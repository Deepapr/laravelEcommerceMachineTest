<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use App\Models\ProductImage;
use App\Services\ImageService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of products (backend)
     */
    public function index()
    {
        $products = Product::with('category', 'color', 'size', 'images')->paginate(15);
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();
        return view('products.create', compact('categories', 'colors', 'sizes'));
    }

    /**
     * Store a newly created product in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'color_id' => 'nullable|exists:colors,id',
            'size_id' => 'nullable|exists:sizes,id',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0.01',
            'sku' => 'nullable|string|unique:products',
            'images' => 'array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120'
        ]);

        $validated['active'] = $request->has('active') ? 1 : 0;

        $product = Product::create($validated);

        // Handle image uploads
        if ($request->hasFile('images')) {
            $isPrimary = true;
            foreach ($request->file('images') as $image) {
                $imagePath = $this->imageService->uploadAndConvert($image);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'is_primary' => $isPrimary
                ]);
                $isPrimary = false;
            }
        }

        return redirect()->route('products.show', $product)
            ->with('success', 'Product created successfully');
    }

    /**
     * Display the specified product
     */
    public function show(Product $product)
    {
        $product->load('category', 'color', 'size', 'images');
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();
        $product->load('images');
        return view('products.edit', compact('product', 'categories', 'colors', 'sizes'));
    }

  
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'color_id' => 'nullable|exists:colors,id',
            'size_id' => 'nullable|exists:sizes,id',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0.01',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'new_images' => 'array|max:5',
            'new_images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            'remove_images' => 'array'
        ]);

        $validated['active'] = $request->has('active') ? 1 : 0;

        $product->update($validated);

        // Remove specified images
        if ($request->has('remove_images')) {
            foreach ($request->input('remove_images') as $imageId) {
                $productImage = ProductImage::find($imageId);
                if ($productImage && $productImage->product_id === $product->id) {
                    $this->imageService->deleteImage($productImage->image_path);
                    $productImage->delete();
                }
            }
        }

        // Add new images
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $imagePath = $this->imageService->uploadAndConvert($image);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'is_primary' => false
                ]);
            }
        }

        return redirect()->route('products.show', $product)
            ->with('success', 'Product updated successfully');
    }

  
    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            $this->imageService->deleteImage($image->image_path);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully');
    }
}
