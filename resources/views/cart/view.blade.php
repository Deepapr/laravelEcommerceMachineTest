@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="card">
    <h2>Shopping Cart</h2>

    @if ($cart->items->isEmpty())
        <p style="text-align: center; margin: 2rem 0;">Your cart is empty.</p>
        <div style="text-align: center;">
            <a href="{{ route('products.index') }}" class="btn">Continue Shopping</a>
        </div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th style="width: 100px;">Price</th>
                    <th style="width: 100px;">Quantity</th>
                    <th style="width: 100px;">Total</th>
                    <th style="width: 80px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @php $subtotal = 0; @endphp
                @foreach ($cart->items as $item)
                    @php $itemTotal = $item->quantity * $item->price; $subtotal += $itemTotal; @endphp
                    <tr>
                        <td>
                            <div style="display: flex; gap: 1rem; align-items: center;">
                                @if ($item->product->primaryImage())
                                    <img src="{{ asset('storage/' . $item->product->primaryImage()->image_path) }}" alt="{{ $item->product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                @endif
                                <a href="{{ route('products.show', $item->product) }}" style="text-decoration: none; color: inherit;">
                                    {{ $item->product->name }}
                                </a>
                            </div>
                        </td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>
                            <form action="{{ route('cart.update') }}" method="POST" style="display: flex; gap: 0.5rem;">
                                @csrf
                                <input type="hidden" name="items[0][id]" value="{{ $item->id }}">
                                <input type="number" name="items[0][quantity]" value="{{ $item->quantity }}" min="1" style="width: 60px;">
                                <button type="submit" class="btn" style="padding: 0.5rem; font-size: 0.9rem;">Update</button>
                            </form>
                        </td>
                        <td>${{ number_format($itemTotal, 2) }}</td>
                        <td>
                            <form action="{{ route('cart.remove') }}" method="POST">
                                @csrf
                                <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                                <button type="submit" class="btn btn-danger" style="padding: 0.5rem; font-size: 0.9rem;">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #ddd;">
            <div style="text-align: right; margin-bottom: 2rem;">
                <p style="font-size: 1.2rem;">
                    <strong>Subtotal: </strong>
                    <span id="subtotal">${{ number_format($subtotal, 2) }}</span>
                </p>
            </div>

            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('products.index') }}" class="btn" style="background-color: #95a5a6;">Continue Shopping</a>
                <a href="{{ route('checkout.show') }}" class="btn btn-success">Proceed to Checkout</a>
            </div>
        </div>
    @endif
</div>
@endsection
