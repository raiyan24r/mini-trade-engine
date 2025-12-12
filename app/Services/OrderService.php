<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Order;
use App\Models\Trade;
use App\Models\User;
use App\Repositories\OrderRepository;
use Illuminate\Database\DatabaseManager;
use RuntimeException;

class OrderService
{
    private const COMMISSION_RATE = 0.015; // 1.5%

    private OrderRepository $orderRepository;
    private DatabaseManager $db;

    public function __construct(
        OrderRepository $orderRepository,
        DatabaseManager $db
    ) {
        $this->orderRepository = $orderRepository;
        $this->db = $db;
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
                    'user_id' => $order->user_id,
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
                    'user_id' => $order->user_id,
                ];
            })
            ->values()
            ->toArray();

        return [
            'asks' => $asks,
            'bids' => $bids,
        ];
    }

    public function getUserOrders(int $userId): array
    {
        $orders = Order::query()
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return $orders->map(static function (Order $order): array {
            return [
                'symbol' => $order->symbol,
                'side' => $order->side,
                'price' => (float) $order->price,
                'amount' => (float) $order->amount,
                'status' => $order->status,
                'date' => $order->created_at->toDateTimeString(),
            ];
        })->toArray();
    }

    /**
     * Create a limit order and attempt immediate full match.
     */
    public function createLimitOrder(int $userId, string $symbol, string $side, float $price, float $amount): array
    {
        return $this->db->transaction(function () use ($userId, $symbol, $side, $price, $amount): array {
            $user = User::whereKey($userId)->lockForUpdate()->firstOrFail();

            if ($side === Order::SIDE_BUY) {
                $this->reserveBuyFunds($user, $price, $amount);
            } else {
                $this->reserveSellAsset($user, $symbol, $amount);
            }

            $order = Order::create([
                'user_id' => $user->id,
                'symbol' => $symbol,
                'side' => $side,
                'price' => $price,
                'amount' => $amount,
                'filled_amount' => 0,
                'status' => Order::STATUS_OPEN,
            ]);

            $trade = null;

            if ($side === Order::SIDE_BUY) {
                $match = $this->orderRepository->findMatchingSell($symbol, $price, $amount);
                if ($match) {
                    $trade = $this->executeTrade($order, $match);
                }
            } else {
                $match = $this->orderRepository->findMatchingBuy($symbol, $price, $amount);
                if ($match) {
                    $trade = $this->executeTrade($match, $order);
                }
            }

            return [
                'order' => $order->fresh(),
                'trade' => $trade,
            ];
        });
    }

    private function reserveBuyFunds(User $user, float $price, float $amount): void
    {
        $cost = $price * $amount;
        $feeReserve = $cost * self::COMMISSION_RATE;
        $totalReserve = $cost + $feeReserve;

        if ((float) $user->balance < $totalReserve) {
            throw new RuntimeException('Insufficient USD balance for this buy order.');
        }

        $newBalance = $this->formatDecimal((float) $user->balance - $totalReserve);
        $user->balance = $newBalance;
        $user->save();
    }

    private function reserveSellAsset(User $user, string $symbol, float $amount): void
    {
        $asset = Asset::where('user_id', $user->id)
            ->where('symbol', $symbol)
            ->lockForUpdate()
            ->first();

        if (!$asset || (float) $asset->amount < $amount) {
            throw new RuntimeException('Insufficient asset balance for this sell order.');
        }

        $asset->amount = $this->formatDecimal((float) $asset->amount - $amount);
        $asset->locked_amount = $this->formatDecimal((float) $asset->locked_amount + $amount);
        $asset->save();
    }

    private function executeTrade(Order $buyOrder, Order $sellOrder): array
    {
        $amount = (float) $buyOrder->amount;
        $matchPrice = (float) $sellOrder->price;
        $volume = $amount * $matchPrice;
        $fee = $volume * self::COMMISSION_RATE;

        $buyer = User::whereKey($buyOrder->user_id)->lockForUpdate()->firstOrFail();
        $seller = User::whereKey($sellOrder->user_id)->lockForUpdate()->firstOrFail();

        $buyerReservedCost = $buyOrder->price * $amount;
        $buyerReservedFee = $buyerReservedCost * self::COMMISSION_RATE;
        $buyerReservedTotal = $buyerReservedCost + $buyerReservedFee;

        $actualSpend = $volume + $fee;
        $refund = $buyerReservedTotal - $actualSpend;
        if ($refund > 0) {
            $buyer->balance = (float) $buyer->balance + $refund;
        }

        $buyerAsset = Asset::where('user_id', $buyer->id)
            ->where('symbol', $buyOrder->symbol)
            ->lockForUpdate()
            ->first();

        if (!$buyerAsset) {
            $buyerAsset = new Asset([
                'user_id' => $buyer->id,
                'symbol' => $buyOrder->symbol,
                'amount' => 0,
                'locked_amount' => 0,
            ]);
        }

        $buyerAsset->amount = (float) $buyerAsset->amount + $amount;
        $buyerAsset->save();

        $sellerAsset = Asset::where('user_id', $seller->id)
            ->where('symbol', $sellOrder->symbol)
            ->lockForUpdate()
            ->first();

        if ($sellerAsset && (float) $sellerAsset->locked_amount >= $amount) {
            $sellerAsset->locked_amount = (float) $sellerAsset->locked_amount - $amount;
            $sellerAsset->save();
        }

        $seller->balance = (float) $seller->balance + $volume;

        $buyer->save();
        $seller->save();

        $buyOrder->filled_amount = (float) $amount;
        $buyOrder->status = Order::STATUS_FILLED;
        $buyOrder->save();

        $sellOrder->filled_amount = (float) $amount;
        $sellOrder->status = Order::STATUS_FILLED;
        $sellOrder->save();

        $trade = Trade::create([
            'buy_order_id' => $buyOrder->id,
            'sell_order_id' => $sellOrder->id,
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'symbol' => $buyOrder->symbol,
            'price' => $matchPrice,
            'amount' => $amount,
        ]);

        return $trade->toArray();
    }

    private function formatDecimal(float $value): float
    {
        return round($value, 8);
    }

    public function cancelOrder(int $orderId, int $userId): void
    {
        $this->db->transaction(function () use ($orderId, $userId): void {
            $order = Order::query()
                ->where('id', $orderId)
                ->where('user_id', $userId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($order->status !== Order::STATUS_OPEN) {
                throw new RuntimeException('Only open orders can be cancelled.');
            }

            $user = User::whereKey($userId)->lockForUpdate()->firstOrFail();

            if ($order->side === Order::SIDE_BUY) {
                // Refund reserved USD
                $cost = $order->price * $order->amount;
                $fee = $cost * self::COMMISSION_RATE;
                $totalReserve = $cost + $fee;

                $user->balance = (float) $user->balance + $totalReserve;
                $user->save();
            } else {
                // Release locked asset
                $asset = Asset::where('user_id', $userId)
                    ->where('symbol', $order->symbol)
                    ->lockForUpdate()
                    ->firstOrFail();

                $asset->amount = (float) $asset->amount + $order->amount;
                $asset->locked_amount = (float) $asset->locked_amount - $order->amount;
                $asset->save();
            }

            $order->status = Order::STATUS_CANCELLED;
            $order->save();
        });
    }
}
