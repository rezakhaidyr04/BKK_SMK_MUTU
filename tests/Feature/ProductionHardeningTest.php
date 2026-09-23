<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * P6: regression tests for focused production hardening.
 * Additive only — does not modify any existing test.
 *
 * Locks: company-role user without a company row (reachable when admin
 * changes umum → company via admin.users.update, which creates no Company
 * record) gets 404 on company profile edit instead of 500 in blade.
 * Mirrors the established ApplicantController@index precedent
 * ("Profil perusahaan tidak ditemukan.").
 */
class ProductionHardeningTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        // Existing behavior: UserController@update syncRoles() ke Spatie role;
        // role harus ada (production: CompanyRolePermissionSeeder).
        Role::firstOrCreate(['name' => 'company']);
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
        $admin->assignRole(Role::firstOrCreate(['name' => 'admin']));

        return $admin;
    }

    public function test_company_profile_edit_returns_404_when_company_row_missing(): void
    {
        $rowless = User::factory()->create(['role' => 'company', 'email_verified_at' => now()]);
        $this->assertNull($rowless->company);

        $this->actingAs($rowless)->get(route('company.profile.edit'))->assertNotFound();
    }

    public function test_role_change_to_company_without_row_leads_to_404_profile(): void
    {
        $admin = $this->admin();
        $user = User::factory()->create(['role' => 'umum', 'email_verified_at' => now()]);

        // Valid admin flow: role change creates NO Company record (existing behavior).
        $this->actingAs($admin)->put(route('admin.users.update', $user), [
            'name' => $user->name,
            'email' => $user->email,
            'role' => 'company',
        ])->assertRedirect(route('admin.users.index'));

        $this->assertSame('company', $user->fresh()->role);
        $this->assertNull($user->fresh()->company);

        $this->actingAs($user->fresh())->get(route('company.profile.edit'))->assertNotFound();
    }
}
