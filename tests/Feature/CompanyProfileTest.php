<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Profil perusahaan publik — bisa diakses tamu & role umum.
 */
class CompanyProfileTest extends TestCase
{
    use RefreshDatabase;

    private function companyWithJobs(): Company
    {
        $user = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->create([
            'user_id' => $user->id,
            'verification_status' => 'verified',
            'is_verified' => true,
        ]);

        Job::factory()->create([
            'company_id' => $company->id,
            'company_name' => $company->name,
            'status' => 'active',
            'deadline' => now()->addDays(10),
            'title' => 'Lowongan Aktif Terlihat',
        ]);

        Job::factory()->create([
            'company_id' => $company->id,
            'company_name' => $company->name,
            'status' => 'pending',
            'deadline' => now()->addDays(10),
            'title' => 'Lowongan Pending Tersembunyi',
        ]);

        return $company->fresh();
    }

    public function test_guest_can_view_company_profile(): void
    {
        $company = $this->companyWithJobs();

        $this->get(route('companies.show', $company))
            ->assertOk()
            ->assertSee($company->name)
            ->assertSee('Lowongan Aktif Terlihat')
            ->assertDontSee('Lowongan Pending Tersembunyi');
    }

    public function test_umum_user_can_view_company_profile(): void
    {
        $company = $this->companyWithJobs();
        $umum = User::factory()->create(['role' => 'umum']);

        $this->actingAs($umum)
            ->get(route('companies.show', $company))
            ->assertOk()
            ->assertSee($company->name);
    }

    public function test_company_profile_shows_full_contact_and_info(): void
    {
        $user = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->create([
            'user_id' => $user->id,
            'verification_status' => 'verified',
            'is_verified' => true,
            'email' => 'hr@contoh-perusahaan.test',
            'phone' => '081234567890',
        ]);

        $umum = User::factory()->create(['role' => 'umum']);

        $this->actingAs($umum)
            ->get(route('companies.show', $company))
            ->assertOk()
            ->assertSee('hr@contoh-perusahaan.test')
            ->assertSee('081234567890')
            ->assertSee('Kontak Perusahaan')
            ->assertSee('Hubungi Perusahaan')
            ->assertSee('Tentang Perusahaan')
            ->assertSee('Lowongan di Perusahaan Ini')
            // Data internal tidak boleh bocor ke publik
            ->assertDontSee('Total pelamar')
            ->assertDontSee('Mitra sejak');
    }

    public function test_company_profile_sparse_data_has_no_fake_content(): void
    {
        $user = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->create([
            'user_id' => $user->id,
            'verification_status' => 'verified',
            'is_verified' => true,
            'description' => null,
            'website' => null,
            'email' => null,
            'phone' => null,
        ]);

        $this->get(route('companies.show', $company))
            ->assertOk()
            ->assertSee($company->name)
            ->assertSee('Belum ada deskripsi')
            ->assertSee('Belum ada lowongan aktif');
    }

    public function test_company_without_website_or_address_hides_related_sections(): void
    {
        $user = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->create([
            'user_id' => $user->id,
            'verification_status' => 'verified',
            'is_verified' => true,
            'address' => null,
            'website' => null,
            'email' => null,
            'phone' => null,
        ]);

        $this->get(route('companies.show', $company))
            ->assertOk()
            ->assertSee($company->name)
            ->assertDontSee('Alamat perusahaan')
            ->assertDontSee('Kunjungi Website')
            ->assertSee('belum menambahkan info kontak');
    }

    public function test_company_profile_404_for_missing_company(): void
    {
        $this->get(route('companies.show', 999999))->assertNotFound();
    }

    public function test_job_detail_links_to_company_profile(): void
    {
        $company = $this->companyWithJobs();
        $job = $company->jobs()->where('status', 'active')->first();

        $this->get(route('jobs.show', $job))
            ->assertOk()
            ->assertSee(route('companies.show', $company));
    }
}
