<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderMatched implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $buyerId;
    public $sellerId;
    public $tradeData;

    public function __construct($buyerId, $sellerId, $tradeData)
    {
        $this->buyerId = $buyerId;
        $this->sellerId = $sellerId;
        $this->tradeData = $tradeData;
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('user.' . $this->buyerId),
            new PrivateChannel('user.' . $this->sellerId),
        ];
    }

    public function broadcastAs()
    {
        return 'order.matched';
    }

    public function broadcastWith()
    {
        return [
            'message' => 'Order matched successfully',
            'symbol' => $this->tradeData['symbol'],
            'price' => $this->tradeData['price'],
            'amount' => $this->tradeData['amount'],
            'value' => $this->tradeData['value'],
            'commission' => $this->tradeData['commission']
        ];
    }
}