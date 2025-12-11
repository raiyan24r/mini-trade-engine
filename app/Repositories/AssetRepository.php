<?php

namespace App\Repositories;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Collection;

class AssetRepository
{
    /**
     * Get all assets for a user
     *
     * @param int $userId
     * @return Collection
     */
    public function getAssetsByUserId(int $userId): Collection
    {
        return Asset::where('user_id', $userId)->get();
    }

    /**
     * Get a specific asset for a user
     *
     * @param int $userId
     * @param string $symbol
     * @return Asset|null
     */
    public function getAssetByUserAndSymbol(int $userId, string $symbol): ?Asset
    {
        return Asset::where('user_id', $userId)
            ->where('symbol', $symbol)
            ->first();
    }
}
