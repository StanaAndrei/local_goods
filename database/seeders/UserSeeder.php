<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\Role;

class UserSeeder extends Seeder
{
    private function mkAdmin()
    {
        // Create 1 admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@localgoods.test',
            'role' => Role::ADMIN,
            'buyer_type' => null,
        ]);

    }

    private function mkSellers()
    {
        // Create 10 sellers
        $sellers = User::factory()
            ->count(10)
            ->state(['role' => Role::SELLER, 'buyer_type' => null])
            ->create();
    }

    private function mkBuyers()
    {
        // Create 20 buyers (random buyer_type)
        User::factory()
            ->count(20)
            ->state(['role' => Role::BUYER])
            ->create();
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->mkAdmin();
        $this->mkSellers();
        $this->mkBuyers();
    }
}
