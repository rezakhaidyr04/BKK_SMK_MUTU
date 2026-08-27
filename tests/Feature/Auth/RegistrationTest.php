<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Daftar sebagai Pencari Kerja');
        $response->assertSee('Buat akun pencari kerja');
        $response->assertDontSee('Perusahaan');
        $response->assertDontSee('Admin');
        $response->assertDontSee('Teacher');
    }

    public function test_umum_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => 'umum',
        ]);
    }

    public function test_public_registration_ignores_role_tampering_and_always_creates_umum(): void
    {
        foreach (['company', 'admin', 'teacher', 'invalid-role'] as $role) {
            $email = 'tampered-' . str_replace('-', '', $role) . '@example.com';

            $response = $this->post('/register', [
                'name' => 'Tampered User',
                'email' => $email,
                'role' => $role,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $this->assertAuthenticated();
            $response->assertRedirect(RouteServiceProvider::HOME);
            $this->assertDatabaseHas('users', [
                'email' => $email,
                'role' => 'umum',
            ]);

            auth()->logout();
        }
    }

    public function test_user_model_role_helpers_reflect_current_role(): void
    {
        $umum = User::factory()->create(['role' => 'umum']);
        $company = User::factory()->create(['role' => 'company']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertTrue($umum->isUmum());
        $this->assertFalse($umum->isCompany());
        $this->assertFalse($umum->isAdmin());

        $this->assertTrue($company->isCompany());
        $this->assertTrue($admin->isAdmin());
    }
}
