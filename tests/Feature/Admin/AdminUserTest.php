<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['isAdmin' => 1]);
    }

    public function test_user_management_page_can_be_rendered()
    {
        $response = $this->actingAs($this->admin)->get('/admin/users');
        $response->assertStatus(200);
    }

    public function test_user_is_redirected_to_admin_login_page_if_not_logged_in()
    {
        $response = $this->get('/admin/users');

        $response->assertRedirect('/admin/login');
    }

    public function test_users_are_displayed_on_user_management_page()
    {
        $users = User::factory(2)->create();

        $response = $this->actingAs($this->admin)->get('/admin/users');

        $response->assertInertia(fn(Assert $assert) => $assert
            ->component('Admin/Users')
            ->has('users.data', User::count()));
    }

    public function test_user_can_be_created()
    {
        $user = User::factory()->make()->toArray();
        $user['password'] = 'password';
        $user['password_confirmation'] = 'password';
        $user['isAdmin'] = true;
        $response = $this->actingAs($this->admin)->post('/admin/users/store', $user);
        $response->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', ['email' => $user['email']]);
    }

    public function test_user_can_be_edited()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)->put("/admin/users/update/{$user->id}", [
            'id' => $user->id,
            'name' => 'John Doe',
            'email' => $user->email,
            'isAdmin' => 1
        ]);

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', ['name' => 'John Doe']);
    }

    public function test_user_can_be_deleted()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/admin/users/delete/{$user->id}");

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseMissing('users', ['email' => $user->email]);
    }
    public function test_user_password_can_be_updated()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)->patch("/admin/users/update-password/{$user->id}", [
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword'
        ]);

        $response->assertRedirect('/admin/users');
        $this->assertTrue(Hash::check('newpassword', User::find($user->id)->password));
    }
}
