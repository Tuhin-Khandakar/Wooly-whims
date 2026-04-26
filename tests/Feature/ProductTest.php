<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        Category::create(['name' => 'Sample', 'slug' => 'sample', 'is_active' => true]);
    }

    public function test_admin_can_create_product()
    {
        $category = Category::factory()->create();
        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name' => 'New Product',
            'category_id' => $category->id,
            'description' => 'Test description',
            'price' => 100,
            'stock' => 10,
            'is_active' => 1,
            'thumbnail' => \Illuminate\Http\UploadedFile::fake()->create('thumb.jpg', 100),
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['name' => 'New Product']);
    }

    public function test_admin_can_update_product()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['name' => 'Old Name', 'category_id' => $category->id]);

        $response = $this->actingAs($this->admin)->put(route('admin.products.update', $product), [
            'name' => 'Updated Name',
            'category_id' => $category->id,
            'description' => $product->description,
            'price' => 150,
            'stock' => 5,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Updated Name', 'price' => 150]);
    }

    public function test_admin_can_delete_product()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_product_page_loads_with_correct_data()
    {
        $product = Product::factory()->create(['name' => 'Luxury Yarn', 'is_active' => true]);

        $response = $this->get(route('product.show', $product->slug));

        $response->assertStatus(200);
        $response->assertSee('Luxury Yarn');
    }

    public function test_out_of_stock_product_shows_unavailable()
    {
        $product = Product::factory()->create(['stock' => 0, 'is_active' => true]);

        $response = $this->get(route('product.show', $product->slug));

        $response->assertSee('Out of Stock');
    }
}
