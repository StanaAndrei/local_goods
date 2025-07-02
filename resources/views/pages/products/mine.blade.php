@extends('layouts.app')

@section('content')
<div class="container">
    <h1>My Products</h1>
    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    @if($products->isEmpty())
        <p>You have not posted any products yet.</p>
    @else
        <div style="display: flex; flex-wrap: wrap; gap: 20px;">
            @foreach($products as $product)
                <div style="border: 1px solid #ccc; padding: 16px; width: 300px;">
                    <h3>{{ $product->name }}</h3>
                    <p><strong>Category:</strong> {{ $product->category->name }}</p>
                    <p><strong>Subcategory:</strong> {{ $product->subcategory->name }}</p>
                    <p><strong>Quantity:</strong> {{ $product->quantity }} {{ $product->unit }}</p>
                    <p><strong>Price:</strong> {{ $product->price }}</p>
                    <p>{{ $product->description }}</p>
                    @if($product->images->isNotEmpty())
                        <div style="display: flex; gap: 5px;">
                            @foreach($product->images as $img)
                                <img src="{{ asset('storage/' . $img->image_path) }}" alt="Product Image" style="width: 60px; height: 60px; object-fit: cover;">
                            @endforeach
                        </div>
                    @endif
                    <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Are you sure you want to delete this product?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="margin-top: 10px; color: red;">Delete</button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection