<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\OrderRepository;

class OrderService
{
    private OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getOrderbook(string $symbol): array
    {
        $asks = $this->orderRepository
            ->getOpenOrdersBySymbol($symbol, Order::SIDE_SELL)
            ->map(static function (Order $order): array {
                return [
                    'price' => (float) $order->price,
                    'amount' => (float) $order->amount,
                    'side' => $order->side,
                ];
            })
            ->values()
            ->toArray();

        $bids = $this->orderRepository
            ->getOpenOrdersBySymbol($symbol, Order::SIDE_BUY)
            ->map(static function (Order $order): array {
                return [
                    'price' => (float) $order->price,
                    'amount' => (float) $order->amount,
                    'side' => $order->side,
                ];
            })
            ->values()
            ->toArray();

        return [
            'asks' => $asks,
            'bids' => $bids,
        ];
    }
}
