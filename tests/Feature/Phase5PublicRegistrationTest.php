<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase5PublicRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function public_registration_creates_jobseeker_account_by_default(): void
    {
        $response = $this->post('/register', [
            'name' => 'Public Jobseeker',
            'email' => 'jobseeker@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'email' => 'jobseeker@example.com',
            'role' => 'jobseeker',
        ]);
    }

    /** @test */
    public function public_registration_does_not_allow_role_escalation_via_role_input(): void
    {
        foreach (['company', 'student', 'alumni', 'admin', 'teacher', 'invalid'] as $role) {
            $response = $this->post('/register', [
                'name' => 'Tampered User',
                'email' => 'blocked-' . $role . '@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => $role,
            ]);

            $response->assertRedirect();
            $this->assertDatabaseHas('users', [
                'email' => 'blocked-' . $role . '@example.com',
                'role' => 'jobseeker',
            ]);
            auth()->logout();
        }
    }
}
