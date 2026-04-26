<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_add_product_to_cart()
    {
        $product = Product::factory()->create(['price' => 100]);

        $response = $this->post(route('cart.add', $product), ['quantity' => 2]);

        $response->assertRedirect();
        $this->assertEquals(1, count(session('cart')));
        $this->assertEquals(2, session('cart')[$product->id]['quantity']);
    }

    public function test_can_update_cart_quantity()
    {
        $product = Product::factory()->create();
        session(['cart' => [$product->id => ['id' => $product->id, 'quantity' => 1, 'price' => 100, 'name' => 'P1', 'image' => 'p1.jpg', 'slug' => 'p1']]]);

        $response = $this->post(route('cart.update'), ['id' => $product->id, 'quantity' => 5]);

        $response->assertStatus(200);
        $this->assertEquals(5, session('cart')[$product->id]['quantity']);
    }

    public function test_can_remove_cart_item()
    {
        $product = Product::factory()->create();
        session(['cart' => [$product->id => ['id' => $product->id, 'name' => 'P1']]]);

        $response = $this->post(route('cart.remove', $product->id));

        $response->assertRedirect();
        $this->assertEmpty(session('cart'));
    }

    public function test_coupon_applied_correctly_reduces_total()
    {
        $product = Product::factory()->create(['price' => 100]);
        session(['cart' => [$product->id => ['id' => $product->id, 'quantity' => 1, 'price' => 100, 'name' => 'P1', 'image' => 'p1.jpg', 'slug' => 'p1']]]);
        
        $coupon = Coupon::create([
            'code' => 'TEST10',
            'type' => 'flat',
            'value' => 10,
            'is_active' => true,
        ]);

        $response = $this->post(route('cart.coupon.apply'), ['code' => 'TEST10']);

        $response->assertJson(['status' => 'success']);
        $this->assertEquals(10, session('coupon')->value);
    }

    public function test_expired_coupon_returns_error()
    {
        $coupon = Coupon::create([
            'code' => 'EXPIRED',
            'type' => 'flat',
            'value' => 10,
            'is_active' => true,
            'expires_at' => now()->subDay(),
        ]);

        $response = $this->post(route('cart.coupon.apply'), ['code' => 'EXPIRED']);

        $response->assertJson(['status' => 'error', 'message' => 'Invalid or expired coupon code.']);
    }

    public function test_invalid_coupon_returns_error()
    {
        $response = $this->post(route('cart.coupon.apply'), ['code' => 'INVALID']);

        $response->assertJson(['status' => 'error', 'message' => 'Invalid or expired coupon code.']);
    }
}
