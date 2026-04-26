<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponTest extends TestCase
{
    use RefreshDatabase;

    public function test_percentage_coupon_calculates_correctly()
    {
        $product = Product::factory()->create(['price' => 200]);
        session(['cart' => [$product->id => ['id' => $product->id, 'quantity' => 1, 'price' => 200, 'name' => 'P1', 'image' => 'p1.jpg', 'slug' => 'p1']]]);
        
        $coupon = Coupon::create([
            'code' => 'SAVE10',
            'type' => 'percentage',
            'value' => 10,
            'is_active' => true,
        ]);

        $this->post(route('cart.coupon.apply'), ['code' => 'SAVE10']);

        // 10% of 200 is 20
        $this->assertEquals(20, (new \App\Services\CartService)->getDiscount());
    }

    public function test_flat_coupon_calculates_correctly()
    {
        $product = Product::factory()->create(['price' => 200]);
        session(['cart' => [$product->id => ['id' => $product->id, 'quantity' => 1, 'price' => 200, 'name' => 'P1', 'image' => 'p1.jpg', 'slug' => 'p1']]]);
        
        $coupon = Coupon::create([
            'code' => 'FLAT50',
            'type' => 'flat',
            'value' => 50,
            'is_active' => true,
        ]);

        $this->post(route('cart.coupon.apply'), ['code' => 'FLAT50']);

        $this->assertEquals(50, (new \App\Services\CartService)->getDiscount());
    }

    public function test_minimum_order_validation_works()
    {
        $product = Product::factory()->create(['price' => 50]);
        session(['cart' => [$product->id => ['id' => $product->id, 'quantity' => 1, 'price' => 50, 'name' => 'P1', 'image' => 'p1.jpg', 'slug' => 'p1']]]);
        
        $coupon = Coupon::create([
            'code' => 'MIN100',
            'type' => 'flat',
            'value' => 10,
            'min_order' => 100,
            'is_active' => true,
        ]);

        $response = $this->post(route('cart.coupon.apply'), ['code' => 'MIN100']);

        $response->assertJson(['status' => 'error', 'message' => 'Minimum order amount not met for this coupon.']);
    }
}
