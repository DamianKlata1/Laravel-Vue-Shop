<?php

namespace HomePage;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class HomePageTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_home_page_load_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_products_load_successfully(): void
    {
        $response = $this->get('/');

        $response->assertInertia(fn (Assert $assert) => $assert
            ->component('User/UserHome')
            ->has('products')
        );
    }
    public function test_home_page_show_only_8_products(): void
    {
        $products = Product::factory()->count(10)->create();

        $response = $this->get('/');

        $response->assertInertia(fn (Assert $assert) => $assert
            ->component('User/UserHome')
            ->has('products.data', 8)
        );
    }


}
