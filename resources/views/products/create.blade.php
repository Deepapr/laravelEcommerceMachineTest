@extends('layouts.app')

@section('title', 'Create Product')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h2>Create New Product</h2>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name">Product Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="category_id">Category</label>
            <select id="category_id" name="category_id">
                <option value="">-- Select Category --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="color_id">Color</label>
            <select id="color_id" name="color_id">
                <option value="">-- Select Color --</option>
                @foreach ($colors as $color)
                    <option value="{{ $color->id }}" {{ old('color_id') == $color->id ? 'selected' : '' }}>
                        {{ $color->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="size_id">Size</label>
            <select id="size_id" name="size_id">
                <option value="">-- Select Size --</option>
                @foreach ($sizes as $size)
                    <option value="{{ $size->id }}" {{ old('size_id') == $size->id ? 'selected' : '' }}>
                        {{ $size->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="quantity">Quantity *</label>
            <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 0) }}" required min="0">
        </div>

        <div class="form-group">
            <label for="price">Price *</label>
            <input type="number" id="price" name="price" value="{{ old('price') }}" required step="0.01" min="0.01">
        </div>

        <div class="form-group">
            <label for="sku">SKU</label>
            <input type="text" id="sku" name="sku" value="{{ old('sku') }}">
        </div>

        <div class="form-group">
            <label for="images">Product Images (JPG/PNG, max 5)</label>
            <input type="file" id="images" name="images[]" multiple accept=".jpg,.jpeg,.png">
            <small>You can upload up to 5 images. They will be converted to WEBP format.</small>
        </div>

        <div class="checkbox-group">
            <input type="checkbox" id="active" name="active" value="1" {{ old('active', 1) ? 'checked' : '' }}>
            <label for="active" style="display: inline; margin: 0;">Active</label>
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" class="btn btn-success">Create Product</button>
            <a href="{{ route('products.index') }}" class="btn" style="background-color: #95a5a6;">Cancel</a>
        </div>
    </form>
</div>
@endsection
