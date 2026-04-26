<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cartItems = $this->cartService->getCart();
        $subtotal = $this->cartService->getSubtotal();
        $discount = $this->cartService->getDiscount();
        $delivery = (float) \App\Helpers\Setting::get('delivery_inside', 60);
        $total = $this->cartService->getTotal($delivery);

        return view('cart', compact('cartItems', 'subtotal', 'discount', 'total', 'delivery'));
    }

    public function add(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);
        $this->cartService->addItem($product, $quantity);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success', 
                'message' => 'Product added to cart!',
                'cart_count' => count($this->cartService->getCart())
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request)
    {
        $this->cartService->updateQuantity($request->id, $request->quantity);
        $delivery = (float) \App\Helpers\Setting::get('delivery_inside', 60);
        return response()->json([
            'status' => 'success',
            'subtotal' => number_format($this->cartService->getSubtotal(), 2),
            'total' => number_format($this->cartService->getTotal($delivery), 2),
            'discount' => number_format($this->cartService->getDiscount(), 2),
        ]);
    }

    public function remove(int $id)
    {
        $this->cartService->removeItem($id);
        return redirect()->back()->with('success', 'Item removed from cart!');
    }

    public function applyCoupon(Request $request)
    {
        $result = $this->cartService->applyCoupon($request->code);
        
        if (!$result['status']) {
            return response()->json(['status' => 'error', 'message' => $result['message']]);
        }

        $delivery = (float) \App\Helpers\Setting::get('delivery_inside', 60);
        return response()->json([
            'status' => 'success', 
            'message' => 'Coupon applied!',
            'discount' => number_format($this->cartService->getDiscount(), 2),
            'total' => number_format($this->cartService->getTotal($delivery), 2)
        ]);
    }
}
