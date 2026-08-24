<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_inactive_users_can_not_authenticate(): void
    {
        $user = User::factory()->create(['is_active' => false]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
    }

    public function test_public_registration_always_signs_in_as_jobseeker_even_when_role_is_tampered(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'PT Contoh',
            'email'                 => 'company@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
            'role'                  => 'company',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
        $this->assertEquals('jobseeker', auth()->user()->role);
    }

    public function test_jobseeker_users_are_redirected_to_their_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'jobseeker']);

        $response = $this->actingAs($user)->get(RouteServiceProvider::HOME);

        $response->assertOk();
    }

    public function test_admin_users_are_redirected_to_their_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get(RouteServiceProvider::HOME);

        $response->assertOk();
    }

    public function test_teacher_users_are_redirected_to_their_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'teacher']);

        $response = $this->actingAs($user)->get(RouteServiceProvider::HOME);

        $response->assertOk();
    }

    public function test_company_users_are_redirected_to_their_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'company']);
        \App\Models\Company::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(RouteServiceProvider::HOME);

        $response->assertOk();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
