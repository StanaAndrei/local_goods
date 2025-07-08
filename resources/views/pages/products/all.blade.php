@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 2rem 1rem;">
    <!-- Header with Create Button -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h1 style="font-size: 1.5rem; font-weight: bold; color: #333;">All Products</h1>
        
        @auth
          @if (Auth::user()->role->name == 'SELLER')  
          <a href="{{ route('products.create') }}" style="background-color: #ff6d00; color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; font-weight: 500; transition: background-color 0.3s;">
              List a Product
          </a>
          @endif
        @endauth
    </div>
    
    <!-- Filter Form -->
    <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: #333;">Filter Products</h2>
        
        <form action="{{ route('products.all') }}" method="GET">
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                <!-- Search -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.25rem;">Search</label>
                    <input 
                        type="text" 
                        id="search" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Search in name & description" 
                        style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.875rem;"
                    >
                </div>
                
                <!-- Category -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.25rem;">Category</label>
                    <select 
                        id="category" 
                        name="category" 
                        style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.875rem;"
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
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.25rem;">Subcategory</label>
                    <select 
                        id="subcategory" 
                        name="subcategory" 
                        style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.875rem;"
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
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.25rem;">Maximum Price (€)</label>
                    <input 
                        type="number" 
                        id="max_price" 
                        name="max_price" 
                        value="{{ request('max_price') }}"
                        min="0" 
                        step="0.01" 
                        placeholder="Max price" 
                        style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.875rem;"
                    >
                </div>
                
                <!-- Date From -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.25rem;">From Date</label>
                    <input 
                        type="date" 
                        id="date_from" 
                        name="date_from" 
                        value="{{ request('date_from') }}"
                        style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.875rem;"
                    >
                </div>
                
                <!-- Date To -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.25rem;">To Date</label>
                    <input 
                        type="date" 
                        id="date_to" 
                        name="date_to" 
                        value="{{ request('date_to') }}"
                        style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.875rem;"
                    >
                </div>
            </div>
            
            <div style="margin-top: 1rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
                @if(request()->anyFilled(['search', 'category', 'subcategory', 'max_price', 'date_from', 'date_to']))
                    <a href="{{ route('products.all') }}" style="background-color: #eee; color: #333; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; font-size: 0.875rem; transition: background-color 0.3s;">
                        Clear Filters
                    </a>
                @endif
                
                <button 
                    type="submit" 
                    style="background-color: #003d7e; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; font-size: 0.875rem; cursor: pointer; transition: background-color 0.3s;"
                >
                    Apply Filters
                </button>
            </div>
        </form>
    </div>
    
    <!-- Results Count -->
    <div style="margin-bottom: 1rem; color: #666; font-size: 0.875rem;">
        Found {{ $products->total() }} product{{ $products->total() !== 1 ? 's' : '' }}
        @if(request()->anyFilled(['search', 'category', 'subcategory', 'max_price', 'date_from', 'date_to']))
            with applied filters
        @endif
    </div>
    
    <!-- Products Grid -->
    @if($products->isEmpty())
        <div style="background-color: #f8f9fa; border-radius: 8px; padding: 2rem; text-align: center;">
            <p style="color: #666;">No products found matching your criteria.</p>
            @if(request()->anyFilled(['search', 'category', 'subcategory', 'max_price', 'date_from', 'date_to']))
                <a href="{{ route('products.all') }}" style="display: inline-block; margin-top: 1rem; background-color: #003d7e; color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none;">
                    Clear Filters
                </a>
            @endif
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem;">
            @foreach($products as $product)
                <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; display: flex; flex-direction: column; height: 100%;">
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
                    
                    <!-- Action Button -->
                    <a href="{{ route('products.show', $product) }}" 
                       style="display: block; width: 100%; padding: 0.5rem; background-color: #003d7e; color: white; text-align: center; text-decoration: none; transition: background-color 0.3s;">
                        View Details
                    </a>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div style="margin-top: 2rem; display: flex; justify-content: center; gap: 0.5rem; font-size: 0.875rem; flex-wrap: wrap;">
            @foreach ($products->withQueryString()->links()->elements[0] as $page => $url)
                @if ($page == $products->currentPage())
                    <span style="padding: 0.4rem 0.75rem; border-radius: 4px; border: 1px solid #003d7e; background-color: #003d7e; color: white; min-width: 2.5rem; text-align: center; font-weight: 500; display: inline-block;">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}" style="padding: 0.4rem 0.75rem; border-radius: 4px; border: 1px solid #ddd; color: #003d7e; text-decoration: none; min-width: 2.5rem; text-align: center; font-weight: 500; display: inline-block; transition: background-color 0.3s, color 0.3s;">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next and Previous links --}}
            @if ($products->onFirstPage() === false)
                <a href="{{ $products->previousPageUrl() }}" style="padding: 0.4rem 0.75rem; border-radius: 4px; border: 1px solid #ddd; color: #003d7e; text-decoration: none; min-width: 2.5rem; text-align: center; font-weight: 500; display: inline-block; transition: background-color 0.3s, color 0.3s;">
                    &laquo;
                </a>
            @endif

            @if ($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" style="padding: 0.4rem 0.75rem; border-radius: 4px; border: 1px solid #ddd; color: #003d7e; text-decoration: none; min-width: 2.5rem; text-align: center; font-weight: 500; display: inline-block; transition: background-color 0.3s, color 0.3s;">
                    &raquo;
                </a>
            @endif
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