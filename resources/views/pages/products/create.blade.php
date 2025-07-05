@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Product</h1>
    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Category and Subcategory Selector -->
        @livewire('pages.products.category-subcategory-selector')

        <div>
            <label>Name:</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
            @error('name') <div style="color:red">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Description:</label>
            <textarea name="description" required>{{ old('description') }}</textarea>
            @error('description') <div style="color:red">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Quantity:</label>
            <input type="number" step="0.01" name="quantity" value="{{ old('quantity') }}" required>
            @error('quantity') <div style="color:red">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Unit:</label>
            <input type="text" name="unit" value="{{ old('unit') }}" required>
            @error('unit') <div style="color:red">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Price:</label>
            <input type="number" step="0.01" name="price" value="{{ old('price') }}" required>
            @error('price') <div style="color:red">{{ $message }}</div> @enderror
        </div>

        @livewire('pages.products.additional-info-editor')
        @error('additional_info') <div style="color:red">{{ $message }}</div> @enderror

        <div>
            <label>Images:</label>
            <input type="file" name="images[]" multiple required>
            @error('images.*') <div style="color:red">{{ $message }}</div> @enderror
        </div>

        <button type="submit">Create Product</button>
    </form>
</div>
@endsection