<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * P0 C-03/C-04: guard status/deadline untuk apply + show publik.
 */
class JobGuardTest extends TestCase
{
    use RefreshDatabase;

    private function umum(): User
    {
        // Factory default verified (dibutuhkan middleware verified pada apply).
        return User::factory()->create(['role' => 'umum']);
    }

    private function coverLetter(): string
    {
        return str_repeat('Saya sangat tertarik dengan posisi ini dan memenuhi kualifikasi. ', 3);
    }

    public function test_cannot_apply_to_draft_job(): void
    {
        $user = $this->umum();
        $job = Job::factory()->create(['status' => 'draft', 'deadline' => now()->addWeek()]);

        $response = $this->actingAs($user)->post(route('jobs.apply', $job), [
            'cover_letter' => $this->coverLetter(),
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('applications', [
            'job_id' => $job->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_cannot_apply_to_closed_job(): void
    {
        $user = $this->umum();
        $job = Job::factory()->create(['status' => 'closed', 'deadline' => now()->addWeek()]);

        $response = $this->actingAs($user)->post(route('jobs.apply', $job), [
            'cover_letter' => $this->coverLetter(),
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('applications', [
            'job_id' => $job->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_cannot_apply_to_expired_job(): void
    {
        $user = $this->umum();
        $job = Job::factory()->create(['status' => 'active', 'deadline' => now()->subDay()]);

        $response = $this->actingAs($user)->post(route('jobs.apply', $job), [
            'cover_letter' => $this->coverLetter(),
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('applications', [
            'job_id' => $job->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_guest_cannot_view_draft_job(): void
    {
        $job = Job::factory()->create(['status' => 'draft', 'deadline' => now()->addWeek()]);

        $this->get(route('jobs.show', $job))->assertNotFound();
    }

    public function test_guest_cannot_view_closed_job(): void
    {
        $job = Job::factory()->create(['status' => 'closed', 'deadline' => now()->addWeek()]);

        $this->get(route('jobs.show', $job))->assertNotFound();
    }

    public function test_guest_cannot_view_expired_job(): void
    {
        $job = Job::factory()->create(['status' => 'active', 'deadline' => now()->subDay()]);

        $this->get(route('jobs.show', $job))->assertNotFound();
    }

    public function test_guest_can_view_active_job(): void
    {
        $job = Job::factory()->create(['status' => 'active', 'deadline' => now()->addWeek()]);

        $this->get(route('jobs.show', $job))->assertOk();
    }

    public function test_api_jobs_do_not_expose_sensitive_company_fields(): void
    {
        $job = Job::factory()->create(['status' => 'active', 'deadline' => now()->addWeek()]);

        $response = $this->getJson('/api/jobs');
        $response->assertOk();

        $company = $response->json('data.0.company');
        $this->assertNotEmpty($company);

        foreach ([
            'tax_number', 'npwp_path', 'business_license_path',
            'operating_license_path', 'mou_path', 'mou_number',
            'mou_signed_at', 'mou_expires_at', 'is_verified',
            'verification_status', 'rejection_reason', 'reviewed_by', 'reviewed_at',
        ] as $sensitive) {
            $this->assertArrayNotHasKey($sensitive, $company, "Field sensitif {$sensitive} bocor di /api/jobs");
        }

        // Field publik tetap ada.
        foreach (['id', 'name', 'industry', 'logo', 'website', 'description'] as $public) {
            $this->assertArrayHasKey($public, $company);
        }

        $detail = $this->getJson('/api/jobs/'.$job->id);
        $detail->assertOk();
        $detailCompany = $detail->json('data.company');
        foreach (['tax_number', 'mou_path', 'verification_status', 'rejection_reason'] as $sensitive) {
            $this->assertArrayNotHasKey($sensitive, $detailCompany);
        }
    }
}
