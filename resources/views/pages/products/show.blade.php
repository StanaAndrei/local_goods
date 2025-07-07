@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Product Images -->
        <div class="relative h-64 bg-gray-200">
            @if($product->images->count() > 0)
                <div class="flex overflow-x-auto h-full">
                    @foreach($product->images as $img)
                      <img src="{{ asset('storage/' . $img->image_path) }}" alt="Product Image" style="width: 60px; height: 60px; object-fit: cover;">
                    @endforeach
                </div>
            @else
                <div class="flex items-center justify-center h-full">
                    <span class="text-gray-400">No images available</span>
                </div>
            @endif
        </div>
        
        <!-- Product Info -->
        <div class="p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h1>
                    <div class="flex items-center mt-1">
                        <span class="text-sm text-gray-600">
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
                <div class="text-right">
                    <div class="text-2xl font-bold text-green-600">
                        {{ number_format($product->price, 2) }} €
                    </div>
                    <div class="text-sm text-gray-600">
                        {{ $product->quantity }} {{ $product->unit }}
                    </div>
                </div>
            </div>
            
            <!-- Seller Info -->
            <div class="mt-4 pb-4 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                        <span class="text-gray-600 font-bold">{{ substr($product->seller->name, 0, 1) }}</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ $product->seller->name }}</p>
                        <p class="text-xs text-gray-500">Seller</p>
                    </div>
                </div>
            </div>
            
            <!-- Description -->
            <div class="mt-4">
                <h2 class="text-lg font-semibold text-gray-800">Description</h2>
                <div class="mt-2 text-gray-600 space-y-2">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>
            
            <!-- Additional Info -->
            @if($product->additional_info)
                <div class="mt-6">
                    <h2 class="text-lg font-semibold text-gray-800">Additional Information</h2>
                    <div class="mt-2 grid grid-cols-2 gap-4">
                        @php
                            $additionalInfo = is_string($product->additional_info) 
                                ? json_decode($product->additional_info, true) 
                                : $product->additional_info;
                        @endphp
                        
                        @foreach($additionalInfo ?? [] as $key => $value)
                            <div class="col-span-1">
                                <span class="text-sm font-medium text-gray-500">{{ $key }}:</span>
                                <span class="ml-2 text-sm text-gray-900">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Date Info -->
            <div class="mt-6 text-sm text-gray-500">
                <p>Listed on {{ $product->created_at->format('F j, Y') }}</p>
                @if($product->updated_at && $product->updated_at->gt($product->created_at))
                    <p>Last updated {{ $product->updated_at->diffForHumans() }}</p>
                @endif
            </div>
            
            <!-- Action Buttons -->
            <div class="mt-6 flex space-x-3">
                {{-- <a href="{{ route('products.index') }}" 
                   class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                    Back to Products
                </a> --}}
                
                @if(auth()->id() === $product->seller_id)
                    <a href="{{ route('products.edit', $product) }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Edit Product
                    </a>
                    
                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                                onclick="return confirm('Are you sure you want to delete this product?')">
                            Delete
                        </button>
                    </form>
                @else
                    <!-- In the product view page (show.blade.php) -->
                    <a href="{{ route('user.profile', $product->seller->id) }}" 
                      class="w-full block py-2 bg-green-600 text-white text-center rounded hover:bg-green-700 transition">
                        Contact Seller
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection