<?php

namespace Tests\Feature;

use App\Models\Architecture;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_view_login_page(): void
    {
        $this->get('/login')->assertOk();
    }

    /** @test */
    public function user_can_register(): void
    {
        $this->post('/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect('/dashboard');

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    /** @test */
    public function user_can_login(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);

        $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password123',
        ])->assertRedirect('/dashboard');
    }

    /** @test */
    public function invalid_credentials_return_error(): void
    {
        $this->post('/login', [
            'email'    => 'wrong@example.com',
            'password' => 'wrongpassword',
        ])->assertSessionHasErrors('email');
    }
}

class ArchitectureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_view_architectures_index(): void
    {
        Architecture::factory()->count(3)->create(['is_published' => true]);
        $this->get('/architectures')->assertOk();
    }

    /** @test */
    public function guest_can_view_architecture_detail(): void
    {
        $arch = Architecture::factory()->create(['is_published' => true]);
        $this->get("/architectures/{$arch->slug}")->assertOk();
    }

    /** @test */
    public function unpublished_architecture_returns_404(): void
    {
        $arch = Architecture::factory()->create(['is_published' => false]);
        $this->get("/architectures/{$arch->slug}")->assertNotFound();
    }
}

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_is_redirected_from_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_view_dashboard(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/dashboard')->assertOk();
    }
}

class AdminTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function regular_user_cannot_access_admin(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    /** @test */
    public function admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get('/admin')->assertOk();
    }

    /** @test */
    public function admin_can_create_architecture(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post('/admin/architectures', [
            'name'              => 'Test Net',
            'short_description' => 'A test architecture',
            'description'       => 'Long description here',
            'difficulty'        => 'beginner',
            'is_published'      => true,
        ])->assertRedirect('/admin/architectures');

        $this->assertDatabaseHas('architectures', ['name' => 'Test Net']);
    }
}
