<?php
namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Trade;

class OrderMatched implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $buyerId;
    public $sellerId;
    public $trade;

    public function __construct($buyerId, $sellerId, Trade $trade)
    {
        $this->buyerId = $buyerId;
        $this->sellerId = $sellerId;
        $this->trade = $trade;
        
        // Log when event is created
        Log::info('OrderMatched event created', [
            'buyer_id' => $buyerId,
            'seller_id' => $sellerId,
            'trade_id' => $trade->id
        ]);
    }

    public function broadcastOn()
    {
        $channels = [
            new PrivateChannel('user.' . $this->buyerId),
            new PrivateChannel('user.' . $this->sellerId),
        ];
        
        // Log channels
        Log::info('Broadcasting on channels', [
            'channels' => array_map(fn($ch) => $ch->name, $channels)
        ]);
        
        return $channels;
    }

    public function broadcastAs()
    {
        return 'order.matched';
    }

    public function broadcastWith()
    {
        $data = [
            'trade' => [
                'id' => $this->trade->id,
                'symbol' => $this->trade->symbol,
                'price' => (string) $this->trade->price,
                'amount' => (string) $this->trade->amount,
                'total_value' => (string) $this->trade->total_value,
                'commission' => (string) $this->trade->commission,
                'created_at' => $this->trade->created_at->toISOString(),
            ],
            'message' => 'Trade executed successfully',
        ];
        
        // Log data being broadcast
        Log::info('Broadcasting data', ['data' => $data]);
        
        return $data;
    }
}