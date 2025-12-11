<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * Get user by ID
     *
     * @param int $userId
     * @return User|null
     */
    public function getUserById(int $userId): ?User
    {
        return User::find($userId);
    }

    /**
     * Get user with their assets
     *
     * @param int $userId
     * @return User|null
     */
    public function getUserWithAssets(int $userId): ?User
    {
        return User::with('assets')->find($userId);
    }
}
