<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Helpers\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cartItems = $this->cartService->getCart();
        if (empty($cartItems)) {
            return redirect()->route('shop')->with('error', 'Your bag is empty.');
        }

        $subtotal = $this->cartService->getSubtotal();
        $discount = $this->cartService->getDiscount();
        $deliveryInside = Setting::get('delivery_inside', 80);
        $total = $this->cartService->getTotal($deliveryInside);

        return view('checkout', compact('cartItems', 'subtotal', 'discount', 'total'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_social' => 'required|string|max:255',
            'customer_address' => 'required|string',
            'area' => 'required|in:inside,outside',
        ]);

        $cartItems = $this->cartService->getCart();
        if (empty($cartItems)) {
            return redirect()->route('shop');
        }

        $deliveryInside = Setting::get('delivery_inside', 80);
        $deliveryOutside = Setting::get('delivery_outside', 120);
        $deliveryCharge = $request->area === 'inside' ? $deliveryInside : $deliveryOutside;
        $subtotal = $this->cartService->getSubtotal();
        $discount = $this->cartService->getDiscount();
        $total = $this->cartService->getTotal($deliveryCharge);
        $coupon = Session::get('coupon');

        // Generate Order Number: WW-YYYYMMDD-XXXXX
        $orderNumber = 'WW-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_social' => $request->customer_social,
            'customer_address' => $request->customer_address . " (" . ucfirst($request->area) . ")",
            'subtotal' => $subtotal,
            'discount' => $discount,
            'delivery_charge' => $deliveryCharge,
            'total' => $total,
            'coupon_code' => $coupon->code ?? null,
            'payment_method' => 'cod',
            'status' => 'pending',
            'notes' => $request->notes
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'product_price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity']
            ]);
        }

        if ($coupon) {
            $coupon->increment('used_count');
        }

        // Clear Cart
        Session::forget('cart');
        Session::forget('coupon');

        return redirect()->route('order.success', $order->order_number)->with('success', 'Your order has been placed successfully!');
    }

    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->with('items')->firstOrFail();
        return view('order-success', compact('order'));
    }
}
