@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Product</h1>
    
    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif
    
    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Set old values for the category and subcategory -->
        @php
            // Set old values for the category and subcategory to be used by the component
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

        <div>
            <label>Name:</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" required>
            @error('name') <div style="color:red">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Description:</label>
            <textarea name="description" required>{{ old('description', $product->description) }}</textarea>
            @error('description') <div style="color:red">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Quantity:</label>
            <input type="number" step="0.01" name="quantity" value="{{ old('quantity', $product->quantity) }}" required>
            @error('quantity') <div style="color:red">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Unit:</label>
            <input type="text" name="unit" value="{{ old('unit', $product->unit) }}" required>
            @error('unit') <div style="color:red">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Price:</label>
            <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required>
            @error('price') <div style="color:red">{{ $message }}</div> @enderror
        </div>

        <!-- Additional Info Section using Volt Component -->
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
        @error('additional_info') <div style="color:red">{{ $message }}</div> @enderror

        <!-- Current Images -->
        @if($product->images && $product->images->count() > 0)
            <div>
                <label>Current Images:</label>
                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                    @foreach($product->images as $img)
                        <div style="position: relative;">
                            <img src="{{ asset('storage/' . $img->image_path) }}" alt="Product Image" style="width: 60px; height: 60px; object-fit: cover;">
                            <div style="margin-top: 5px;">
                                <label>
                                    <input type="checkbox" name="delete_images[]" value="{{ $img->id }}">
                                    Delete
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- New Images -->
        <div>
            <label>{{ $product->images && $product->images->count() > 0 ? 'Add More Images:' : 'Images:' }}</label>
            <input type="file" name="images[]" multiple 
                   {{ $product->images && $product->images->count() > 0 ? '' : ''/*required case*/ }}>
            <p>You can select multiple images at once.</p>
            @error('images.*') <div style="color:red">{{ $message }}</div> @enderror
        </div>

        <div style="margin-top: 20px;">
            <button type="submit">Update Product</button>
            <a href="{{ route('products.show', $product) }}" style="margin-left: 10px;">Cancel</a>
        </div>
    </form>
</div>
@endsection