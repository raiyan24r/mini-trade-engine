<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserBalanceHistory;

class BalanceHistoryService
{
    public static function log(
        User $user,
        string $action,
        float $credit,
        float $debit,
        string $description,
        ?string $referenceType = null,
        ?int $referenceId = null
    ): void {
        UserBalanceHistory::create([
            'user_id' => $user->id,
            'balance' => (float) $user->balance,
            'action' => $action,
            'credit' => $credit,
            'debit' => $debit,
            'description' => $description,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
        ]);
    }

    public static function logOrderReservation(User $user, float $amount, int $orderId, string $side): void
    {
        self::log(
            user: $user,
            action: 'order_reserved',
            credit: 0,
            debit: $amount,
            description: "Reserved $amount USD for {$side} order #{$orderId}",
            referenceType: 'Order',
            referenceId: $orderId
        );
    }

    public static function logOrderRefund(User $user, float $amount, int $orderId): void
    {
        self::log(
            user: $user,
            action: 'order_refund',
            credit: $amount,
            debit: 0,
            description: "Refunded $amount USD from order #{$orderId}",
            referenceType: 'Order',
            referenceId: $orderId
        );
    }

    public static function logOrderCancellation(User $user, float $amount, int $orderId): void
    {
        self::log(
            user: $user,
            action: 'order_cancelled',
            credit: $amount,
            debit: 0,
            description: "Cancelled order #{$orderId}, refunded $amount USD",
            referenceType: 'Order',
            referenceId: $orderId
        );
    }

    public static function logTradeEarnings(User $user, float $amount, int $tradeId, string $symbol): void
    {
        self::log(
            user: $user,
            action: 'trade_completed',
            credit: $amount,
            debit: 0,
            description: "Received $amount USD from selling {$symbol} in trade #{$tradeId}",
            referenceType: 'Trade',
            referenceId: $tradeId
        );
    }
}
