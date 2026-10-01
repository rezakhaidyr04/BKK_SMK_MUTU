<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Certificate;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Halaman Detail Pengguna (admin): kaya info per role, aman (admin only).
 */
class AdminUserShowTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'company', 'guard_name' => 'web']);

        $this->admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $this->admin->assignRole('admin');
    }

    public function test_admin_sees_full_umum_profile(): void
    {
        $user = User::factory()->create([
            'role' => 'umum',
            'phone' => '081234567890',
            'bio' => 'Lulusan SMK pencari kerja.',
        ]);
        $companyUser = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->create(['user_id' => $companyUser->id]);
        $job = Job::factory()->create(['company_id' => $company->id, 'status' => 'active']);
        Application::factory()->create(['job_id' => $job->id, 'user_id' => $user->id, 'status' => 'interviewed']);
        Certificate::factory()->create(['user_id' => $user->id, 'title' => 'Sertifikat Las', 'file_path' => 'certificates/las.pdf']);

        $response = $this->actingAs($this->admin)->get(route('admin.users.show', $user));

        $response->assertOk()
            ->assertSee('Biodata')
            ->assertSee('081234567890')
            ->assertSee('Keahlian')
            ->assertSee('Riwayat Lamaran')
            ->assertSee($job->title)
            ->assertSee('Wawancara')
            ->assertSee('Dokumen')
            ->assertSee('Sertifikat Las')
            ->assertSee('Info Akun');
    }

    public function test_admin_sees_company_panel_with_jobs(): void
    {
        $companyUser = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->create([
            'user_id' => $companyUser->id,
            'verification_status' => 'verified',
            'is_verified' => true,
        ]);
        Job::factory()->create(['company_id' => $company->id, 'title' => 'Operator Produksi', 'status' => 'active']);

        $response = $this->actingAs($this->admin)->get(route('admin.users.show', $companyUser));

        $response->assertOk()
            ->assertSee('Perusahaan')
            ->assertSee($company->name)
            ->assertSee('Operator Produksi')
            ->assertSee('Dokumen');
    }

    public function test_non_admin_cannot_view_user_detail(): void
    {
        $umum = User::factory()->create(['role' => 'umum']);
        $other = User::factory()->create(['role' => 'umum']);

        $this->actingAs($umum)->get(route('admin.users.show', $other))->assertForbidden();
    }

    public function test_empty_sections_render_gracefully(): void
    {
        $user = User::factory()->create(['role' => 'umum']);

        $response = $this->actingAs($this->admin)->get(route('admin.users.show', $user));

        $response->assertOk()
            ->assertSee('Belum ada keahlian tercatat.')
            ->assertSee('Belum pernah melamar lowongan.')
            ->assertSee('Tidak ada dokumen');
    }
}
