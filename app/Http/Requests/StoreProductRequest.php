<?php

namespace App\Http\Requests;

use App\Enums\Category;
use App\Enums\Subcategory;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only allow if user is authenticated and is a seller
        return auth()->check() && auth()->user()->isSeller();
    }

    public function rules(): array
    {
        return [
            'category' => 'required|in:'.implode(',', array_column(Category::cases(), 'value')),
            'subcategory' => 'required|in:'.implode(',', array_column(Subcategory::cases(), 'value')),
            'name' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:20',
            'price' => 'required|numeric|min:0.01',
            'additional_info' => 'nullable|json',
            'images.*' => 'required|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'category.required' => 'Please select a category.',
            'subcategory.required' => 'Please select a subcategory.',
            'name.required' => 'Product name is required.',
            'description.required' => 'Description is required.',
            'quantity.required' => 'Quantity is required.',
            'unit.required' => 'Unit is required.',
            'price.required' => 'Price is required.',
            'images.*.required' => 'At least one image is required.',
            'images.*.image' => 'Each file must be an image.',
            'images.*.max' => 'Each image must be less than 2MB.',
        ];
    }
}
