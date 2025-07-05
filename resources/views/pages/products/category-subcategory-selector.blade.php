<?php

use function Livewire\Volt\{state, computed};
use App\Enums\Category;
use App\Helpers\CategoryHelper;

// Define initial state
state([
    'selectedCategory' => old('category'),
    'selectedSubcategory' => old('subcategory'),
]);

// Method to update category and reset subcategory
$updateCategory = function ($value) {
    $this->selectedCategory = $value;
    $this->selectedSubcategory = null;
};

// Computed property to get available subcategories
$availableSubcategories = computed(function () {
    if ($this->selectedCategory) {
        try {
            $category = Category::from((int)$this->selectedCategory);
            return CategoryHelper::subcategoriesForCategory($category);
        } catch (\Exception $e) {
            return [];
        }
    }
    return [];
});

?>

<div>
    <div class="mb-4">
        <label class="block text-gray-700 mb-2">Category:</label>
        <select wire:model.live="selectedCategory" name="category" class="w-full px-3 py-2 border rounded" required>
            <option value="">Select a category</option>
            @foreach(Category::cases() as $cat)
                <option value="{{ $cat->value }}">
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
        @error('category') <div class="text-red-500 mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 mb-2">Subcategory:</label>
        <select wire:model.live="selectedSubcategory" name="subcategory" class="w-full px-3 py-2 border rounded" required {{ empty($this->availableSubcategories) ? 'disabled' : '' }}>
            <option value="">{{ empty($this->availableSubcategories) ? 'Select a category first' : 'Select a subcategory' }}</option>
            @foreach($this->availableSubcategories as $sub)
                <option value="{{ $sub->value }}">
                    {{ $sub->name }}
                </option>
            @endforeach
        </select>
        @error('subcategory') <div class="text-red-500 mt-1">{{ $message }}</div> @enderror
    </div>
</div>