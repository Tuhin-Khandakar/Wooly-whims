<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_place_order_with_valid_data()
    {
        $product = Product::factory()->create(['price' => 100]);
        session(['cart' => [$product->id => ['id' => $product->id, 'quantity' => 1, 'price' => 100, 'name' => 'P1', 'image' => 'p1.jpg', 'slug' => 'p1']]]);

        $response = $this->post(route('checkout.place'), [
            'customer_name' => 'John Doe',
            'customer_phone' => '01712345678',
            'customer_address' => '123 Street, Madhabdi',
            'area' => 'inside',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', ['customer_name' => 'John Doe']);
        $this->assertEmpty(session('cart'));
    }

    public function test_order_number_is_generated_correctly()
    {
        $product = Product::factory()->create(['price' => 100]);
        session(['cart' => [$product->id => ['id' => $product->id, 'quantity' => 1, 'price' => 100, 'name' => 'P1', 'image' => 'p1.jpg', 'slug' => 'p1']]]);

        $this->post(route('checkout.place'), [
            'customer_name' => 'John Doe',
            'customer_phone' => '01712345678',
            'customer_address' => '123 Street',
            'area' => 'inside',
        ]);

        $order = \App\Models\Order::first();
        $this->assertStringStartsWith('WW-', $order->order_number);
    }

    public function test_order_appears_in_admin_panel()
    {
        $admin = User::factory()->create();
        $order = \App\Models\Order::create([
            'order_number' => 'WW-123',
            'customer_name' => 'Visible Customer',
            'customer_phone' => '123',
            'customer_address' => 'Addr',
            'subtotal' => 100,
            'total' => 100,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($admin)->get(route('admin.orders.index'));

        $response->assertSee('Visible Customer');
        $response->assertSee('WW-123');
    }
}
