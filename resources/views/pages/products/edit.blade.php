@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 2rem 1rem;">
    <h1 style="font-size: 1.5rem; font-weight: bold; color: #333; margin-bottom: 1.5rem;">Edit Product</h1>
    
    @if(session('success'))
        <div style="background-color: #e8f5e9; color: #4caf50; padding: 0.75rem 1rem; border-radius: 4px; border: 1px solid #c8e6c9; margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
    @endif
    
    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data" style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        @csrf
        @method('PUT')

        <!-- Set old values for the category and subcategory -->
        @php
            session()->flash('_old_input', [
                'category' => $product->category instanceof \App\Enums\Category 
                    ? $product->category->value 
                    : $product->category,
                'subcategory' => $product->subcategory instanceof \App\Enums\Subcategory 
                    ? $product->subcategory->value 
                    : $product->subcategory,
                ...(old() ?: [])
            ]);
        @endphp

        <!-- Category and Subcategory Selector -->
        @livewire('pages.products.category-subcategory-selector')

        <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.25rem;">Name:</label>
            <input 
                type="text" 
                name="name" 
                value="{{ old('name', $product->name) }}" 
                required
                style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.875rem;"
            >
            @error('name') <div style="color: #f44336; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.25rem;">Description:</label>
            <textarea 
                name="description" 
                required
                style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.875rem; min-height: 100px;"
            >{{ old('description', $product->description) }}</textarea>
            @error('description') <div style="color: #f44336; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.25rem;">Quantity:</label>
            <input 
                type="number" 
                step="0.01" 
                name="quantity" 
                value="{{ old('quantity', $product->quantity) }}" 
                required
                style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.875rem;"
            >
            @error('quantity') <div style="color: #f44336; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.25rem;">Unit:</label>
            <input 
                type="text" 
                name="unit" 
                value="{{ old('unit', $product->unit) }}" 
                required
                style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.875rem;"
            >
            @error('unit') <div style="color: #f44336; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.25rem;">Price (€):</label>
            <input 
                type="number" 
                step="0.01" 
                name="price" 
                value="{{ old('price', $product->price) }}" 
                required
                style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.875rem;"
            >
            @error('price') <div style="color: #f44336; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
        </div>

        <!-- Additional Info Section -->
        @php
            $additionalInfo = is_string($product->additional_info) 
                ? json_decode($product->additional_info, true) 
                : $product->additional_info;
            
            $initialPairs = [];
            if (!empty($additionalInfo)) {
                foreach ($additionalInfo as $key => $value) {
                    $initialPairs[] = ['key' => $key, 'value' => $value];
                }
            }
        @endphp
        
        @livewire('pages.products.additional-info-editor', ['initialPairs' => $initialPairs])
        @error('additional_info') <div style="color: #f44336; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror

        <!-- Current Images -->
        @if($product->images && $product->images->count() > 0)
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.25rem;">Current Images:</label>
                <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
                    @foreach($product->images as $img)
                        <div style="position: relative;">
                            <img src="{{ asset('storage/' . $img->image_path) }}" alt="Product Image" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;">
                            <div style="margin-top: 0.25rem; text-align: center;">
                                <label style="display: flex; align-items: center; justify-content: center; gap: 0.25rem;">
                                    <input type="checkbox" name="delete_images[]" value="{{ $img->id }}">
                                    <span style="font-size: 0.75rem;">Delete</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- New Images -->
        <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.25rem;">
                {{ $product->images && $product->images->count() > 0 ? 'Add More Images:' : 'Images:' }}
            </label>
            <input 
                type="file" 
                name="images[]" 
                multiple
                style="width: 100%; padding: 0.5rem 0;"
            >
            <p style="font-size: 0.75rem; color: #666; margin-top: 0.25rem;">You can select multiple images at once.</p>
            @error('images.*') <div style="color: #f44336; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-top: 1.5rem; display: flex; gap: 0.75rem;">
            <button 
                type="submit" 
                style="background-color: #ff6d00; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; font-weight: 500; cursor: pointer;"
            >
                Update Product
            </button>
            <a 
                href="{{ route('products.show', $product) }}" 
                style="background-color: #eee; color: #333; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; font-weight: 500;"
            >
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection