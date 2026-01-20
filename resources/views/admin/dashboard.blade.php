@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="card">
    <h2>Admin Dashboard</h2>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; margin: 2rem 0;">
        <div class="card" style="text-align: center;">
            <h3 style="color: #3498db; margin: 0;">{{ $totalProducts }}</h3>
            <p style="margin-top: 0.5rem;">Total Products</p>
        </div>
        <div class="card" style="text-align: center;">
            <h3 style="color: #27ae60; margin: 0;">{{ $totalOrders }}</h3>
            <p style="margin-top: 0.5rem;">Total Orders</p>
        </div>
        <div class="card" style="text-align: center;">
            <h3 style="color: #e74c3c; margin: 0;">${{ number_format($totalRevenue, 2) }}</h3>
            <p style="margin-top: 0.5rem;">Total Revenue</p>
        </div>
    </div>

    <h3 style="margin-top: 3rem;">Admin Tools</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-top: 1rem;">
        <a href="{{ route('products.index') }}" class="btn">Manage Products</a>
        <a href="{{ route('categories.index') }}" class="btn">Manage Categories</a>
        <a href="{{ route('colors.index') }}" class="btn">Manage Colors</a>
        <a href="{{ route('sizes.index') }}" class="btn">Manage Sizes</a>
        <a href="{{ route('coupons.index') }}" class="btn">Manage Coupons</a>
        <a href="{{ route('import.show') }}" class="btn">Import Products</a>
    </div>

    @if ($recentOrders->isNotEmpty())
        <h3 style="margin-top: 3rem;">Recent Orders</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Email</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recentOrders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>{{ $order->customer_email }}</td>
                        <td>${{ number_format($order->total, 2) }}</td>
                        <td>
                            <span style="padding: 0.25rem 0.75rem; background-color: #ecf0f1; border-radius: 4px;">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
