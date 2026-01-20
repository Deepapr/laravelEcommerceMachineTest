@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div style="text-align: center; padding: 4rem 2rem;">
    <h1 style="font-size: 3rem; margin-bottom: 1rem;">Welcome to E-Commerce Store</h1>
    <p style="font-size: 1.2rem; color: #7f8c8d; margin-bottom: 2rem;">
        Browse our amazing collection of products
    </p>
    <a href="{{ route('products.index') }}" class="btn" style="padding: 1rem 2rem; font-size: 1.1rem;">Shop Now</a>
</div>

<div class="card">
    <h2>Featured Categories</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; margin-top: 2rem;">
        <div style="text-align: center; padding: 2rem; background-color: #ecf0f1; border-radius: 8px;">
            <h3>👔 Apparel</h3>
            <p>Clothing and fashion items</p>
        </div>
        <div style="text-align: center; padding: 2rem; background-color: #ecf0f1; border-radius: 8px;">
            <h3>👟 Shoes</h3>
            <p>Footwear for all occasions</p>
        </div>
        <div style="text-align: center; padding: 2rem; background-color: #ecf0f1; border-radius: 8px;">
            <h3>⌚ Accessories</h3>
            <p>Complete your style</p>
        </div>
    </div>
</div>
@endsection
