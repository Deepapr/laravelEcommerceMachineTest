@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="card">
    <h2>Checkout</h2>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-top: 2rem;">
        <!-- Order Summary -->
        <div>
            <h3>Order Summary</h3>
            <table class="table" style="font-size: 0.95rem;">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th style="width: 80px;">Qty</th>
                        <th style="width: 100px; text-align: right;">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td style="text-align: right;">${{ number_format($item->quantity * $item->price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #ddd;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                    <strong>Subtotal:</strong>
                    <span id="display-subtotal">${{ number_format($subtotal, 2) }}</span>
                </div>

                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;" id="coupon-section" @if ($discount == 0) style="display: none;" @endif>
                    <strong>Discount:</strong>
                    <span id="display-discount" style="color: #27ae60;">-$<span id="discount-amount">{{ number_format($discount, 2) }}</span></span>
                </div>

                <div style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: bold;">
                    <span>Total:</span>
                    <span id="display-total">${{ number_format($total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Checkout Form -->
        <div>
            <h3>Shipping Information</h3>
            <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                @csrf

                <div class="form-group">
                    <label for="customer_email">Email Address *</label>
                    <input type="email" id="customer_email" name="customer_email" value="{{ old('customer_email', auth()->user()?->email) }}" required>
                </div>

                <div class="form-group">
                    <label for="customer_phone">Phone Number *</label>
                    <input type="text" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required>
                </div>

                <div class="form-group">
                    <label for="shipping_address">Shipping Address *</label>
                    <textarea id="shipping_address" name="shipping_address" required>{{ old('shipping_address') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="notes">Order Notes</label>
                    <textarea id="notes" name="notes">{{ old('notes') }}</textarea>
                </div>

                <h3 style="margin-top: 2rem;">Apply Coupon</h3>
                <div style="display: flex; gap: 0.5rem;">
                    <input type="text" id="coupon_code" placeholder="Enter coupon code" style="flex: 1;">
                    <button type="button" id="apply-coupon-btn" class="btn" style="background-color: #f39c12;">Apply</button>
                </div>
                @if ($coupon)
                    <p style="color: #27ae60; margin-top: 1rem;">
                        ✓ Coupon applied: {{ $coupon->code }}
                    </p>
                @endif

                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-success" style="width: 100%; padding: 1rem;">Place Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('apply-coupon-btn').addEventListener('click', function() {
    const couponCode = document.getElementById('coupon_code').value.trim();
    
    if (!couponCode) {
        alert('Please enter a coupon code');
        return;
    }

    fetch('{{ route("checkout.validate-coupon") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            coupon_code: couponCode
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Coupon applied successfully!');
            const newTotal = {{ $subtotal }} - data.discount;
            document.getElementById('display-discount').textContent = '-$' + data.discount.toFixed(2);
            document.getElementById('discount-amount').textContent = data.discount.toFixed(2);
            document.getElementById('display-total').textContent = '$' + newTotal.toFixed(2);
            document.getElementById('coupon-section').style.display = 'flex';
            document.getElementById('coupon_code').disabled = true;
            document.getElementById('apply-coupon-btn').disabled = true;
        } else {
            alert(data.message || 'Failed to apply coupon');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while applying the coupon');
    });
});
</script>
@endsection
