@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Products</h2>
        @auth
            <a href="{{ route('products.create') }}" class="btn">+ Create Product</a>
        @endauth
    </div>

    @if ($products->isEmpty())
        <p style="text-align: center; margin-top: 2rem;">No products found.</p>
    @else
        <div class="grid">
            @foreach ($products as $product)
                <a href="{{ route('products.show', $product) }}" style="text-decoration: none; color: inherit;">
                    <div class="product-card">
                        @if ($product->primaryImage())
                            <img src="/storage/{{ $product->primaryImage()->image_path }}" alt="{{ $product->name }}" class="product-image">
                        @else
                            <div class="product-image" style="display: flex; align-items: center; justify-content: center; background-color: #ecf0f1;">
                                <span style="color: #bdc3c7;">No image</span>
                            </div>
                        @endif
                        <div class="product-info">
                            <div class="product-name">{{ $product->name }}</div>
                            @if ($product->category)
                                <small style="color: #7f8c8d;">{{ $product->category->name }}</small><br>
                            @endif
                            <div class="product-price">${{ number_format($product->price, 2) }}</div>
                            <small style="color: #7f8c8d;">Stock: {{ $product->quantity }}</small>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="pagination">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
