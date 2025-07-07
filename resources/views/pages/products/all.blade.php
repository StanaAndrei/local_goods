@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">All Products</h1>
        
        @auth
            <a href="{{ route('products.create') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                List a Product
            </a>
        @endauth
    </div>
    
    <!-- Filter Form -->
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
        <h2 class="text-lg font-semibold mb-4">Filter Products</h2>
        
        <form action="{{ route('products.all') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input 
                        type="text" 
                        id="search" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Search in name & description" 
                        class="w-full px-3 py-2 border rounded text-sm"
                    >
                </div>
                
                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select 
                        id="category" 
                        name="category" 
                        class="w-full px-3 py-2 border rounded text-sm"
                        onchange="updateSubcategories()"
                    >
                        <option value="">All Categories</option>
                        @foreach(\App\Enums\Category::cases() as $cat)
                            <option value="{{ $cat->value }}" {{ request('category') == $cat->value ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Subcategory -->
                <div>
                    <label for="subcategory" class="block text-sm font-medium text-gray-700 mb-1">Subcategory</label>
                    <select 
                        id="subcategory" 
                        name="subcategory" 
                        class="w-full px-3 py-2 border rounded text-sm"
                    >
                        <option value="">All Subcategories</option>
                        @foreach($availableSubcategories ?? [] as $subcat)
                            <option value="{{ $subcat->value }}" {{ request('subcategory') == $subcat->value ? 'selected' : '' }}>
                                {{ $subcat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Max Price -->
                <div>
                    <label for="max_price" class="block text-sm font-medium text-gray-700 mb-1">Maximum Price (€)</label>
                    <input 
                        type="number" 
                        id="max_price" 
                        name="max_price" 
                        value="{{ request('max_price') }}"
                        min="0" 
                        step="0.01" 
                        placeholder="Max price" 
                        class="w-full px-3 py-2 border rounded text-sm"
                    >
                </div>
                
                <!-- Date From -->
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                    <input 
                        type="date" 
                        id="date_from" 
                        name="date_from" 
                        value="{{ request('date_from') }}"
                        class="w-full px-3 py-2 border rounded text-sm"
                    >
                </div>
                
                <!-- Date To -->
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                    <input 
                        type="date" 
                        id="date_to" 
                        name="date_to" 
                        value="{{ request('date_to') }}"
                        class="w-full px-3 py-2 border rounded text-sm"
                    >
                </div>
            </div>
            
            <div class="mt-4 flex justify-end space-x-3">
                @if(request()->anyFilled(['search', 'category', 'subcategory', 'max_price', 'date_from', 'date_to']))
                    <a href="{{ route('products.all') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm">
                        Clear Filters
                    </a>
                @endif
                
                <button 
                    type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm"
                >
                    Apply Filters
                </button>
            </div>
        </form>
    </div>
    
    <!-- Results Count -->
    <div class="mb-4 text-gray-600">
        Found {{ $products->total() }} product{{ $products->total() !== 1 ? 's' : '' }}
        @if(request()->anyFilled(['search', 'category', 'subcategory', 'max_price', 'date_from', 'date_to']))
            with applied filters
        @endif
    </div>
    
    <!-- Products Grid -->
    @if($products->isEmpty())
        <div class="bg-gray-50 rounded-lg p-8 text-center">
            <p class="text-gray-600">No products found matching your criteria.</p>
            @if(request()->anyFilled(['search', 'category', 'subcategory', 'max_price', 'date_from', 'date_to']))
                <a href="{{ route('products.all') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Clear Filters
                </a>
            @endif
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden flex flex-col h-full">
                    <!-- Product Image -->
                    <div class="h-48 overflow-hidden">
                        @if($product->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No image</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Product Info -->
                    <div class="p-4 flex-grow flex flex-col">
                        <div class="text-sm text-gray-500 mb-1">
                            @if($product->category instanceof \App\Enums\Category)
                                {{ $product->category->name }} › {{ $product->subcategory->name }}
                            @else
                                {{ \App\Enums\Category::from((int)$product->category)->name }} › 
                                {{ \App\Enums\Subcategory::from((int)$product->subcategory)->name }}
                            @endif
                        </div>
                        
                        <h3 class="text-lg font-semibold mb-2 text-gray-800">{{ $product->name }}</h3>
                        
                        <p class="text-gray-600 mb-3 line-clamp-2 flex-grow">
                            {{ Str::limit($product->description, 100) }}
                        </p>
                        
                        <div class="flex justify-between items-center mt-auto">
                            <span class="text-gray-700">{{ $product->quantity }} {{ $product->unit }}</span>
                            <span class="font-bold text-green-600">{{ number_format($product->price, 2) }} €</span>
                        </div>
                        
                        <div class="text-xs text-gray-500 mt-2">
                            Listed {{ $product->created_at->diffForHumans() }}
                        </div>
                    </div>
                    
                    <!-- Action Button -->
                    <a href="{{ route('products.show', $product) }}" 
                       class="block w-full py-2 bg-blue-600 text-white text-center hover:bg-blue-700 transition">
                        View Details
                    </a>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $products->withQueryString()->links() }}
        </div>
    @endif
    
    <!-- JavaScript for dynamic subcategories -->
    <script>
        // Category-subcategory mapping
        const categorySubcategories = {
            @foreach(\App\Enums\Category::cases() as $category)
                {{ $category->value }}: [
                    @foreach(\App\Helpers\CategoryHelper::subcategoriesForCategory($category) as $subcategory)
                        { value: {{ $subcategory->value }}, name: "{{ $subcategory->name }}" },
                    @endforeach
                ],
            @endforeach
        };
        
        function updateSubcategories() {
            const categorySelect = document.getElementById('category');
            const subcategorySelect = document.getElementById('subcategory');
            
            // Clear existing options
            subcategorySelect.innerHTML = '<option value="">All Subcategories</option>';
            
            const categoryValue = categorySelect.value;
            
            if (categoryValue && categorySubcategories[categoryValue]) {
                // Add subcategories for the selected category
                categorySubcategories[categoryValue].forEach(subcategory => {
                    const option = document.createElement('option');
                    option.value = subcategory.value;
                    option.textContent = subcategory.name;
                    
                    // Check if this subcategory was previously selected
                    if (subcategory.value == "{{ request('subcategory') }}") {
                        option.selected = true;
                    }
                    
                    subcategorySelect.appendChild(option);
                });
                
                subcategorySelect.disabled = false;
            } else {
                subcategorySelect.disabled = true;
            }
        }
        
        // Initialize subcategories on page load
        document.addEventListener('DOMContentLoaded', updateSubcategories);
    </script>
</div>
@endsection