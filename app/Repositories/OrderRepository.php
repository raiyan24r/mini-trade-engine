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
}
