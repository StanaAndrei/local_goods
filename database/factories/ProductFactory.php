<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\Category;
use App\Enums\Subcategory;
use App\Helpers\CategoryHelper;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $category = $this->faker->randomElement(Category::cases());
        $subcategories = CategoryHelper::subcategoriesForCategory($category);
        $subcategory = $this->faker->randomElement($subcategories);

        return [
            'seller_id' => \App\Models\User::factory()->state(['role' => \App\Enums\Role::SELLER]),
            'category' => $category->value,
            'subcategory' => $subcategory->value,
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'quantity' => $this->faker->randomFloat(2, 1, 100),
            'unit' => $this->faker->randomElement(['kg', 'pcs', 'liters']),
            'price' => $this->faker->randomFloat(2, 1, 500),
            'additional_info' => json_encode(['origin' => $this->faker->city()]),
        ];
    }
}
