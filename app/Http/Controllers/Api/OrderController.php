<?php

namespace App\Http\Controllers\Api;

use App\Helpers\HttpResponse;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request): JsonResponse
    {
        $symbol = $request->query('symbol');

        if (!$symbol) {
            return HttpResponse::validationError('Symbol is required');
        }

        $orderbook = $this->orderService->getOrderbook($symbol);

        return HttpResponse::success('Orders retrieved successfully', $orderbook);
    }
}
