<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_sees_setting_menu()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Setting');
        $response->assertSee(route('users.create'));
    }

    public function test_non_admin_does_not_see_setting_menu()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Setting');
    }

    public function test_guest_does_not_see_setting_menu()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Setting');
    }
}
