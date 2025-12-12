<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Collection;

class OrderRepository
{
    public function getOpenOrdersBySymbol(string $symbol, string $side): Collection
    {
        return Order::query()
            ->where('symbol', $symbol)
            ->where('side', $side)
            ->where('status', Order::STATUS_OPEN)
            ->orderBy('price', $side === Order::SIDE_BUY ? 'desc' : 'asc')
            ->get();
    }

    public function findMatchingSell(string $symbol, float $price, float $amount): ?Order
    {
        return Order::query()
            ->where('symbol', $symbol)
            ->where('side', Order::SIDE_SELL)
            ->where('status', Order::STATUS_OPEN)
            ->where('price', '<=', $price)
            ->where('amount', $amount)
            ->orderBy('price')
            ->orderBy('id')
            ->lockForUpdate()
            ->first();
    }

    public function findMatchingBuy(string $symbol, float $price, float $amount): ?Order
    {
        return Order::query()
            ->where('symbol', $symbol)
            ->where('side', Order::SIDE_BUY)
            ->where('status', Order::STATUS_OPEN)
            ->where('price', '>=', $price)
            ->where('amount', $amount)
            ->orderByDesc('price')
            ->orderBy('id')
            ->lockForUpdate()
            ->first();
    }
}
