<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class AdminCanManageUsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_create_user_page()
    {
        putenv('ADMIN_NIP=admin-manage');
        putenv('ADMIN_PASSWORD=secret');
        $this->seed(\Database\Seeders\AdminUserSeeder::class);

        $admin = User::where('nip', env('ADMIN_NIP'))->first();
        $this->actingAs($admin);

        $response = $this->get('/users/create');
        $response->assertStatus(200);
        $response->assertSee('Create User');
    }

    public function test_admin_can_create_user()
    {
        putenv('ADMIN_NIP=admin-manage2');
        putenv('ADMIN_PASSWORD=secret');
        $this->seed(\Database\Seeders\AdminUserSeeder::class);

        $admin = User::where('nip', env('ADMIN_NIP'))->first();
        $this->actingAs($admin);

        $response = $this->post('/users', [
            'name' => 'New User',
            'nip' => '12345',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'user',
        ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', ['nip' => '12345', 'role' => 'user']);
    }

    public function test_non_admin_cannot_access_user_routes()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/users/create');
        $response->assertStatus(403);
    }
}
