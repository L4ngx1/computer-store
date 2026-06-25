<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_search_users_by_name(): void
    {
        // Create admin user to bypass is_admin middleware
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create test users
        $user1 = User::create([
            'name' => 'Nguyen Van A',
            'email' => 'nva@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $user2 = User::create([
            'name' => 'Tran Thi B',
            'email' => 'ttb@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        // Search for 'Nguyen'
        $response = $this->actingAs($admin)->get('/admin/users?search=Nguyen');

        $response->assertStatus(200);
        $response->assertSee('Nguyen Van A');
        $response->assertDontSee('Tran Thi B');
    }

    public function test_admin_can_search_users_by_email(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $user1 = User::create([
            'name' => 'Nguyen Van A',
            'email' => 'nva@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $user2 = User::create([
            'name' => 'Tran Thi B',
            'email' => 'ttb@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        // Search for 'ttb@example.com'
        $response = $this->actingAs($admin)->get('/admin/users?search=ttb@example.com');

        $response->assertStatus(200);
        $response->assertSee('Tran Thi B');
        $response->assertDontSee('Nguyen Van A');
    }
}
