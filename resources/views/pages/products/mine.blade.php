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
                @include('pages.products.product-card', ['product' => $product])
            @endforeach
        </div>
    @endif
</div>
@endsection