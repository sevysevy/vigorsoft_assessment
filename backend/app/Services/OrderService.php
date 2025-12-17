<?php
namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Asset;
use App\Models\Trade;
use App\Events\OrderMatched;
use Illuminate\Support\Facades\DB;

class OrderService
{
    const COMMISSION_RATE = 0.015; // 1.5%
    
    public function placeOrder(User $user, array $data): Order
    {
        return DB::transaction(function () use ($user, $data) {

            $order = $data['side'] === 'buy'
                ? $this->placeBuyOrder($user, $data)
                : $this->placeSellOrder($user, $data);

            $trade = $this->matchOrder($order);

            if ($trade) {
                DB::afterCommit(function () use ($trade, $order) {
                    broadcast(new OrderMatched(
                        $trade->buyOrder->user_id,
                        $trade->sellOrder->user_id,
                        $trade
                    ));
                });
            }

            return $order;
        });
    }

    
    private function placeBuyOrder(User $user, array $data): Order
    {
        $totalCost = $data['amount'] * $data['price'];
        
        if ($user->balance < $totalCost) {
            throw new \Exception('Insufficient balance');
        }
        
        // Lock USD (already atomic in transaction)
        $user->decrement('balance', $totalCost);
        
        return Order::create([
            'user_id' => $user->id,
            'symbol' => $data['symbol'],
            'side' => 'buy',
            'price' => $data['price'],
            'amount' => $data['amount'],
            'status' => 'open'
        ]);
    }
    
    private function placeSellOrder(User $user, array $data): Order
    {
        $asset = Asset::firstOrCreate(
            ['user_id' => $user->id, 'symbol' => $data['symbol']],
            ['amount' => 0, 'locked_amount' => 0]
        );
        
        if ($asset->amount < $data['amount']) {
            throw new \Exception('Insufficient asset amount');
        }
        
        $asset->decrement('amount', $data['amount']);
        $asset->increment('locked_amount', $data['amount']);
        
        return Order::create([
            'user_id' => $user->id,
            'symbol' => $data['symbol'],
            'side' => 'sell',
            'price' => $data['price'],
            'amount' => $data['amount'],
            'status' => 'open'
        ]);
    }
    
    public function matchOrder(Order $order): ?Trade
    {
        $match = Order::where('symbol', $order->symbol)
            ->where('side', $order->side === 'buy' ? 'sell' : 'buy')
            ->where('status', 'open')
            ->where('amount', $order->amount)
            //->where('user_id', '!=', $order->user_id)
            ->when($order->side === 'buy', fn ($q) =>
                $q->where('price', '<=', $order->price)->orderBy('price', 'asc')
            )
            ->when($order->side === 'sell', fn ($q) =>
                $q->where('price', '>=', $order->price)->orderBy('price', 'desc')
            )
            ->lockForUpdate()
            ->first();

        if (!$match) {
            return null;
        }

        return $this->executeTrade($order, $match);
    }


    
    private function executeTrade(Order $order1, Order $order2)
    {

        $buyOrder = $order1->side === 'buy' ? $order1 : $order2;
        $sellOrder = $order1->side === 'sell' ? $order1 : $order2;


        $buyer = User::where('id', $buyOrder->user_id)->lockForUpdate()->first();
        $seller = User::where('id', $sellOrder->user_id)->lockForUpdate()->first();

        $buyerAsset = Asset::where('user_id', $buyer->id)
            ->where('symbol', $buyOrder->symbol)
            ->lockForUpdate()
            ->firstOrCreate(
                ['user_id' => $buyer->id, 'symbol' => $buyOrder->symbol],
                ['amount' => 0, 'locked_amount' => 0]
            );

        $sellerAsset = Asset::where('user_id', $seller->id)
            ->where('symbol', $sellOrder->symbol)
            ->lockForUpdate()
            ->first();

        
        $price = $sellOrder->price;
        $amount = $buyOrder->amount;
        $tradeValue = $amount * $price;
        $commission = $tradeValue * self::COMMISSION_RATE;
        
        // Update orders to filled
        $buyOrder->update(['status' => 'filled', 'filled_amount' => $amount]);
        $sellOrder->update(['status' => 'filled', 'filled_amount' => $amount]);
        
        // Process buyer
        $buyer = $buyOrder->user;
        $lockedAmount = $buyOrder->amount * $buyOrder->price;
        
        // Add asset to buyer
        $buyerAsset = Asset::firstOrCreate(
            ['user_id' => $buyer->id, 'symbol' => $buyOrder->symbol],
            ['amount' => 0, 'locked_amount' => 0]
        );
        $buyerAsset->increment('amount', $amount);
        
        // Refund excess if buy price > sell price
        if ($buyOrder->price > $sellOrder->price) {
            $refund = ($buyOrder->price - $sellOrder->price) * $amount;
            $buyer->increment('balance', $refund);
        }
        
        // Deduct commission from buyer
        $buyer->decrement('balance', $commission);
        
        // Process seller
        $seller = $sellOrder->user;
        $sellerAsset = Asset::where([
            'user_id' => $seller->id,
            'symbol' => $sellOrder->symbol
        ])->first();
        
        // Release locked assets
        $sellerAsset->decrement('locked_amount', $amount);
        
        // Seller gets full trade value (buyer pays commission)
        $seller->increment('balance', $tradeValue);
        
        // ========== CREATE TRADE RECORD (MINIMAL) ==========
        // Check if Trade model exists, create it if not
        $trade = Trade::create([
            'buy_order_id' => $buyOrder->id,
            'sell_order_id' => $sellOrder->id,
            'symbol' => $buyOrder->symbol,
            'price' => $price,
            'amount' => $amount,
            'total_value' => $tradeValue,
            'commission' => $commission,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return $trade;
    }


    // app/Services/OrderService.php - Add this method
    public function cancelOrder(Order $order)
    {
        return DB::transaction(function () use ($order) {
            if ($order->status !== 'open') {
                throw new \Exception('Order cannot be cancelled');
            }

            // Update order status
            $order->update(['status' => 'cancelled']);

            if ($order->side === 'buy') {
                // Release locked USD back to user
                $lockedAmount = $order->amount * $order->price;
                $filledValue = $order->filled_amount * $order->price;
                $toRelease = $lockedAmount - $filledValue;
                
                if ($toRelease > 0) {
                    $order->user->increment('balance', $toRelease);
                }
            } else {
                // Release locked assets back to available amount
                $toRelease = $order->amount - $order->filled_amount;
                
                $asset = Asset::where([
                    'user_id' => $order->user_id,
                    'symbol' => $order->symbol
                ])->first();
                
                if ($asset && $toRelease > 0) {
                    $asset->decrement('locked_amount', $toRelease);
                    $asset->increment('amount', $toRelease);
                }
            }

            return $order;
        });
    }
}