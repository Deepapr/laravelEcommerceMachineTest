@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h2>Edit Product</h2>

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Product Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="category_id">Category</label>
            <select id="category_id" name="category_id">
                <option value="">-- Select Category --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                    <option value="{{ $color->id }}" {{ old('color_id', $product->color_id) == $color->id ? 'selected' : '' }}>
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
                    <option value="{{ $size->id }}" {{ old('size_id', $product->size_id) == $size->id ? 'selected' : '' }}>
                        {{ $size->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="quantity">Quantity *</label>
            <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}" required min="0">
        </div>

        <div class="form-group">
            <label for="price">Price *</label>
            <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" required step="0.01" min="0.01">
        </div>

        <div class="form-group">
            <label for="sku">SKU</label>
            <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku) }}">
        </div>

        <div class="form-group">
            <h3>Current Images</h3>
            @if ($product->images->isEmpty())
                <p style="color: #7f8c8d;">No images uploaded yet.</p>
            @else
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                    @foreach ($product->images as $image)
                        <div style="position: relative;">
                            <img src="/storage/{{ $image->image_path }}" alt="{{ $product->name }}" style="width: 100%; height: 100px; object-fit: cover; border-radius: 4px;">
                            <label style="display: flex; align-items: center; margin-top: 0.5rem;">
                                <input type="checkbox" name="remove_images[]" value="{{ $image->id }}">
                                <span style="margin-left: 0.5rem;">Remove</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="new_images">Add New Images (JPG/PNG, max 5)</label>
            <input type="file" id="new_images" name="new_images[]" multiple accept=".jpg,.jpeg,.png">
            <small>You can upload up to 5 images. They will be converted to WEBP format.</small>
        </div>

        <div class="checkbox-group">
            <input type="checkbox" id="active" name="active" {{ old('active', $product->active) ? 'checked' : '' }}>
            <label for="active" style="display: inline; margin: 0;">Active</label>
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" class="btn btn-success">Update Product</button>
            <a href="{{ route('products.show', $product) }}" class="btn" style="background-color: #95a5a6;">Cancel</a>
        </div>
    </form>
</div>
@endsection
