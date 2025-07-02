@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Product</h1>
    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @csrf

        <div>
            <label>Category:</label>
            <select name="category" required>
                @foreach(\App\Enums\Category::cases() as $cat)
                    <option value="{{ $cat->value }}" {{ old('category') == $cat->value ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            @error('category') <div style="color:red">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Subcategory:</label>
            <select name="subcategory" required>
                @foreach(\App\Enums\Subcategory::cases() as $sub)
                    <option value="{{ $sub->value }}" {{ old('subcategory') == $sub->value ? 'selected' : '' }}>
                        {{ $sub->name }}
                    </option>
                @endforeach
            </select>
            @error('subcategory') <div style="color:red">{{ $message }}</div> @enderror
        </div>

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

        <div>
            <label>Additional Info (JSON):</label>
            <input type="text" name="additional_info" value="{{ old('additional_info') }}">
            @error('additional_info') <div style="color:red">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Images:</label>
            <input type="file" name="images[]" multiple required>
            @error('images.*') <div style="color:red">{{ $message }}</div> @enderror
        </div>

        <button type="submit">Create Product</button>
    </form>
</div>
@endsection