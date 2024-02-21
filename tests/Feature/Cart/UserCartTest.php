<?php

namespace Cart;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCartTest extends TestCase
{
    use RefreshDatabase;
    private User $user;
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_product_is_added_to_user_cart(): void
    {
        $product = Product::factory()->create();

        $this->actingAs($this->user)->post('/cart/store/' . $product->id);

        $this->assertDatabaseHas('cart_items', [
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
    }

    public function test_product_quantity_is_increased_when_product_is_added_to_cart_twice(): void
    {
        $product = Product::factory()->create();

        $this->actingAs($this->user)->post('/cart/store/' . $product->id);
        $this->actingAs($this->user)->post('/cart/store/' . $product->id);

        $this->assertDatabaseHas('cart_items', [
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_product_quantity_is_updated_when_product_is_updated_in_cart(): void
    {
        $product = Product::factory()->create();

        $this->actingAs($this->user)->post('/cart/store/' . $product->id);
        $this->actingAs($this->user)->patch('/cart/update/' . $product->id, ['quantity' => 3]);

        $this->assertDatabaseHas('cart_items', [
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'quantity' => 3,
        ]);
    }

    public function test_cart_item_is_deleted_when_product_is_deleted_from_user_cart(): void
    {
        $product = Product::factory()->create();

        $this->actingAs($this->user)->post('/cart/store/' . $product->id);
        $this->actingAs($this->user)->delete('/cart/delete/' . $product->id);

        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $this->user->id,
            'product_id' => $product->id,
        ]);
    }
    public function test_cart_item_is_deleted_when_product_is_deleted_from_database(): void
    {
        $product = Product::factory()->create();

        $this->actingAs($this->user)->post('/cart/store/' . $product->id);
        $product->delete();

        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $this->user->id,
            'product_id' => $product->id,
        ]);
    }
    public function test_cart_items_are_deleted_when_user_is_deleted(): void
    {
        $product = Product::factory()->create();

        $this->actingAs($this->user)->post('/cart/store/' . $product->id);
        $this->user->delete();

        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $this->user->id,
        ]);
    }
    public function test_cart_item_is_deleted_when_product_is_unpublished(): void
    {
        $product = Product::factory()->create();

        $this->actingAs($this->user)->post('/cart/store/' . $product->id);
        $product->update(['published' => 0]);

        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $this->user->id,
            'product_id' => $product->id,
        ]);
    }
    public function test_user_is_redirected_to_home_page_after_cart_is_empty(): void
    {
        $product = Product::factory()->create();

        $this->actingAs($this->user)->post('/cart/store/' . $product->id);
        $this->actingAs($this->user)->delete('/cart/delete/' . $product->id);
        $response = $this->actingAs($this->user)->get('/cart/view');

        $response->assertRedirect('/');
    }
    public function test_cookie_cart_items_are_saved_to_database_when_user_logs_in(): void
    {
        $product = Product::factory()->create();

        $response = $this->post('/cart/store/' . $product->id);
        $cartItems = $response->getCookie('cart_items')->getValue();
        $this->withCookies(['cart_items' => $cartItems])->post('/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $this->assertDatabaseHas('cart_items', [
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
    }
}
