<?php

namespace App\Services;

use App\Models\UserBalanceHistory;

class BalanceHistoryQueryService
{
    public function getUserBalanceHistory(int $userId): array
    {
        $histories = UserBalanceHistory::query()
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return $histories->map(static function (UserBalanceHistory $history): array {
            return [
                'id' => $history->id,
                'balance' => (float) $history->balance,
                'action' => $history->action,
                'credit' => (float) $history->credit,
                'debit' => (float) $history->debit,
                'description' => $history->description,
                'reference_type' => $history->reference_type,
                'reference_id' => $history->reference_id,
                'date' => $history->created_at->toDateTimeString(),
            ];
        })->toArray();
    }
}
