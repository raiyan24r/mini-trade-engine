<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderMatched implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public int $buyerId;
    public int $sellerId;
    public string $symbol;
    public float $price;
    public float $amount;
    public int $buyOrderId;
    public int $sellOrderId;
    public int $tradeId;

    public function __construct(
        int $buyerId,
        int $sellerId,
        string $symbol,
        float $price,
        float $amount,
        int $buyOrderId,
        int $sellOrderId,
        int $tradeId
    ) {
        $this->buyerId = $buyerId;
        $this->sellerId = $sellerId;
        $this->symbol = $symbol;
        $this->price = $price;
        $this->amount = $amount;
        $this->buyOrderId = $buyOrderId;
        $this->sellOrderId = $sellOrderId;
        $this->tradeId = $tradeId;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("user.{$this->buyerId}"),
            new PrivateChannel("user.{$this->sellerId}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'order.matched';
    }

    public function broadcastWith(): array
    {
        return [
            'buyer_id' => $this->buyerId,
            'seller_id' => $this->sellerId,
            'symbol' => $this->symbol,
            'price' => $this->price,
            'amount' => $this->amount,
            'buy_order_id' => $this->buyOrderId,
            'sell_order_id' => $this->sellOrderId,
            'trade_id' => $this->tradeId,
        ];
    }
}
