<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Get cart for current user/session
     */
    protected function getCart()
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->first();
        }

        return Cart::where('session_id', session()->getId())->first();
    }

    /**
     * Show checkout page
     */
    public function show(Request $request)
    {
        // Prevent admins from checking out
        if (Auth::user()->isAdmin()) {
            return redirect()->route('products.index')
                ->with('error', 'Admins cannot place orders');
        }

        $cart = $this->getCart();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('products.index')
                ->with('info', 'Your cart is empty');
        }

        $cart->load('items.product');

        $subtotal = $cart->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $discount = 0;
        $coupon = null;

        if ($request->session()->has('coupon_code')) {
            $coupon = Coupon::where('code', $request->session()->get('coupon_code'))->first();
            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }

        $total = $subtotal - $discount;

        return view('checkout.show', compact('cart', 'subtotal', 'discount', 'total', 'coupon'));
    }

    /**
     * Validate coupon
     */
    public function validateCoupon(Request $request)
    {
        $validated = $request->validate([
            'coupon_code' => 'required|string'
        ]);

        $coupon = Coupon::where('code', $validated['coupon_code'])->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon not found'
            ], 404);
        }

        if (!$coupon->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon is not valid or has expired'
            ], 422);
        }

        $cart = $this->getCart();
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found'
            ], 404);
        }

        $subtotal = $cart->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $discount = $coupon->calculateDiscount($subtotal);

        if ($discount == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon is not applicable to your purchase'
            ], 422);
        }

        // Store coupon in session
        $request->session()->put('coupon_code', $coupon->code);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully',
            'discount' => $discount,
            'type' => $coupon->type,
            'discount_value' => $coupon->discount_value
        ]);
    }

    /**
     * Process checkout
     */
    public function process(Request $request)
    {
        // Prevent admins from checking out
        if (Auth::user()->isAdmin()) {
            return redirect()->route('products.index')
                ->with('error', 'Admins cannot place orders');
        }

        $validated = $request->validate([
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string',
            'shipping_address' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $cart = $this->getCart();

        if (!$cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Your cart is empty');
        }

        try {
            DB::beginTransaction();

            $subtotal = $cart->items->sum(function ($item) {
                return $item->quantity * $item->price;
            });

            $discount = 0;
            $couponCode = null;

            if ($request->session()->has('coupon_code')) {
                $coupon = Coupon::where('code', $request->session()->get('coupon_code'))->first();
                if ($coupon && $coupon->isValid()) {
                    $discount = $coupon->calculateDiscount($subtotal);
                    $couponCode = $coupon->code;
                    
                    // Increment coupon usage
                    $coupon->increment('usage_count');
                }
            }

            $total = $subtotal - $discount;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'coupon_code' => $couponCode,
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'notes' => $validated['notes']
            ]);

            // Add items to order
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->quantity * $item->price
                ]);

                // Update product quantity
                $product = $item->product;
                $product->decrement('quantity', $item->quantity);
            }

            // Clear cart
            $cart->items()->delete();

            // Clear coupon from session
            $request->session()->forget('coupon_code');

            DB::commit();

            return redirect()->route('home')
                ->with('success', 'Order placed successfully! Order number: ' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while processing your order. Please try again.');
        }
    }
}
