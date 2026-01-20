@extends('layouts.app')

@section('title', 'Import Products')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <h2>Import Products</h2>

    <p style="margin-top: 1rem; color: #7f8c8d;">
        Upload a CSV or Excel file to import multiple products at once. The file should contain the following columns:
    </p>

    <div style="background-color: #ecf0f1; padding: 1rem; border-radius: 4px; margin: 1rem 0;">
        <p><strong>Required Columns:</strong></p>
        <ul style="margin: 0; padding-left: 1.5rem;">
            <li><code>product_name</code> - Name of the product</li>
            <li><code>price</code> - Product price (numeric)</li>
            <li><code>quantity</code> - Stock quantity (numeric)</li>
        </ul>

        <p style="margin-top: 1rem;"><strong>Optional Columns:</strong></p>
        <ul style="margin: 0; padding-left: 1.5rem;">
            <li><code>category</code> - Category name (will be created if doesn't exist)</li>
            <li><code>color</code> - Color name (will be created if doesn't exist)</li>
            <li><code>size</code> - Size name (will be created if doesn't exist)</li>
            <li><code>description</code> - Product description</li>
            <li><code>sku</code> - SKU code</li>
            <li><code>image_url</code> - URL to product image (JPG or PNG, will be converted to WEBP)</li>
        </ul>
    </div>

    <form action="{{ route('import.process') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="file">Select File (CSV or Excel)</label>
            <input type="file" id="file" name="file" accept=".csv,.xlsx,.xls" required>
            <small>Maximum file size: 5MB</small>
        </div>

        <button type="submit" class="btn btn-success">Import</button>
        <a href="{{ route('dashboard') }}" class="btn" style="background-color: #95a5a6;">Cancel</a>
    </form>

    @if (session('import_errors'))
        <div style="margin-top: 2rem;">
            <h3>Import Errors</h3>
            @if (count(session('import_errors')) > 0)
                <ul style="color: #721c24;">
                    @foreach (session('import_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

    <hr style="margin: 2rem 0;">

    <h3>Example CSV Format</h3>
    <div style="background-color: #f8f9fa; padding: 1rem; border-radius: 4px; overflow-x: auto;">
        <pre>product_name,category,color,size,price,quantity,description,sku,image_url
T-Shirt Red,Apparel,Red,M,19.99,100,A comfortable cotton t-shirt,TSH-RED-M,https://example.com/tshirt-red.jpg
Jeans Blue,Apparel,Blue,32,49.99,50,Classic blue denim jeans,JEAN-BLUE-32,https://example.com/jeans-blue.jpg</pre>
    </div>
</div>
@endsection
