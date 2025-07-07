@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 2rem 1rem;">
    <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
        <!-- Product Images -->
        <div style="position: relative; height: 400px; background-color: #f5f5f5;">
            @if($product->images->count() > 0)
                <div style="display: flex; overflow-x: auto; height: 100%;">
                    @foreach($product->images as $img)
                        <img src="{{ asset('storage/' . $img->image_path) }}" 
                             alt="Product Image" 
                             style="width: 100%; height: 100%; object-fit: contain; flex-shrink: 0;">
                    @endforeach
                </div>
            @else
                <div style="display: flex; align-items: center; justify-content: center; height: 100%;">
                    <span style="color: #999;">No images available</span>
                </div>
            @endif
        </div>
        
        <!-- Product Info -->
        <div style="padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <h1 style="font-size: 1.5rem; font-weight: bold; color: #333;">{{ $product->name }}</h1>
                    <div style="display: flex; align-items: center; margin-top: 0.5rem;">
                        <span style="font-size: 0.875rem; color: #666;">
                            @if($product->category instanceof \App\Enums\Category)
                                {{ $product->category->name }} › 
                                {{ $product->subcategory->name }}
                            @else
                                {{ \App\Enums\Category::from($product->category)->name }} › 
                                {{ \App\Enums\Subcategory::from($product->subcategory)->name }}
                            @endif
                        </span>
                    </div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 1.5rem; font-weight: bold; color: #ff6d00;">
                        {{ number_format($product->price, 2) }} €
                    </div>
                    <div style="font-size: 0.875rem; color: #666;">
                        {{ $product->quantity }} {{ $product->unit }}
                    </div>
                </div>
            </div>
            
            <!-- Seller Info -->
            <div style="margin-top: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #eee;">
                <div style="display: flex; align-items: center;">
                    <div style="width: 40px; height: 40px; background-color: #e0e0e0; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <span style="color: #666; font-weight: bold;">{{ substr($product->seller->name, 0, 1) }}</span>
                    </div>
                    <div style="margin-left: 0.75rem;">
                        <p style="font-size: 0.875rem; font-weight: 500; color: #333;">{{ $product->seller->name }}</p>
                        <p style="font-size: 0.75rem; color: #666;">Seller</p>
                    </div>
                </div>
            </div>
            
            <!-- Description -->
            <div style="margin-top: 1.5rem;">
                <h2 style="font-size: 1.125rem; font-weight: 600; color: #333;">Description</h2>
                <div style="margin-top: 0.5rem; color: #666; line-height: 1.6;">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>
            
            <!-- Additional Info -->
            @if($product->additional_info)
                <div style="margin-top: 1.5rem;">
                    <h2 style="font-size: 1.125rem; font-weight: 600; color: #333;">Additional Information</h2>
                    <div style="margin-top: 0.5rem; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem;">
                        @php
                            $additionalInfo = is_string($product->additional_info) 
                                ? json_decode($product->additional_info, true) 
                                : $product->additional_info;
                        @endphp
                        
                        @foreach($additionalInfo ?? [] as $key => $value)
                            <div>
                                <span style="font-size: 0.875rem; font-weight: 500; color: #666;">{{ $key }}:</span>
                                <span style="margin-left: 0.5rem; font-size: 0.875rem; color: #333;">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Date Info -->
            <div style="margin-top: 1.5rem; font-size: 0.875rem; color: #666;">
                <p>Listed on {{ $product->created_at->format('F j, Y') }}</p>
                @if($product->updated_at && $product->updated_at->gt($product->created_at))
                    <p>Last updated {{ $product->updated_at->diffForHumans() }}</p>
                @endif
            </div>
            
            <!-- Action Buttons -->
            <div style="margin-top: 1.5rem; display: flex; gap: 0.75rem;">
                @if(auth()->id() === $product->seller_id)
                    <a href="{{ route('products.edit', $product) }}" 
                       style="padding: 0.5rem 1rem; background-color: #003d7e; color: white; border-radius: 4px; text-decoration: none;">
                        Edit Product
                    </a>
                    
                    <form action="{{ route('products.destroy', $product) }}" method="POST" style="margin: 0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                style="padding: 0.5rem 1rem; background-color: #f44336; color: white; border: none; border-radius: 4px; cursor: pointer;"
                                onclick="return confirm('Are you sure you want to delete this product?')">
                            Delete
                        </button>
                    </form>
                @else
                    <a href="{{ route('user.profile', $product->seller->id) }}" 
                       style="display: block; width: 100%; padding: 0.75rem; background-color: #ff6d00; color: white; text-align: center; text-decoration: none; border-radius: 4px;">
                        Contact Seller
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection