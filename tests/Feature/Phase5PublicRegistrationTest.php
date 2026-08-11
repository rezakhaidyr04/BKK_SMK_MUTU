<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class Phase5PublicRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function company_role_is_not_allowed_via_public_registration(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Company',
            'email' => 'testcompany@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'company',
            'company_name' => 'PT Test Company',
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertDatabaseMissing('users', ['email' => 'testcompany@example.com']);
    }

    /** @test */
    public function student_role_is_still_allowed_via_public_registration(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Student',
            'email' => 'teststudent@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'student',
        ]);

        $response->assertRedirect(); // redirect to home
        $this->assertDatabaseHas('users', [
            'email' => 'teststudent@example.com',
            'role' => 'student'
        ]);
    }
}
