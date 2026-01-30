<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class NipLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_using_nip()
    {
        $user = User::factory()->create([
            'nip' => '999',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->post('/login', [
            'identifier' => '999',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_can_login_using_nip()
    {
        $admin = User::factory()->create([
            'nip' => 'admin-1',
            'role' => 'admin',
            'password' => Hash::make('adminpass'),
        ]);

        $response = $this->post('/login', [
            'identifier' => 'admin-1',
            'password' => 'adminpass',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($admin);
    }
}
