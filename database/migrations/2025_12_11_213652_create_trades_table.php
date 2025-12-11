<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('buy_order_id');
            $table->unsignedBigInteger('sell_order_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('seller_id');
            $table->string('symbol', 10);
            $table->decimal('price', 20, 8);
            $table->decimal('amount', 20, 8);
            $table->timestamps();

            $table->index('buy_order_id');
            $table->index('sell_order_id');
            $table->index('buyer_id');
            $table->index('seller_id');
            $table->index('symbol');
            $table->index(['symbol', 'created_at']);

            $table->foreign('buy_order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('sell_order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('buyer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
