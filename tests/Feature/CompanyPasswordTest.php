<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CompanyPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_profile_page_has_password_form(): void
    {
        $u = User::factory()->create(['role' => 'company', 'email_verified_at' => now()]);
        Company::factory()->create(['user_id' => $u->id]);

        $this->actingAs($u)->get(route('company.profile.edit'))
            ->assertOk()
            ->assertSee('Keamanan Kata Sandi', false)
            ->assertSee(route('password.update'), false);
    }

    public function test_company_can_update_password(): void
    {
        $u = User::factory()->create(['role' => 'company', 'email_verified_at' => now()]);
        Company::factory()->create(['user_id' => $u->id]);

        $this->actingAs($u)->put(route('password.update'), [
            'current_password' => 'password',
            'password' => 'password-baru-123',
            'password_confirmation' => 'password-baru-123',
        ])->assertSessionHasNoErrors()->assertRedirect();

        $this->assertTrue(Hash::check('password-baru-123', $u->fresh()->password));
    }
}
