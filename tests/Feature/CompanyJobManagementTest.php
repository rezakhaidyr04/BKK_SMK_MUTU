<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * P1 H-09/H-11/H-12: verification consistency, ownership, management, history.
 *
 * Workflow dipertahankan: verified -> create -> pending -> admin approval -> active.
 */
class CompanyJobManagementTest extends TestCase
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

    private function otherVerifiedCompanyUser(): User
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
            'title' => 'Backend Developer',
            'position' => 'Developer',
            'location' => 'Jakarta',
            'job_type' => 'full_time',
            'salary_min' => 5000000,
            'salary_max' => 9000000,
            'description' => 'Deskripsi pekerjaan.',
            'qualifications' => 'Minimal pengalaman 2 tahun.',
            'benefits' => 'Asuransi kesehatan',
            'deadline' => now()->addWeeks(2)->format('Y-m-d'),
        ], $overrides);
    }

    // ── H-09: verification consistency ──────────────────────────

    public function test_verified_company_create_job_goes_pending_not_active(): void
    {
        $user = $this->verifiedCompanyUser();

        $response = $this->actingAs($user)->post(
            route('company.jobs.store'),
            $this->jobPayload(['company_name' => $user->company->name])
        );

        $response->assertRedirect(route('company.jobs.index'));
        $this->assertDatabaseHas('jobs', [
            'title' => 'Backend Developer',
            'company_id' => $user->company->id,
            'status' => 'pending',
        ]);
    }

    public function test_unverified_company_create_job_rejected(): void
    {
        $user = User::factory()->create(['role' => 'company']);
        Company::factory()->create([
            'user_id' => $user->id,
            'is_verified' => false,
            'verification_status' => 'pending',
        ]);

        $response = $this->actingAs($user)->post(
            route('company.jobs.store'),
            $this->jobPayload()
        );

        $response->assertRedirect(route('company.jobs.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('jobs', ['title' => 'Backend Developer']);
    }

    public function test_verification_status_is_canonical(): void
    {
        $user = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->create([
            'user_id' => $user->id,
            'is_verified' => false,
            'verification_status' => 'pending',
        ]);

        // Mengubah status ke verified otomatis sync is_verified via saving hook.
        $company->verification_status = 'verified';
        $company->save();

        $this->assertTrue($company->fresh()->is_verified);
        $this->assertTrue($company->fresh()->isApproved());
    }

    // ── H-09B: identity tidak bisa di-spoof ─────────────────────

    public function test_company_identity_cannot_be_spoofed(): void
    {
        $user = $this->verifiedCompanyUser();
        $victim = $this->otherVerifiedCompanyUser();

        $this->actingAs($user)->post(route('company.jobs.store'), $this->jobPayload([
            'company_id' => $victim->company->id,
            'company_name' => 'PT Palsu Milik Orang Lain',
        ]));

        $job = Job::where('title', 'Backend Developer')->firstOrFail();
        $this->assertSame($user->company->id, $job->company_id);
        $this->assertSame($user->company->name, $job->company_name);
    }

    public function test_verification_status_manipulation_ignored(): void
    {
        $user = User::factory()->create(['role' => 'company']);
        Company::factory()->create([
            'user_id' => $user->id,
            'is_verified' => false,
            'verification_status' => 'pending',
        ]);

        // Request tidak punya field status/verification; kirim paksa tetap ditolak.
        $response = $this->actingAs($user)->post(route('company.jobs.store'), $this->jobPayload([
            'status' => 'active',
            'is_verified' => true,
            'verification_status' => 'verified',
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('jobs', ['title' => 'Backend Developer']);
    }

    // ── H-11: edit/update/close ─────────────────────────────────

    public function test_company_can_edit_own_job(): void
    {
        $user = $this->verifiedCompanyUser();
        $job = Job::factory()->create([
            'company_id' => $user->company->id,
            'company_name' => $user->company->name,
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->get(route('company.jobs.edit', $job))
            ->assertOk();
    }

    public function test_company_can_update_own_job_without_status_change(): void
    {
        $user = $this->verifiedCompanyUser();
        $job = Job::factory()->create([
            'company_id' => $user->company->id,
            'company_name' => $user->company->name,
            'status' => 'pending',
            'title' => 'Judul Lama',
        ]);

        $response = $this->actingAs($user)->put(
            route('company.jobs.update', $job),
            $this->jobPayload(['title' => 'Judul Baru', 'status' => 'active'])
        );

        $response->assertRedirect(route('company.jobs.index'));
        $fresh = $job->fresh();
        $this->assertSame('Judul Baru', $fresh->title);
        // Status tidak boleh berubah via update (tetap pending, tidak bypass approval).
        $this->assertSame('pending', $fresh->status);
        $this->assertSame($user->company->id, $fresh->company_id);
    }

    public function test_company_can_close_own_active_job(): void
    {
        $user = $this->verifiedCompanyUser();
        $job = Job::factory()->create([
            'company_id' => $user->company->id,
            'company_name' => $user->company->name,
            'status' => 'active',
            'deadline' => now()->addWeek(),
        ]);

        $response = $this->actingAs($user)->post(route('company.jobs.close', $job));

        $response->assertRedirect(route('company.jobs.index'));
        $this->assertSame('closed', $job->fresh()->status);
    }

    public function test_closed_job_rejects_new_application(): void
    {
        $companyUser = $this->verifiedCompanyUser();
        $job = Job::factory()->create([
            'company_id' => $companyUser->company->id,
            'company_name' => $companyUser->company->name,
            'status' => 'active',
            'deadline' => now()->addWeek(),
        ]);

        $this->actingAs($companyUser)->post(route('company.jobs.close', $job));
        $this->assertSame('closed', $job->fresh()->status);

        $applicant = User::factory()->create(['role' => 'umum']);
        $response = $this->actingAs($applicant)->post(route('jobs.apply', $job->fresh()), [
            'cover_letter' => str_repeat('Saya sangat tertarik dengan posisi ini. ', 5),
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('applications', ['job_id' => $job->id]);
    }

    // ── H-12: delete protection ─────────────────────────────────

    public function test_company_can_delete_job_without_applications(): void
    {
        $user = $this->verifiedCompanyUser();
        $job = Job::factory()->create([
            'company_id' => $user->company->id,
            'company_name' => $user->company->name,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->delete(route('company.jobs.destroy', $job));

        $response->assertRedirect(route('company.jobs.index'));
        $this->assertSoftDeleted('jobs', ['id' => $job->id]);
    }

    public function test_company_cannot_delete_job_with_applications(): void
    {
        $user = $this->verifiedCompanyUser();
        $job = Job::factory()->create([
            'company_id' => $user->company->id,
            'company_name' => $user->company->name,
            'status' => 'active',
        ]);
        $applicant = User::factory()->create(['role' => 'umum']);
        Application::factory()->create(['job_id' => $job->id, 'user_id' => $applicant->id]);

        $response = $this->actingAs($user)->delete(route('company.jobs.destroy', $job));

        $response->assertSessionHas('error');
        // Job tetap ada (tidak terhapus) dan history utuh.
        $this->assertDatabaseHas('jobs', ['id' => $job->id, 'deleted_at' => null]);
        $this->assertDatabaseHas('applications', ['job_id' => $job->id, 'user_id' => $applicant->id]);
    }

    // ── Cross-company IDOR ──────────────────────────────────────

    public function test_cross_company_edit_is_rejected(): void
    {
        $owner = $this->verifiedCompanyUser();
        $attacker = $this->otherVerifiedCompanyUser();
        $job = Job::factory()->create([
            'company_id' => $owner->company->id,
            'company_name' => $owner->company->name,
            'status' => 'pending',
        ]);

        $this->actingAs($attacker)
            ->get(route('company.jobs.edit', $job))
            ->assertForbidden();
    }

    public function test_cross_company_update_is_rejected(): void
    {
        $owner = $this->verifiedCompanyUser();
        $attacker = $this->otherVerifiedCompanyUser();
        $job = Job::factory()->create([
            'company_id' => $owner->company->id,
            'company_name' => $owner->company->name,
            'status' => 'pending',
            'title' => 'Asli',
        ]);

        $this->actingAs($attacker)->put(
            route('company.jobs.update', $job),
            $this->jobPayload(['title' => 'Bajakan'])
        )->assertForbidden();

        $this->assertSame('Asli', $job->fresh()->title);
    }

    public function test_cross_company_close_is_rejected(): void
    {
        $owner = $this->verifiedCompanyUser();
        $attacker = $this->otherVerifiedCompanyUser();
        $job = Job::factory()->create([
            'company_id' => $owner->company->id,
            'company_name' => $owner->company->name,
            'status' => 'active',
        ]);

        $this->actingAs($attacker)
            ->post(route('company.jobs.close', $job))
            ->assertForbidden();

        $this->assertSame('active', $job->fresh()->status);
    }

    public function test_cross_company_delete_is_rejected(): void
    {
        $owner = $this->verifiedCompanyUser();
        $attacker = $this->otherVerifiedCompanyUser();
        $job = Job::factory()->create([
            'company_id' => $owner->company->id,
            'company_name' => $owner->company->name,
            'status' => 'pending',
        ]);

        $this->actingAs($attacker)
            ->delete(route('company.jobs.destroy', $job))
            ->assertForbidden();

        $this->assertDatabaseHas('jobs', ['id' => $job->id, 'deleted_at' => null]);
    }
}
