<?php

namespace App\Helpers;

use App\Enums\Category;
use App\Enums\Subcategory;

class CategoryHelper
{
    public static function subcategoriesForCategory(Category $category): array
    {
        return match ($category) {
            Category::DAIRY => [
                Subcategory::CHEESE,
                Subcategory::YOGURT,
                Subcategory::MILK,
                Subcategory::OTHER,
            ],
            Category::HANDMADES => [
                Subcategory::FURNITURE,
                Subcategory::DECORATION,
                Subcategory::TOOLS,
                Subcategory::OTHER,
            ],
            Category::MEAT => [
                Subcategory::OTHER,
            ],
            Category::VEGETABLES => [
                Subcategory::OTHER,
            ],
            Category::FRUITS => [
                Subcategory::OTHER,
            ]
            // ...
        };
    }
}
