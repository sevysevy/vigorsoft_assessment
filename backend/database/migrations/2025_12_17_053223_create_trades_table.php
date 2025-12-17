<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buy_order_id')->constrained('orders');
            $table->foreignId('sell_order_id')->constrained('orders');
            $table->string('symbol', 10);
            $table->decimal('price', 18, 8);
            $table->decimal('amount', 18, 8);
            $table->decimal('total_value', 18, 8);
            $table->decimal('commission', 18, 8);
            $table->timestamps();
            
            $table->index(['symbol', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};