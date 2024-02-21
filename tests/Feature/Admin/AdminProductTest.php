<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;
    private User $admin;
    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->admin = User::factory()->create(['isAdmin' => true]);
    }

    public function test_product_management_page_can_be_rendered()
    {
        $response = $this->actingAs($this->admin)->get('/admin/products');

        $response->assertStatus(200);
    }

    public function test_user_is_redirected_to_admin_login_page_if_not_logged_in()
    {
        $response = $this->get('/admin/products');

        $response->assertRedirect('/admin/login');
    }

    public function test_products_are_displayed_on_product_management_page()
    {
        $products = Product::factory(2)->create();

        $response = $this->actingAs($this->admin)->get('/admin/products');

        $response->assertInertia(fn($assert) => $assert
            ->component('Admin/Product/index')
            ->has('products.data', Product::count()));
    }

    public function test_product_can_be_created()
    {
        $product = Product::factory()->make()->toArray();
        $product['product_images'] = [UploadedFile::fake()->image('product1.jpg')];

        $response = $this->actingAs($this->admin)->post('/admin/products/store', $product);

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', ['title' => $product['title']]);
    }

    public function test_product_can_be_edited()
    {
        $product = Product::factory()->create();
        $updatedProduct = Product::factory()->make(['title' => 'New Product Name'])->toArray();

        $response = $this->actingAs($this->admin)->put("/admin/products/update/{$product->id}", $updatedProduct);

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', ['title' => 'New Product Name']);
    }

    public function test_product_can_be_deleted()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/admin/products/delete/{$product->id}");

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseMissing('products', ['title' => $product->title]);
    }

    public function test_product_name_is_required()
    {
        $product = Product::factory()->make()->toArray();
        $product['title'] = '';

        $response = $this->actingAs($this->admin)->post('/admin/products/store', $product);

        $response->assertSessionHasErrors('title');
    }

    public function test_product_price_is_required()
    {
        $product = Product::factory()->make()->toArray();
        $product['price'] = '';

        $response = $this->actingAs($this->admin)->post('/admin/products/store', $product);

        $response->assertSessionHasErrors('price');
    }

    public function test_product_quantity_is_required()
    {
        $product = Product::factory()->make()->toArray();
        $product['quantity'] = '';

        $response = $this->actingAs($this->admin)->post('/admin/products/store', $product);

        $response->assertSessionHasErrors('quantity');
    }

    public function test_product_description_is_required()
    {
        $product = Product::factory()->make()->toArray();
        $product['description'] = '';

        $response = $this->actingAs($this->admin)->post('/admin/products/store', $product);

        $response->assertSessionHasErrors('description');
    }

    public function test_product_brand_is_required()
    {
        $product = Product::factory()->make()->toArray();
        $product['brand_id'] = '';

        $response = $this->actingAs($this->admin)->post('/admin/products/store', $product);

        $response->assertSessionHasErrors('brand_id');
    }


    public function test_product_category_is_required()
    {
        $product = Product::factory()->make()->toArray();
        $product['category_id'] = '';

        $response = $this->actingAs($this->admin)->post('/admin/products/store', $product);

        $response->assertSessionHasErrors('category_id');
    }

    public function test_product_can_be_published()
    {
        $product = Product::factory()->create(['published' => 0]);

        $response = $this->actingAs($this->admin)->patch("/admin/products/publish/{$product->id}");

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', ['published' => 1]);
    }

    public function test_product_can_be_unpublished()
    {
        $product = Product::factory()->create(['published' => 1]);

        $response = $this->actingAs($this->admin)->patch("/admin/products/publish/{$product->id}");

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', ['published' => 0]);
    }

    public function test_product_image_can_be_added()
    {
        $product = Product::factory()->create();
        $productImage = UploadedFile::fake()->image('product1.jpg');
        $updatedProduct = Product::factory()->make(['product_images' => [$productImage],'title' => 'new title'])->toArray();

        $response = $this->actingAs($this->admin)->put("/admin/products/update/{$product->id}", $updatedProduct);

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('product_images', ['product_id' => $product->id]);
    }
    public function test_product_image_can_be_deleted()
    {
        $product = Product::factory()->create();
        $productImage = $product->product_images()->create(['image' => 'product_images/image.jpg']);

        $response = $this->actingAs($this->admin)->delete("/admin/products/image/delete/{$productImage->id}");

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseMissing('product_images', ['id' => $productImage->id]);
    }


}
