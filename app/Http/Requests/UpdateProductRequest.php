<?php

namespace App\Http\Requests;

use App\Enums\Category;
use App\Enums\Subcategory;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only allow if user is authenticated and is the owner of the product
        $product = $this->route('product');

        return auth()->check() && auth()->id() === $product->seller_id;
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
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'nullable|integer|exists:product_images,id',
        ];
    }

    public function messages(): array
    {
        return [
            'category.required' => 'Please select a category.',
            'subcategory.required' => 'Please select a subcategory.',
            'name.required' => 'Product name is required.',
            'description.required' => 'Description is required.',
            'description.min' => 'Description must be at least 10 characters.',
            'quantity.required' => 'Quantity is required.',
            'quantity.min' => 'Quantity must be greater than zero.',
            'unit.required' => 'Unit is required.',
            'price.required' => 'Price is required.',
            'price.min' => 'Price must be greater than zero.',
            'images.*.image' => 'Each file must be an image.',
            'images.*.max' => 'Each image must be less than 2MB.',
            'delete_images.*.exists' => 'One or more selected images do not exist.',
        ];
    }
}
