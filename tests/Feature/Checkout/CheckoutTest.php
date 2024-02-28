<?php

namespace Tests\Feature\Checkout;

use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Models\UserAddress;
use Database\Factories\UserAddressFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_checkout_without_new_address(): void
    {
        $product = Product::factory()->create(['price' => 100]);
        $cartItem = CartItem::factory()->create([
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
        $userAddress = UserAddress::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $cartItemsToPass = CartItem::where('user_id', $this->user->id)->with('product')->get();


        $response = $this->actingAs($this->user)->post('/checkout/order', [
            'cartItems' => $cartItemsToPass->toArray(),
            'total' => 100,
        ]);

        $this->assertDatabaseHas('orders', [
            'user_address_id' => $this->user->user_addresses()->where('isMain', 1)->first()->id,
        ]);
    }
    public function test_user_can_checkout_with_new_address(): void
    {
        $product = Product::factory()->create(['price' => 100]);
        $cartItem = CartItem::factory()->create([
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
        $userAddress = UserAddress::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $cartItemsToPass = CartItem::where('user_id', $this->user->id)->with('product')->get();

        $response = $this->actingAs($this->user)->post('/checkout/order', [
            'cartItems' => $cartItemsToPass->toArray(),
            'total' => 100,
            'address' => [
                'address' => 'new address',
                'state' => 'new state',
                'zipcode' => 'new zipcode',
                'city' => 'new city',
                'country_code' => 'new',
                'type' => 'new type',
            ],
        ]);

        $this->assertDatabaseHas('user_addresses', [
            'address1' => 'new address',
        ]);
    }

}
