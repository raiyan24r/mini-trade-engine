<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $usersData = [
            ['name' => 'Alice', 'email' => 'alice@example.com', 'balance' => '10000.00000000', 'password' => bcrypt('password')],
            ['name' => 'Bob', 'email' => 'bob@example.com', 'balance' => '10000.00000000', 'password' => bcrypt('password')],
            ['name' => 'Charlie', 'email' => 'charlie@example.com', 'balance' => '10000.00000000', 'password' => bcrypt('password')],
        ];

        $users = [];
        foreach ($usersData as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
            $users[] = $user;
        }

        $assetsData = [
            $users[0]->id => [
                ['symbol' => 'BTC', 'amount' => '0.50000000'],
                ['symbol' => 'ETH', 'amount' => '2.00000000'],
            ],
            $users[1]->id => [
                ['symbol' => 'BTC', 'amount' => '1.00000000'],
                ['symbol' => 'ETH', 'amount' => '5.00000000'],
            ],
            $users[2]->id => [
                ['symbol' => 'BTC', 'amount' => '0.25000000'],
                ['symbol' => 'ETH', 'amount' => '10.00000000'],
            ],
        ];

        foreach ($assetsData as $userId => $assets) {
            foreach ($assets as $assetData) {
                Asset::updateOrCreate(
                    ['user_id' => $userId, 'symbol' => $assetData['symbol']],
                    ['amount' => $assetData['amount'], 'locked_amount' => '0.00000000']
                );
            }
        }
    }
}
