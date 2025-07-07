@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 2rem;">
    <h1 style="font-size: 1.5rem; font-weight: 600; color: #333; margin-bottom: 1.5rem;">Create Product</h1>
    
    @if(session('success'))
        <div style="background-color: #e8f5e9; color: #4caf50; padding: 0.75rem 1rem; border-radius: 4px; border: 1px solid #c8e6c9; margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
    @endif
    
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        @csrf

        <!-- Category and Subcategory Selector -->
        @livewire('pages.products.category-subcategory-selector')

        <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.25rem;">Name:</label>
            <input type="text" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.875rem;">
            @error('name') <div style="color: #f44336; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.25rem;">Description:</label>
            <textarea name="description" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.875rem; min-height: 100px;">{{ old('description') }}</textarea>
            @error('description') <div style="color: #f44336; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.25rem;">Quantity:</label>
            <input type="number" step="0.01" name="quantity" value="{{ old('quantity') }}" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.875rem;">
            @error('quantity') <div style="color: #f44336; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.25rem;">Unit:</label>
            <input type="text" name="unit" value="{{ old('unit') }}" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.875rem;">
            @error('unit') <div style="color: #f44336; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.25rem;">Price (€):</label>
            <input type="number" step="0.01" name="price" value="{{ old('price') }}" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.875rem;">
            @error('price') <div style="color: #f44336; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
        </div>

        @livewire('pages.products.additional-info-editor')
        @error('additional_info') <div style="color: #f44336; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror

        <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.25rem;">Images:</label>
            <input type="file" name="images[]" multiple style="width: 100%; padding: 0.5rem 0;">
            @error('images.*') <div style="color: #f44336; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
        </div>

        <button type="submit" style="background-color: #ff6d00; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 4px; font-weight: 500; cursor: pointer; width: 100%;">Create Product</button>
    </form>
</div>
@endsection