<?php
namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Asset;
use App\Events\OrderMatched;
use Illuminate\Support\Facades\DB;

class OrderService
{
    const COMMISSION_RATE = 0.015; // 1.5%
    
    public function placeOrder(User $user, array $data): Order
    {
        return DB::transaction(function () use ($user, $data) {
            if ($data['side'] === 'buy') {
                $order = $this->placeBuyOrder($user, $data);
            } else {
                $order = $this->placeSellOrder($user, $data);
            }
            
            // Immediate matching attempt
            $this->matchOrder($order);
            
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
    
    public function matchOrder(Order $newOrder)
    {
        DB::transaction(function () use ($newOrder) {
            // Find matching order based on side
            if ($newOrder->side === 'buy') {
                $match = Order::where('symbol', $newOrder->symbol)
                    ->where('side', 'sell')
                    ->where('status', 'open')
                    ->where('price', '<=', $newOrder->price)
                    ->orderBy('price')
                    ->orderBy('created_at')
                    ->lockForUpdate()
                    ->first();
            } else {
                $match = Order::where('symbol', $newOrder->symbol)
                    ->where('side', 'buy')
                    ->where('status', 'open')
                    ->where('price', '>=', $newOrder->price)
                    ->orderBy('price', 'desc')
                    ->orderBy('created_at')
                    ->lockForUpdate()
                    ->first();
            }
            
            if (!$match || $match->amount !== $newOrder->amount) {
                return; 
            }
            
            // Execute the match
            $this->executeTrade($newOrder, $match);
        });
    }
    
    private function executeTrade(Order $order1, Order $order2)
    {
        $buyOrder = $order1->side === 'buy' ? $order1 : $order2;
        $sellOrder = $order1->side === 'sell' ? $order1 : $order2;
        
        $price = $sellOrder->price;
        $amount = $buyOrder->amount;
        $tradeValue = $amount * $price;
        $commission = $tradeValue * self::COMMISSION_RATE;
        
        // Update orders to filled
        $buyOrder->update(['status' => 'filled', 'filled_amount' => $amount]);
        $sellOrder->update(['status' => 'filled', 'filled_amount' => $amount]);
        

        $buyer = $buyOrder->user;
        $lockedAmount = $buyOrder->amount * $buyOrder->price;
        

        $buyerAsset = Asset::firstOrCreate(
            ['user_id' => $buyer->id, 'symbol' => $buyOrder->symbol],
            ['amount' => 0, 'locked_amount' => 0]
        );
        $buyerAsset->increment('amount', $amount);
        
        if ($buyOrder->price > $sellOrder->price) {
            $refund = ($buyOrder->price - $sellOrder->price) * $amount;
            $buyer->increment('balance', $refund);
        }
        
        $buyer->decrement('balance', $commission);
        
        $seller = $sellOrder->user;
        $sellerAsset = Asset::where([
            'user_id' => $seller->id,
            'symbol' => $sellOrder->symbol
        ])->first();
        
        $sellerAsset->decrement('locked_amount', $amount);
        

        $seller->increment('balance', $tradeValue);
        
        broadcast(new OrderMatched(
            $buyOrder->user_id,
            $sellOrder->user_id,
            [
                'symbol' => $buyOrder->symbol,
                'price' => $price,
                'amount' => $amount,
                'value' => $tradeValue,
                'commission' => $commission,
                'buy_order_id' => $buyOrder->id,
                'sell_order_id' => $sellOrder->id
            ]
        ));
    }
}