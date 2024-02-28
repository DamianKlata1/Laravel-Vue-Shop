<?php

namespace Tests\Feature\Admin;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminBrandTest extends TestCase
{
    use RefreshDatabase;
    private User $admin;
    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->admin = User::factory()->create(['isAdmin' => 1]);
    }

    public function test_brand_management_page_can_be_rendered()
    {
        $response = $this->actingAs($this->admin)->get('/admin/brands');
        $response->assertStatus(200);
    }

    public function test_brand_is_redirected_to_admin_login_page_if_not_logged_in()
    {
        $response = $this->get('/admin/brands');
        $response->assertRedirect('/admin/login');
    }

    public function test_brands_are_displayed_on_brand_management_page()
    {
        $brands = Brand::factory(2)->create();

        $response = $this->actingAs($this->admin)->get('/admin/brands');

        $response->assertInertia(fn(Assert $assert) => $assert
            ->component('Admin/Brands')
            ->has('brands.data', Brand::count()));
    }

    public function test_brand_can_be_created()
    {
        $brand = Brand::factory()->make()->toArray();

        $response = $this->actingAs($this->admin)->post('/admin/brands/store', $brand);

        $response->assertRedirect('/admin/brands');
        $this->assertDatabaseHas('brands', ['name' => $brand['name']]);
    }

    public function test_brand_can_be_edited()
    {
        $brand = Brand::factory()->create();

        $response = $this->actingAs($this->admin)->put("/admin/brands/update/{$brand->id}", [
            'name' => 'New Brand Name'
        ]);

        $response->assertRedirect('/admin/brands');
        $this->assertDatabaseHas('brands', ['name' => 'New Brand Name']);
    }

    public function test_brand_can_be_deleted()
    {
        $brand = Brand::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/admin/brands/delete/{$brand->id}");

        $response->assertRedirect('/admin/brands');
        $this->assertDatabaseMissing('brands', ['name' => $brand->name]);
    }
}