@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: start;">
        <div style="flex: 1; max-width: 400px;">
            @if ($product->primaryImage())
                <img src="/storage/{{ $product->primaryImage()->image_path }}" alt="{{ $product->name }}" style="width: 100%; border-radius: 8px; margin-bottom: 1rem;">
            @else
                <div style="width: 100%; height: 300px; background-color: #ecf0f1; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                    <span style="color: #bdc3c7;">No image</span>
                </div>
            @endif

            @if ($product->images->count() > 1)
                <div style="display: flex; gap: 0.5rem; overflow-x: auto;">
                    @foreach ($product->images as $image)
                        <img src="/storage/{{ $image->image_path }}" alt="{{ $product->name }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; cursor: pointer;" title="{{ $image->is_primary ? 'Primary' : '' }}">
                    @endforeach
                </div>
            @endif
        </div>

        <div style="flex: 1; margin-left: 2rem;">
            <h1>{{ $product->name }}</h1>

            @if ($product->category)
                <p style="color: #7f8c8d;">Category: <strong>{{ $product->category->name }}</strong></p>
            @endif

            @if ($product->color)
                <p style="color: #7f8c8d;">Color: <strong>{{ $product->color->name }}</strong></p>
            @endif

            @if ($product->size)
                <p style="color: #7f8c8d;">Size: <strong>{{ $product->size->name }}</strong></p>
            @endif

            <p style="color: #7f8c8d;">Stock: <strong>{{ $product->quantity }}</strong></p>

            <div class="product-price">${{ number_format($product->price, 2) }}</div>

            @if ($product->description)
                <p style="margin-top: 1rem;">{{ $product->description }}</p>
            @endif

            @auth
                @if (Auth::user()->isAdmin())
                    <div style="margin-top: 2rem;">
                        <a href="{{ route('products.edit', $product) }}" class="btn">Edit Product</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete Product</button>
                        </form>
                    </div>
                @else
                    @if ($product->quantity > 0)
                        <form action="{{ route('cart.add') }}" method="POST" style="margin-top: 2rem;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="form-group">
                                <label for="quantity">Quantity:</label>
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->quantity }}" style="width: 100px;">
                            </div>
                            <button type="submit" class="btn btn-success">Add to Cart</button>
                        </form>
                    @else
                        <p style="color: #e74c3c; font-weight: bold; margin-top: 1rem;">Out of Stock</p>
                    @endif
                @endif
            @else
                @if ($product->quantity > 0)
                    <form action="{{ route('cart.add') }}" method="POST" style="margin-top: 2rem;">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="form-group">
                            <label for="quantity">Quantity:</label>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->quantity }}" style="width: 100px;">
                        </div>
                        <button type="submit" class="btn btn-success">Add to Cart</button>
                    </form>
                @else
                    <p style="color: #e74c3c; font-weight: bold; margin-top: 1rem;">Out of Stock</p>
                @endif
            @endauth
        </div>
    </div>
</div>
@endsection
