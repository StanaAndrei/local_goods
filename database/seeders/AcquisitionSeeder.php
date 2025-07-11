<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Acquisition;
use App\Models\User;
use App\Models\Product;

class AcquisitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buyer = User::where('role', \App\Enums\Role::BUYER)->first();
        $product = Product::first(); // product already has seller_id

        Acquisition::create([
            'buyer_id' => $buyer->id,
            'product_id' => $product->id,
            'quantity' => 5,
            'cost' => 100.00,
        ]);
    }
}
