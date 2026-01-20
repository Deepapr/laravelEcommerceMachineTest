@extends('layouts.app')

@section('title', 'Manage Coupons')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Manage Coupons</h2>
        <a href="{{ route('coupons.create') }}" class="btn btn-success">+ Create Coupon</a>
    </div>

    <table class="table" style="margin-top: 2rem;">
        <thead>
            <tr>
                <th>Code</th>
                <th>Type</th>
                <th>Value</th>
                <th>Usage</th>
                <th>Valid Until</th>
                <th>Status</th>
                <th style="width: 100px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($coupons as $coupon)
                <tr>
                    <td><strong>{{ $coupon->code }}</strong></td>
                    <td>{{ ucfirst($coupon->type) }}</td>
                    <td>
                        @if ($coupon->type === 'percentage')
                            {{ $coupon->discount_value }}%
                        @else
                            ${{ number_format($coupon->discount_value, 2) }}
                        @endif
                    </td>
                    <td>{{ $coupon->usage_count }}@if ($coupon->usage_limit) / {{ $coupon->usage_limit }} @endif</td>
                    <td>{{ $coupon->valid_until ? $coupon->valid_until->format('M d, Y') : 'No limit' }}</td>
                    <td>
                        <span style="padding: 0.25rem 0.75rem; background-color: {{ $coupon->active ? '#d4edda' : '#f8d7da' }}; border-radius: 4px;">
                            {{ $coupon->active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('coupons.destroy', $coupon) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.5rem; font-size: 0.85rem;" onclick="return confirm('Delete this coupon?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">No coupons found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($coupons->hasPages())
        <div class="pagination">
            {{ $coupons->links() }}
        </div>
    @endif
</div>
@endsection
