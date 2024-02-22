<?php

namespace Tests\Feature\Cart;

use App\Helpers\CookieCartHelper;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CookieCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_is_added_to_cookie_cart(): void
    {
        $product = Product::factory()->create();

        $response = $this->post('/cart/store/' . $product->id);

        $response->assertCookie('cart_items', json_encode([
            [
                'user_id' => null,
                'product_id' => $product->id,
                'quantity' => 1,
            ],
        ]));
    }

    public function test_cookie_cart_item_quantity_is_increased_when_product_is_added_to_cart_twice(): void
    {
        $product = Product::factory()->create();

        $response = $this->post('/cart/store/' . $product->id);

        $cartItems = $response->getCookie('cart_items')->getValue();
        $response = $this->withCookies(['cart_items' => $cartItems])->post('/cart/store/' . $product->id);

        $response->assertCookie('cart_items', json_encode([
            [
                'user_id' => null,
                'product_id' => $product->id,
                'quantity' => 2,
            ],
        ]));
    }

    public function test_cookie_cart_item_quantity_is_updated_when_product_is_updated_in_cart(): void
    {
        $product = Product::factory()->create();

        $response = $this->post('/cart/store/' . $product->id);
        $cartItems = $response->getCookie('cart_items')->getValue();
        $response = $this->withCookies(['cart_items' => $cartItems])->patch('/cart/update/' . $product->id, ['quantity' => 3]);

        $response->assertCookie('cart_items', json_encode([
            [
                'user_id' => null,
                'product_id' => $product->id,
                'quantity' => 3,
            ],
        ]));
    }

    public function test_cookie_cart_item_is_deleted_when_product_is_deleted_from_cart(): void
    {
        $product = Product::factory()->create();

        $response = $this->post('/cart/store/' . $product->id);
        $cartItems = $response->getCookie('cart_items')->getValue();
        $response = $this->withCookies(['cart_items' => $cartItems])->delete('/cart/delete/' . $product->id);

        $response->assertCookie('cart_items', json_encode([]));
    }

    public function test_cookie_cart_item_is_deleted_when_product_is_deleted_from_database(): void
    {
        $product = Product::factory()->create();

        $this->post('/cart/store/' . $product->id);
        $product->delete();
        $response = $this->get('/cart/view');

        $response->assertCookie('cart_items', json_encode([]));
    }

    public function test_cookie_cart_item_is_deleted_when_product_is_unpublished(): void
    {
        $product = Product::factory()->create();

        $this->post('/cart/store/' . $product->id);
        $product->update(['published' => 0]);
        $response = $this->get('/cart/view');

        $response->assertCookie('cart_items', json_encode([]));
    }

    public function test_guest_is_redirected_to_home_page_after_cookie_cart_is_empty()
    {
        $response = $this->get('/cart/view');

        $response->assertRedirect('/');
    }
    public function test_total_price_is_calculated_correctly_on_cart_view()
    {
        $product1 = Product::factory()->create(['price' => 100]);
        $product2 = Product::factory()->create(['price' => 50]);
        $cartItems = json_encode([
            [
                'user_id' => null,
                'product_id' => $product1->id,
                'quantity' => 2,
            ],
            [
                'user_id' => null,
                'product_id' => $product2->id,
                'quantity' => 1,
            ],
        ]);

        $response = $this->withCookies(['cart_items' => $cartItems])->get('/cart/view');

        $response->assertInertia(fn(Assert $assert) => $assert
            ->component('User/CartList')
            ->where('total', 250)
        );
    }
}

