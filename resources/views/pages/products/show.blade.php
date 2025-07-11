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
            <div style="margin-top: 1.5rem;">
                @if(auth()->id() === $product->seller_id)
                    <div style="display: flex; gap: 0.75rem;">
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
                    </div>
                @else
                    <!-- Contact Seller Button -->
                    <a href="{{ route('user.profile', $product->seller->id) }}" 
                       style="display: block; width: 100%; padding: 0.75rem; background-color: #ff6d00; color: white; text-align: center; text-decoration: none; border-radius: 4px; margin-bottom: 1rem;">
                        Contact Seller
                    </a>
                    
                    <!-- Purchase Form -->
                    @auth
                        @if(auth()->user()->isBuyer())
                            <div style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 1.5rem; margin-top: 1rem;">
                                <h3 style="font-size: 1.125rem; font-weight: 600; color: #333; margin-bottom: 1rem;">Purchase Product</h3>
                                
                                @if(session('error'))
                                    <div style="background-color: #f8d7da; color: #721c24; padding: 0.75rem; border-radius: 4px; margin-bottom: 1rem; border: 1px solid #f5c6cb;">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                
                                @if(session('success'))
                                    <div style="background-color: #d4edda; color: #155724; padding: 0.75rem; border-radius: 4px; margin-bottom: 1rem; border: 1px solid #c3e6cb;">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                
                                <form action="{{ route('acquisitions.store') }}" method="POST" id="purchaseForm">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    
                                    <div style="margin-bottom: 1rem;">
                                        <label for="quantity" style="display: block; font-size: 0.875rem; font-weight: 500; color: #333; margin-bottom: 0.5rem;">
                                            Quantity ({{ $product->unit }})
                                        </label>
                                        <input type="number" 
                                               id="quantity" 
                                               name="quantity" 
                                               min="0.01" 
                                               max="{{ $product->quantity }}" 
                                               step="0.01"
                                               value="1"
                                               style="width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px; font-size: 0.875rem;"
                                               oninput="updateTotalCost()"
                                               required>
                                        <small style="color: #666; font-size: 0.75rem;">Available: {{ $product->quantity }} {{ $product->unit }}</small>
                                        @error('quantity')
                                            <div style="color: #dc3545; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div style="margin-bottom: 1rem; padding: 0.75rem; background-color: #e9ecef; border-radius: 4px;">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <span style="font-size: 0.875rem; color: #666;">Unit Price:</span>
                                            <span style="font-size: 0.875rem; font-weight: 500;">{{ number_format($product->price, 2) }} €</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem;">
                                            <span style="font-size: 1rem; font-weight: 600; color: #333;">Total Cost:</span>
                                            <span id="totalCost" style="font-size: 1.125rem; font-weight: bold; color: #ff6d00;">{{ number_format($product->price, 2) }} €</span>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" 
                                            style="width: 100%; padding: 0.75rem; background-color: #28a745; color: white; border: none; border-radius: 4px; font-size: 1rem; font-weight: 500; cursor: pointer; transition: background-color 0.2s;"
                                            onmouseover="this.style.backgroundColor='#218838'"
                                            onmouseout="this.style.backgroundColor='#28a745'"
                                            onclick="return confirm('Are you sure you want to purchase this product?')">
                                        Buy Now
                                    </button>
                                </form>
                            </div>
                        @else
                            <div style="background-color: #fff3cd; color: #856404; padding: 0.75rem; border-radius: 4px; margin-top: 1rem; border: 1px solid #ffeaa7;">
                                Only buyers can purchase products. Please contact the seller directly.
                            </div>
                        @endif
                    @else
                        <div style="background-color: #d1ecf1; color: #0c5460; padding: 0.75rem; border-radius: 4px; margin-top: 1rem; border: 1px solid #bee5eb;">
                            Please <a href="{{ route('login') }}" style="color: #0c5460; text-decoration: underline;">login</a> to purchase this product.
                        </div>
                    @endauth
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function updateTotalCost() {
    const quantity = document.getElementById('quantity').value;
    const unitPrice = {{ $product->price }};
    const totalCost = (quantity * unitPrice).toFixed(2);
    document.getElementById('totalCost').textContent = totalCost + ' €';
}
</script>
@endsection