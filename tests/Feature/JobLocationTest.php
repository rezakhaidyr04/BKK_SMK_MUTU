<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Lokasi nasional: province → city seluruh Indonesia.
 * Master: config/regions.php (38 provinsi, 514 kab/kota).
 */
class JobLocationTest extends TestCase
{
    use RefreshDatabase;

    private function verifiedCompanyUser(): User
    {
        $user = User::factory()->create(['role' => 'company']);
        Company::factory()->create([
            'user_id' => $user->id,
            'is_verified' => true,
            'verification_status' => 'verified',
        ]);

        return $user->fresh();
    }

    private function jobPayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Operator Produksi',
            'description' => 'Deskripsi pekerjaan.',
            'qualifications' => 'Minimal lulusan SMK.',
            'deadline' => now()->addWeeks(2)->format('Y-m-d'),
        ], $overrides);
    }

    public function test_company_can_create_job_in_karawang(): void
    {
        $user = $this->verifiedCompanyUser();

        $this->actingAs($user)->post(route('company.jobs.store'), $this->jobPayload([
            'province' => 'Jawa Barat',
            'city' => 'Kabupaten Karawang',
        ]))->assertRedirect(route('company.jobs.index'));

        $this->assertDatabaseHas('jobs', [
            'company_id' => $user->company->id,
            'province' => 'Jawa Barat',
            'city' => 'Kabupaten Karawang',
            'location' => 'Kabupaten Karawang, Jawa Barat',
        ]);
    }

    public function test_company_can_create_job_outside_java_barat(): void
    {
        $user = $this->verifiedCompanyUser();

        $this->actingAs($user)->post(route('company.jobs.store'), $this->jobPayload([
            'title' => 'Marketing Staff',
            'province' => 'Jawa Timur',
            'city' => 'Kota Surabaya',
        ]))->assertRedirect(route('company.jobs.index'));

        $this->actingAs($user)->post(route('company.jobs.store'), $this->jobPayload([
            'title' => 'Admin',
            'province' => 'Bali',
            'city' => 'Kota Denpasar',
        ]))->assertRedirect(route('company.jobs.index'));

        $this->assertDatabaseHas('jobs', ['city' => 'Kota Surabaya', 'province' => 'Jawa Timur']);
        $this->assertDatabaseHas('jobs', ['city' => 'Kota Denpasar', 'province' => 'Bali']);
    }

    public function test_mismatched_province_and_city_fails_validation(): void
    {
        $user = $this->verifiedCompanyUser();

        $this->actingAs($user)->post(route('company.jobs.store'), $this->jobPayload([
            'province' => 'Jawa Barat',
            'city' => 'Kota Surabaya',
        ]))->assertSessionHasErrors(['city']);

        $this->assertDatabaseMissing('jobs', ['city' => 'Kota Surabaya']);

        // Provinsi tidak dikenal juga ditolak.
        $this->actingAs($user)->post(route('company.jobs.store'), $this->jobPayload([
            'province' => 'Atlantis',
            'city' => 'Kota Surabaya',
        ]))->assertSessionHasErrors(['province']);
    }

    public function test_listing_can_filter_by_province(): void
    {
        $user = $this->verifiedCompanyUser();
        $jabar = Job::factory()->create([
            'company_id' => $user->company->id,
            'title' => 'Lowongan Karawang Unik',
            'province' => 'Jawa Barat',
            'city' => 'Kabupaten Karawang',
            'location' => 'Kabupaten Karawang, Jawa Barat',
            'status' => 'active',
            'deadline' => now()->addWeek(),
        ]);
        $jatim = Job::factory()->create([
            'company_id' => $user->company->id,
            'title' => 'Lowongan Surabaya Unik',
            'province' => 'Jawa Timur',
            'city' => 'Kota Surabaya',
            'location' => 'Kota Surabaya, Jawa Timur',
            'status' => 'active',
            'deadline' => now()->addWeek(),
        ]);

        $response = $this->get(route('jobs.index', ['province' => 'Jawa Barat']));

        $response->assertOk();
        $response->assertSee('Lowongan Karawang Unik');
        $response->assertDontSee('Lowongan Surabaya Unik');
    }

    public function test_listing_can_filter_by_city(): void
    {
        $user = $this->verifiedCompanyUser();
        Job::factory()->create([
            'company_id' => $user->company->id,
            'title' => 'Lowongan Karawang Kota Unik',
            'province' => 'Jawa Barat',
            'city' => 'Kabupaten Karawang',
            'location' => 'Kabupaten Karawang, Jawa Barat',
            'status' => 'active',
            'deadline' => now()->addWeek(),
        ]);
        Job::factory()->create([
            'company_id' => $user->company->id,
            'title' => 'Lowongan Bekasi Kota Unik',
            'province' => 'Jawa Barat',
            'city' => 'Kota Bekasi',
            'location' => 'Kota Bekasi, Jawa Barat',
            'status' => 'active',
            'deadline' => now()->addWeek(),
        ]);

        $response = $this->get(route('jobs.index', ['city' => 'Kabupaten Karawang']));

        $response->assertOk();
        $response->assertSee('Lowongan Karawang Kota Unik');
        $response->assertDontSee('Lowongan Bekasi Kota Unik');
    }

    public function test_detail_shows_location_from_database(): void
    {
        $user = $this->verifiedCompanyUser();
        $job = Job::factory()->create([
            'company_id' => $user->company->id,
            'province' => 'Jawa Barat',
            'city' => 'Kabupaten Karawang',
            'location' => 'Kabupaten Karawang, Jawa Barat',
            'status' => 'active',
            'deadline' => now()->addWeek(),
        ]);

        $this->get(route('jobs.show', $job))
            ->assertOk()
            ->assertSee('Kabupaten Karawang, Jawa Barat');
    }

    public function test_other_company_cannot_change_job_location(): void
    {
        $owner = $this->verifiedCompanyUser();
        $other = $this->verifiedCompanyUser();
        $job = Job::factory()->create([
            'company_id' => $owner->company->id,
            'province' => 'Jawa Barat',
            'city' => 'Kabupaten Karawang',
            'location' => 'Kabupaten Karawang, Jawa Barat',
            'status' => 'active',
            'deadline' => now()->addWeek(),
        ]);

        $this->actingAs($other)->put(route('company.jobs.update', $job), $this->jobPayload([
            'province' => 'Bali',
            'city' => 'Kota Denpasar',
        ]))->assertForbidden();

        $this->assertDatabaseHas('jobs', [
            'id' => $job->id,
            'province' => 'Jawa Barat',
            'city' => 'Kabupaten Karawang',
        ]);
    }
}
