<?php

namespace Tests\Feature;

use App\Jobs\GenerateCvJob;
use App\Models\Application;
use App\Models\Company;
use App\Models\CvFile;
use App\Models\Event;
use App\Models\Job;
use App\Models\User;
use App\Services\CvBuilderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Regresi untuk temuan audit keamanan & logika (cek ulang keseluruhan).
 */
class AuditFixRegressionTest extends TestCase
{
    use RefreshDatabase;

    private function applicant(): User
    {
        return User::factory()->create([
            'role' => 'umum',
            'email_verified_at' => now(),
            'phone' => '081234567890',
        ]);
    }

    private function companyWithApplicant(User $applicant): User
    {
        $companyUser = User::factory()->create(['role' => 'company', 'email_verified_at' => now()]);
        $company = Company::factory()->create([
            'user_id' => $companyUser->id,
            'is_verified' => true,
            'verification_status' => 'verified',
        ]);
        $job = Job::factory()->create([
            'company_id' => $company->id,
            'status' => 'active',
            'deadline' => now()->addWeek(),
        ]);
        Application::factory()->create([
            'job_id' => $job->id,
            'user_id' => $applicant->id,
            'status' => 'submitted',
        ]);

        return $companyUser;
    }

    public function test_company_with_view_access_cannot_delete_applicant_cv(): void
    {
        Storage::fake('private');
        $applicant = $this->applicant();
        $companyUser = $this->companyWithApplicant($applicant);
        $cv = CvFile::factory()->create(['user_id' => $applicant->id]);
        Storage::disk('private')->put($cv->file_path, '%PDF-1.4 dummy');

        // Perusahaan boleh MELIHAT (policy view) tapi TIDAK boleh menghapus.
        $this->actingAs($companyUser)->get(route('cv.download', $cv))->assertOk();
        $this->actingAs($companyUser)->delete(route('cv.destroy', $cv))->assertForbidden();
        $this->assertDatabaseHas('cv_files', ['id' => $cv->id]);
    }

    public function test_owner_can_still_delete_own_cv(): void
    {
        Storage::fake('private');
        $applicant = $this->applicant();
        $cv = CvFile::factory()->create(['user_id' => $applicant->id]);

        $this->actingAs($applicant)->delete(route('cv.destroy', $cv))->assertRedirect();
        $this->assertDatabaseMissing('cv_files', ['id' => $cv->id]);
    }

    public function test_export_masks_contact_until_interview_stage(): void
    {
        $applicant = $this->applicant();
        $companyUser = $this->companyWithApplicant($applicant);
        $app = Application::where('user_id', $applicant->id)->firstOrFail();

        $csv = $this->actingAs($companyUser)->get(route('company.applicants.export'))->assertOk()->getContent();
        $this->assertStringNotContainsString('081234567890', $csv);
        $this->assertStringNotContainsString($applicant->email, $csv);

        $app->update(['status' => 'interviewed']);
        $csv2 = $this->actingAs($companyUser)->get(route('company.applicants.export'))->assertOk()->getContent();
        $this->assertStringContainsString('081234567890', $csv2);
        $this->assertStringContainsString($applicant->email, $csv2);
    }

    public function test_event_register_rejects_company_role(): void
    {
        $companyUser = User::factory()->create(['role' => 'company', 'email_verified_at' => now()]);
        $event = Event::factory()->create(['start_time' => now()->addDays(2)]);

        $this->actingAs($companyUser)
            ->post(route('events.register', $event), ['notes' => 'ikut'])
            ->assertForbidden();
        $this->assertDatabaseMissing('event_registrations', [
            'event_id' => $event->id,
            'user_id' => $companyUser->id,
        ]);
    }

    public function test_cv_signature_changes_when_profile_bio_changes(): void
    {
        Queue::fake();
        $user = $this->applicant();
        $this->actingAs($user);

        $svc = app(CvBuilderService::class);
        $svc->generateCv([]);
        Cache::forget(CvBuilderService::generatingKey($user->id));

        $user->update(['bio' => 'Bio unik yang pasti mengubah signature']);
        $svc->generateCv([]);

        $pushed = Queue::pushed(GenerateCvJob::class);
        $this->assertCount(2, $pushed);
        $this->assertNotEquals($pushed[0]->fileName, $pushed[1]->fileName);
    }
}
