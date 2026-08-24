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
        $response->assertDontSee('Student');
        $response->assertDontSee('Alumni');
    }

    public function test_jobseekers_can_register(): void
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
            'role' => 'jobseeker',
        ]);
    }

    public function test_public_registration_ignores_role_tampering_and_always_creates_jobseeker(): void
    {
        foreach (['company', 'admin', 'teacher', 'student', 'alumni', 'invalid-role'] as $role) {
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
                'role' => 'jobseeker',
            ]);

            auth()->logout();
        }
    }

    public function test_user_model_role_helpers_reflect_current_role(): void
    {
        $jobseeker = User::factory()->create(['role' => 'jobseeker']);
        $company = User::factory()->create(['role' => 'company']);
        $admin = User::factory()->create(['role' => 'admin']);
        $teacher = User::factory()->create(['role' => 'teacher']);

        $this->assertTrue($jobseeker->isJobseeker());
        $this->assertFalse($jobseeker->isCompany());
        $this->assertFalse($jobseeker->isAdmin());
        $this->assertFalse($jobseeker->isTeacher());

        $this->assertTrue($company->isCompany());
        $this->assertTrue($admin->isAdmin());
        $this->assertTrue($teacher->isTeacher());
    }
}
