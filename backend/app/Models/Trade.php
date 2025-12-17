<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trade extends Model
{
    protected $fillable = [
        'buy_order_id',
        'sell_order_id',
        'symbol',
        'price',
        'amount',
        'total_value',
        'commission'
    ];

    protected $casts = [
        'price' => 'decimal:8',
        'amount' => 'decimal:8',
        'total_value' => 'decimal:8',
        'commission' => 'decimal:8',
        'created_at' => 'datetime'
    ];

    public function buyOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'buy_order_id');
    }

    public function sellOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'sell_order_id');
    }
}