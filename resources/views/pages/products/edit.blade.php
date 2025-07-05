@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-6">Edit Product</h1>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Category:</label>
            <select name="category" class="w-full px-3 py-2 border rounded" required>
                @foreach(\App\Enums\Category::cases() as $cat)
                    <option value="{{ $cat->value }}" 
                        {{ $product->category instanceof \App\Enums\Category 
                            ? ($product->category === $cat ? 'selected' : '') 
                            : ($product->category == $cat->value ? 'selected' : '') }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            @error('category') <div class="text-red-500 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Subcategory:</label>
            <select name="subcategory" class="w-full px-3 py-2 border rounded" required>
                @foreach(\App\Enums\Subcategory::cases() as $sub)
                    <option value="{{ $sub->value }}" 
                        {{ $product->subcategory instanceof \App\Enums\Subcategory 
                            ? ($product->subcategory === $sub ? 'selected' : '') 
                            : ($product->subcategory == $sub->value ? 'selected' : '') }}>
                        {{ $sub->name }}
                    </option>
                @endforeach
            </select>
            @error('subcategory') <div class="text-red-500 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Name:</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" 
                   class="w-full px-3 py-2 border rounded" required>
            @error('name') <div class="text-red-500 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Description:</label>
            <textarea name="description" class="w-full px-3 py-2 border rounded" rows="4" required>{{ old('description', $product->description) }}</textarea>
            @error('description') <div class="text-red-500 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Quantity:</label>
            <input type="number" step="0.01" name="quantity" value="{{ old('quantity', $product->quantity) }}" 
                   class="w-full px-3 py-2 border rounded" required>
            @error('quantity') <div class="text-red-500 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Unit:</label>
            <input type="text" name="unit" value="{{ old('unit', $product->unit) }}" 
                   class="w-full px-3 py-2 border rounded" required>
            @error('unit') <div class="text-red-500 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Price:</label>
            <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" 
                   class="w-full px-3 py-2 border rounded" required>
            @error('price') <div class="text-red-500 mt-1">{{ $message }}</div> @enderror
        </div>

        <!-- Additional Info Section using Volt Component -->
        <div class="mb-4">
            @php
                $additionalInfo = is_string($product->additional_info) 
                    ? json_decode($product->additional_info, true) 
                    : $product->additional_info;
                
                // Initialize the Volt component with existing data
                $initialPairs = [];
                if (!empty($additionalInfo)) {
                    foreach ($additionalInfo as $key => $value) {
                        $initialPairs[] = ['key' => $key, 'value' => $value];
                    }
                }
            @endphp
            
            @livewire('pages.products.additional-info-editor', ['initialPairs' => $initialPairs])
            @error('additional_info') <div class="text-red-500 mt-1">{{ $message }}</div> @enderror
        </div>

        <!-- Current Images -->
        @if($product->images && $product->images->count() > 0)
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Current Images:</label>
                <div class="flex flex-wrap gap-4">
                    @foreach($product->images as $image)
                        <div class="relative">
                            <img src="{{ asset('storage/' . $image->path) }}" 
                                 alt="Product image" 
                                 class="h-24 w-24 object-cover rounded">
                            <div class="mt-1">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="form-checkbox">
                                    <span class="ml-2 text-sm text-red-600">Delete</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- New Images -->
        <div class="mb-6">
            <label class="block text-gray-700 mb-2">
                {{ $product->images && $product->images->count() > 0 ? 'Add More Images:' : 'Images:' }}
            </label>
            <input type="file" name="images[]" multiple 
                   class="w-full px-3 py-2 border rounded" 
                   {{ $product->images && $product->images->count() > 0 ? '' : 'required' }}>
            <p class="text-sm text-gray-500 mt-1">You can select multiple images at once.</p>
            @error('images.*') <div class="text-red-500 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="flex gap-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Update Product
            </button>
            
            <a href="{{ route('products.show', $product) }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection