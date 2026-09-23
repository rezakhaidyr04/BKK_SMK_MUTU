<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * P5.7 MASTER: regression tests for LOW-risk security/privacy fixes.
 * Additive only — does not modify any existing test.
 *
 * Locks:
 * - orphaned-applicant safety (soft-deleted applicant): company update
 *   skips notification, show/surat endpoints 404 instead of 500.
 * - account deletion cleans up private files (no orphaned docs on disk).
 * - sitemap + home list only public jobs (scopeActive, post-P5.6 rule).
 * - event registration notes rejects non-string input (422, not 500).
 */
class SecurityPrivacyMasterTest extends TestCase
{
    use RefreshDatabase;

    private function verifiedUmum(array $over = []): User
    {
        return User::factory()->create(array_merge([
            'role' => 'umum',
            'email_verified_at' => now(),
        ], $over));
    }

    private function verifiedCompany(array $over = []): User
    {
        $u = User::factory()->create(array_merge([
            'role' => 'company',
            'email_verified_at' => now(),
        ], $over));
        Company::factory()->create([
            'user_id' => $u->id,
            'is_verified' => true,
            'verification_status' => 'verified',
        ]);

        return $u;
    }

    private function companyWithJob(): array
    {
        $companyUser = $this->verifiedCompany();
        $job = Job::factory()->create([
            'company_id' => $companyUser->company->id,
            'status' => 'active',
            'deadline' => now()->addWeeks(2),
        ]);

        return [$companyUser, $job];
    }

    // ---------- Orphaned applicant (soft-deleted) ----------

    public function test_company_update_skips_notification_when_applicant_deleted(): void
    {
        [$companyUser, $job] = $this->companyWithJob();
        $applicant = $this->verifiedUmum();
        $app = Application::factory()->create([
            'job_id' => $job->id,
            'user_id' => $applicant->id,
            'status' => 'submitted',
        ]);
        $applicant->delete(); // soft delete — application row survives

        Notification::fake();
        $resp = $this->actingAs($companyUser)->patch(route('company.applications.update', $app), [
            'status' => 'under_review',
        ]);

        $resp->assertRedirect();
        $this->assertSame('under_review', $app->fresh()->status);
        Notification::assertNothingSent();
    }

    public function test_company_applicant_show_returns_404_when_applicant_deleted(): void
    {
        [$companyUser, $job] = $this->companyWithJob();
        $applicant = $this->verifiedUmum();
        $app = Application::factory()->create(['job_id' => $job->id, 'user_id' => $applicant->id]);
        $applicant->delete();

        $this->actingAs($companyUser)->get(route('company.applicants.show', $app))->assertNotFound();
    }

    public function test_application_show_returns_404_when_applicant_deleted(): void
    {
        [$companyUser, $job] = $this->companyWithJob();
        $applicant = $this->verifiedUmum();
        $app = Application::factory()->create(['job_id' => $job->id, 'user_id' => $applicant->id]);
        $applicant->delete();

        // Company viewer (authorized via policy) gets 404, not 500.
        $this->actingAs($companyUser)->get(route('applications.show', $app))->assertNotFound();
    }

    public function test_surat_pengantar_returns_404_when_applicant_deleted(): void
    {
        [$companyUser, $job] = $this->companyWithJob();
        $applicant = $this->verifiedUmum();
        $app = Application::factory()->create(['job_id' => $job->id, 'user_id' => $applicant->id]);
        $applicant->delete();

        $this->actingAs($companyUser)->get(route('applications.surat-pengantar', $app))->assertNotFound();
    }

    // ---------- Account deletion cleans private files ----------

    public function test_profile_destroy_deletes_private_files(): void
    {
        Storage::fake('private');
        Storage::fake('public');

        $user = $this->verifiedUmum();
        $certPath = 'certificates/mine.pdf';
        $cvPath = 'cv-files/mine.pdf';
        $docPath = 'user-documents/mine.pdf';
        $attachPath = 'applications/mine.pdf';
        foreach ([$certPath, $cvPath, $docPath, $attachPath] as $p) {
            Storage::disk('private')->put($p, 'secret');
        }
        \App\Models\Certificate::factory()->create(['user_id' => $user->id, 'file_path' => $certPath]);
        \App\Models\CvFile::factory()->create(['user_id' => $user->id, 'file_path' => $cvPath]);
        \App\Models\UserDocument::create([
            'user_id' => $user->id, 'document_type' => 'ktp',
            'file_path' => $docPath, 'original_name' => 'mine.pdf',
        ]);
        Application::factory()->create([
            'user_id' => $user->id, 'status' => 'submitted',
            'attachment_path' => $attachPath, 'attachment_name' => 'mine.pdf',
        ]);

        $this->actingAs($user)->delete(route('profile.destroy'), ['password' => 'password'])
            ->assertRedirect('/');

        foreach ([$certPath, $cvPath, $docPath, $attachPath] as $p) {
            Storage::disk('private')->assertMissing($p);
        }
        // Existing behavior: self-service deletion is forceDelete (hard delete),
        // which cascades file-record rows; files must not be left on disk.
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('certificates', ['file_path' => $certPath]);
    }

    // ---------- Public listing consistency (post-P5.6 rule) ----------

    public function test_sitemap_excludes_expired_active_jobs(): void
    {
        $future = Job::factory()->create(['status' => 'active', 'deadline' => now()->addWeek()]);
        $expired = Job::factory()->create(['status' => 'active', 'deadline' => now()->subDay()]);

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertSee(route('jobs.show', $future), false);
        $response->assertDontSee(route('jobs.show', $expired), false);
    }

    public function test_home_lists_only_non_expired_active_jobs(): void
    {
        Job::factory()->create(['status' => 'active', 'deadline' => now()->addWeek(), 'title' => 'Home Future Aktif']);
        Job::factory()->create(['status' => 'active', 'deadline' => now()->subDay(), 'title' => 'Home Expired Aktif']);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Home Future Aktif');
        $response->assertDontSee('Home Expired Aktif');
    }

    // ---------- Event registration input boundary ----------

    public function test_event_register_rejects_non_string_notes(): void
    {
        $user = $this->verifiedUmum();
        $event = \App\Models\Event::factory()->create(['start_time' => now()->addDays(5)]);

        $resp = $this->actingAs($user)->post(route('events.register', $event), [
            'notes' => ['array' => 'not-allowed'],
        ]);

        $resp->assertSessionHasErrors(['notes']);
        $this->assertDatabaseMissing('event_registrations', [
            'user_id' => $user->id, 'event_id' => $event->id,
        ]);
    }

    public function test_event_register_accepts_string_notes(): void
    {
        $user = $this->verifiedUmum();
        $event = \App\Models\Event::factory()->create(['start_time' => now()->addDays(5)]);

        $this->actingAs($user)->post(route('events.register', $event), [
            'notes' => 'Tolong kursi depan.',
        ])->assertRedirect();

        $this->assertDatabaseHas('event_registrations', [
            'user_id' => $user->id, 'event_id' => $event->id,
            'status' => 'registered', 'notes' => 'Tolong kursi depan.',
        ]);
    }
}
