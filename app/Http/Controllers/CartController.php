<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Get or create cart for user/session
     */
    protected function getCart()
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        }

        $sessionId = session()->getId();
        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        // Prevent admins from shopping
        if (Auth::user()->isAdmin()) {
            return back()->with('error', 'Admins cannot add products to cart');
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $cart = $this->getCart();

        // Check if item already in cart
        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $cartItem->quantity + $validated['quantity']
            ]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'price' => $product->price
            ]);
        }

        return back()->with('success', 'Product added to cart');
    }

    /**
     * View cart
     */
    public function view()
    {
        $cart = $this->getCart();
        $cart->load('items.product.images');
        
        return view('cart.view', compact('cart'));
    }

    /**
     * Update cart items
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:cart_items,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        foreach ($validated['items'] as $item) {
            CartItem::find($item['id'])->update([
                'quantity' => $item['quantity']
            ]);
        }

        return back()->with('success', 'Cart updated');
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request)
    {
        $validated = $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id'
        ]);

        CartItem::findOrFail($validated['cart_item_id'])->delete();

        return back()->with('success', 'Item removed from cart');
    }
}
