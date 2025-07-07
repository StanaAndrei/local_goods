<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function edit(Product $product)
    {
        if (auth()->id() !== $product->seller_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('pages.products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        // The authorization is already handled in the request class

        // Get validated data
        $validated = $request->validated();

        // Process additional info (already JSON encoded by the Volt component)
        $additionalInfo = null;
        if (isset($validated['additional_info']) && ! empty($validated['additional_info'])) {
            $additionalInfo = json_decode($validated['additional_info'], true);
        }

        // Update the product
        $product->update([
            'category' => $validated['category'],
            'subcategory' => $validated['subcategory'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'quantity' => $validated['quantity'],
            'unit' => $validated['unit'],
            'price' => $validated['price'],
            'additional_info' => $additionalInfo,
        ]);

        // Handle image deletions
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = $product->images()->find($imageId);
                if ($image) {
                    // Delete the file from storage
                    Storage::delete('public/'.$image->path);
                    // Delete the record
                    $image->delete();
                }
            }
        }

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $path = $imageFile->store('product-images', 'public');

                // Create image record
                $product->images()->create([
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('products.show', $product)
            ->with('success', 'Product updated successfully.');
    }

    public function show(Product $product)
    {
        // Eager load the seller and images relationships
        $product->load(['seller', 'images']);

        return view('pages.products.show', compact('product'));
    }

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

    /**
     * Display a listing of all products with filtering and pagination.
     */
    public function all(Request $request)
    {
        $query = Product::with(['images', 'seller']);

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply category filter
        if ($request->filled('category')) {
            $query->where('category', (int) $request->category);

            // Get available subcategories for the selected category
            $category = \App\Enums\Category::from((int) $request->category);
            $availableSubcategories = \App\Helpers\CategoryHelper::subcategoriesForCategory($category);
        } else {
            $availableSubcategories = [];
        }

        // Apply subcategory filter
        if ($request->filled('subcategory')) {
            $query->where('subcategory', (int) $request->subcategory);
        }

        // Apply max price filter
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        // Apply date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Apply sorting
        $query->latest();

        // Get paginated results
        $products = $query->paginate(12)->withQueryString();

        return view('pages.products.all', compact('products', 'availableSubcategories'));
    }
}
