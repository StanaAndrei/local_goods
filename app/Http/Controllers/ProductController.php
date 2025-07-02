<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use App\Models\ProductImage;

class ProductController extends Controller
{
    public function create()
    {
        return view('pages.products.create');
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        $product = Product::create([
            'seller_id' => auth()->id(),
            'category' => $data['category'],
            'subcategory' => $data['subcategory'],
            'name' => $data['name'],
            'description' => $data['description'],
            'quantity' => $data['quantity'],
            'unit' => $data['unit'],
            'price' => $data['price'],
            'additional_info' => isset($data['additional_info']) ? json_decode($data['additional_info'], true) : null,
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

    public function mine()
    {
        $products = auth()->user()->products()->with('images')->latest()->get();

        return view('pages.products.mine', compact('products'));
    }

    public function destroy(\App\Models\Product $product)
    {
        // Ensure the product belongs to the current seller
        if ($product->seller_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete images from storage
        foreach ($product->images as $image) {
            \Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $product->delete();

        return redirect()->route('products.mine')->with('success', 'Product deleted!');
    }
}
