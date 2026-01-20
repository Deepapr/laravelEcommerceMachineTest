@extends('layouts.app')

@section('title', 'Create Coupon')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h2>Create New Coupon</h2>

    <form action="{{ route('coupons.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="code">Coupon Code *</label>
            <input type="text" id="code" name="code" value="{{ old('code') }}" required placeholder="e.g., SAVE10">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="type">Discount Type *</label>
            <select id="type" name="type" required>
                <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount ($)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="discount_value">Discount Value *</label>
            <input type="number" id="discount_value" name="discount_value" value="{{ old('discount_value') }}" required step="0.01" min="0.01" placeholder="e.g., 10">
        </div>

        <div class="form-group">
            <label for="minimum_amount">Minimum Purchase Amount</label>
            <input type="number" id="minimum_amount" name="minimum_amount" value="{{ old('minimum_amount') }}" step="0.01" min="0" placeholder="Leave empty for no minimum">
        </div>

        <div class="form-group">
            <label for="usage_limit">Usage Limit</label>
            <input type="number" id="usage_limit" name="usage_limit" value="{{ old('usage_limit') }}" min="1" placeholder="Leave empty for unlimited">
        </div>

        <div class="form-group">
            <label for="valid_from">Valid From</label>
            <input type="date" id="valid_from" name="valid_from" value="{{ old('valid_from') }}">
        </div>

        <div class="form-group">
            <label for="valid_until">Valid Until</label>
            <input type="date" id="valid_until" name="valid_until" value="{{ old('valid_until') }}">
        </div>

        <div class="checkbox-group">
            <input type="checkbox" id="active" name="active" {{ old('active', true) ? 'checked' : '' }}>
            <label for="active" style="display: inline; margin: 0;">Active</label>
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" class="btn btn-success">Create Coupon</button>
            <a href="{{ route('coupons.index') }}" class="btn" style="background-color: #95a5a6;">Cancel</a>
        </div>
    </form>
</div>
@endsection
