@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 2rem 1rem;">
    <h1 style="font-size: 1.5rem; font-weight: bold; color: #333; margin-bottom: 1.5rem;">My Products</h1>
    
    @if(session('success'))
        <div style="background-color: #e8f5e9; color: #4caf50; padding: 0.75rem 1rem; border-radius: 4px; border: 1px solid #c8e6c9; margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
    @endif

    @if($products->isEmpty())
        <div style="background-color: #f8f9fa; border-radius: 8px; padding: 2rem; text-align: center;">
            <p style="color: #666;">You have not posted any products yet.</p>
            <a href="{{ route('products.create') }}" style="display: inline-block; margin-top: 1rem; background-color: #ff6d00; color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none;">
                List Your First Product
            </a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem;">
            @foreach($products as $product)
                <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; display: flex; flex-direction: column; height: 100%; position: relative;">
                    <!-- Product Image -->
                    <div style="height: 200px; overflow: hidden;">
                        @if($product->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                 alt="{{ $product->name }}" 
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 100%; background-color: #eee; display: flex; align-items: center; justify-content: center;">
                                <span style="color: #999;">No image</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Product Info -->
                    <div style="padding: 1rem; flex-grow: 1; display: flex; flex-direction: column;">
                        <div style="font-size: 0.75rem; color: #666; margin-bottom: 0.25rem;">
                            @if($product->category instanceof \App\Enums\Category)
                                {{ $product->category->name }} › {{ $product->subcategory->name }}
                            @else
                                {{ \App\Enums\Category::from((int)$product->category)->name }} › 
                                {{ \App\Enums\Subcategory::from((int)$product->subcategory)->name }}
                            @endif
                        </div>
                        
                        <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; color: #333;">{{ $product->name }}</h3>
                        
                        <p style="color: #666; margin-bottom: 0.75rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; flex-grow: 1;">
                            {{ Str::limit($product->description, 100) }}
                        </p>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto;">
                            <span style="color: #444;">{{ $product->quantity }} {{ $product->unit }}</span>
                            <span style="font-weight: bold; color: #ff6d00;">{{ number_format($product->price, 2) }} €</span>
                        </div>
                        
                        <div style="font-size: 0.75rem; color: #666; margin-top: 0.5rem;">
                            Listed {{ $product->created_at->diffForHumans() }}
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; border-top: 1px solid #eee;">
                        <a href="{{ route('products.edit', $product) }}" 
                           style="padding: 0.5rem; text-align: center; color: #003d7e; text-decoration: none; border-right: 1px solid #eee; transition: background-color 0.3s;">
                            Edit
                        </a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    style="width: 100%; padding: 0.5rem; background: none; border: none; color: #f44336; cursor: pointer; transition: background-color 0.3s;"
                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection