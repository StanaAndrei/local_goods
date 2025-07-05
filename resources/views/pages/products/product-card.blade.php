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
    
    <div style="display: flex; gap: 10px; margin-top: 15px;">
        <a href="{{ route('products.show', $product) }}" style="padding: 5px 10px; background-color: #4a90e2; color: white; text-decoration: none; border-radius: 4px;">
            View Details
        </a>
        
        <a href="{{ route('products.edit', $product) }}" style="padding: 5px 10px; background-color: #f0ad4e; color: white; text-decoration: none; border-radius: 4px;">
            Edit
        </a>
        
        <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Are you sure you want to delete this product?');">
            @csrf
            @method('DELETE')
            <button type="submit" style="padding: 5px 10px; background-color: #d9534f; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Delete
            </button>
        </form>
    </div>
</div>