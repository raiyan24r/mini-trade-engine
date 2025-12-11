<?php

namespace App\Services;

use App\Models\Asset;
use App\Repositories\UserRepository;

class ProfileService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get user profile with balances
     *
     * @param int $userId
     * @return array|null
     */
    public function getUserProfile(int $userId): ?array
    {
        $user = $this->userRepository->getUserWithAssets($userId);

        if (!$user) {
            return null;
        }

        $formattedAssets = $user->assets->map(function (Asset $asset): array {
            return [
                'symbol' => $asset->symbol,
                'amount' => (float) $asset->amount,
                'locked_amount' => (float) $asset->locked_amount,
            ];
        })->toArray();

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'asset_balances' => $formattedAssets,
        ];
    }
}
