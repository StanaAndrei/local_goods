<?php

namespace App\Http\Controllers;

use App\Enums\Category;
use App\Enums\Subcategory;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function create()
    {
        return view('pages.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|in:'.implode(',', array_column(Category::cases(), 'value')),
            'subcategory' => 'required|in:'.implode(',', array_column(Subcategory::cases(), 'value')),
            'name' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:20',
            'price' => 'required|numeric|min:0.01',
            'additional_info' => 'nullable|json',
            'images.*' => 'required|image|max:2048',
        ]);

        $product = Product::create([
            'seller_id' => auth()->id(),
            'category' => $request->category,
            'subcategory' => $request->subcategory,
            'name' => $request->name,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'price' => $request->price,
            'additional_info' => $request->additional_info ? json_decode($request->additional_info, true) : null,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('products.create')->with('success', 'Product created!');
    }
}
