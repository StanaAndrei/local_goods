<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\Role;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Create 1 admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@localgoods.test',
            'role' => Role::ADMIN,
            'buyer_type' => null,
        ]);

        // Create 10 sellers
        $sellers = User::factory()
            ->count(10)
            ->state(['role' => Role::SELLER, 'buyer_type' => null])
            ->create();

        // Create 20 buyers (random buyer_type)
        User::factory()
            ->count(20)
            ->state(['role' => Role::BUYER])
            ->create();

        // Create 50 products for random sellers
        $sellersIds = $sellers->pluck('id')->toArray();
        Product::factory()
            ->count(50)
            ->state(function () use ($sellersIds) {
                return [
                    'seller_id' => $sellersIds[array_rand($sellersIds)],
                ];
            })
            ->create();
    }
}
