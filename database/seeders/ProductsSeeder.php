<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sellersIds = User::where('role', \App\Enums\Role::SELLER)->pluck('id')->toArray();

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
