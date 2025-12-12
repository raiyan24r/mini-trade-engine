<?php

namespace App\Http\Controllers\Api;

use App\Helpers\HttpResponse;
use App\Http\Requests\CreateOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

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

    public function store(CreateOrderRequest $request): JsonResponse
    {
        try {
            $result = $this->orderService->createLimitOrder(
                $request->user()->id,
                $request->validated('symbol'),
                $request->validated('side'),
                (float) $request->validated('price'),
                (float) $request->validated('amount')
            );

            return HttpResponse::success('Order created successfully', $result);
        } catch (RuntimeException $e) {
            return HttpResponse::error($e->getMessage(), null, 400);
        }
    }

    public function myOrders(Request $request): JsonResponse
    {
        $orders = $this->orderService->getUserOrders($request->user()->id);

        return HttpResponse::success('User orders retrieved successfully', $orders);
    }

    public function cancel(Request $request, int $id): JsonResponse
    {
        try {
            $this->orderService->cancelOrder($id, $request->user()->id);

            return HttpResponse::success('Order cancelled successfully');
        } catch (RuntimeException $e) {
            return HttpResponse::error($e->getMessage(), null, 400);
        }
    }
}
