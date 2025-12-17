<?php

namespace App\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Trade;

class OrderMatched implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $buyerId;
    public $sellerId;
    public $tradeData;

    public $trade;

    public function __construct($buyerId, $sellerId, Trade $trade)
    {
        $this->buyerId = $buyerId;
        $this->sellerId = $sellerId;
        $this->trade = $trade;
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('private-user.' . $this->buyerId),
            new PrivateChannel('private-user.' . $this->sellerId),
        ];
    }


    public function broadcastAs()
    {
        return 'order.matched';
    }

    public function broadcastWith()
    {
        return [
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
    }

}