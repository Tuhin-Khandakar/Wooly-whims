<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected $sessionKey = 'cart';
    protected $couponKey = 'coupon';

    public function getCart()
    {
        return Session::get($this->sessionKey, []);
    }

    public function addItem(Product $product, int $quantity = 1)
    {
        $cart = $this->getCart();
        $id = $product->id;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->sale_price ?? $product->price,
                'quantity' => $quantity,
                'image' => $product->thumbnail,
                'slug' => $product->slug
            ];
        }

        Session::put($this->sessionKey, $cart);
    }

    public function updateQuantity(int $id, int $quantity)
    {
        $cart = $this->getCart();
        if (isset($cart[$id])) {
            if ($quantity <= 0) {
                $this->removeItem($id);
            } else {
                $cart[$id]['quantity'] = $quantity;
                Session::put($this->sessionKey, $cart);
            }
        }
    }

    public function removeItem(int $id)
    {
        $cart = $this->getCart();
        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put($this->sessionKey, $cart);
        }
    }

    public function getSubtotal()
    {
        $cart = $this->getCart();
        return array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);
    }

    public function applyCoupon(string $code)
    {
        $coupon = Coupon::where('code', $code)
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->first();

        if (!$coupon) {
            return ['status' => false, 'message' => 'Invalid or expired coupon code.'];
        }

        $subtotal = $this->getSubtotal();
        if ($coupon->min_order && $subtotal < $coupon->min_order) {
            return ['status' => false, 'message' => 'Minimum order amount not met for this coupon.'];
        }

        Session::put($this->couponKey, $coupon);
        return ['status' => true, 'coupon' => $coupon];
    }

    public function getDiscount()
    {
        $coupon = Session::get($this->couponKey);
        if (!$coupon) return 0;

        $subtotal = $this->getSubtotal();
        if ($coupon->type === 'flat') {
            return min($coupon->value, $subtotal);
        } else {
            return $subtotal * ($coupon->value / 100);
        }
    }

    public function removeCoupon()
    {
        Session::forget($this->couponKey);
    }

    public function getTotal(float $delivery = 60)
    {
        return max(0, $this->getSubtotal() - $this->getDiscount() + $delivery);
    }
}
