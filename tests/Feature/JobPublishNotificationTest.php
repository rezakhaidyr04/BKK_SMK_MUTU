<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use App\Notifications\ApplicationStatusUpdated;
use App\Notifications\InterviewScheduled;
use App\Notifications\NewJobPosted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Notifikasi otomatis: lowongan baru saat admin approve + email & in-app
 * untuk setiap perubahan tahap lamaran (termasuk wawancara).
 */
class JobPublishNotificationTest extends TestCase
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

    private function companyWithPendingJob(): array
    {
        $companyUser = User::factory()->create(['role' => 'company', 'email_verified_at' => now()]);
        $company = Company::factory()->create(['user_id' => $companyUser->id]);
        $job = Job::factory()->create(['company_id' => $company->id, 'status' => 'pending']);

        return [$companyUser, $company, $job];
    }

    public function test_admin_approve_notifies_all_job_seekers(): void
    {
        [, , $job] = $this->companyWithPendingJob();
        $seeker1 = User::factory()->create(['role' => 'umum', 'email_verified_at' => now()]);
        $seeker2 = User::factory()->create(['role' => 'umum', 'email_verified_at' => now()]);

        Notification::fake();

        $this->actingAs($this->admin)
            ->post(route('admin.jobs.approve', $job))
            ->assertRedirect();

        $this->assertEquals('active', $job->fresh()->status);
        Notification::assertSentTo($seeker1, NewJobPosted::class);
        Notification::assertSentTo($seeker2, NewJobPosted::class);
    }

    public function test_admin_approve_already_active_job_sends_nothing(): void
    {
        [, , $job] = $this->companyWithPendingJob();
        $job->update(['status' => 'active']);

        Notification::fake();

        $this->actingAs($this->admin)
            ->post(route('admin.jobs.approve', $job))
            ->assertRedirect();

        Notification::assertNothingSent();
    }

    public function test_status_change_to_accepted_sends_mail_and_database(): void
    {
        [$companyUser, , $job] = $this->companyWithPendingJob();
        $applicant = User::factory()->create(['role' => 'umum', 'email_verified_at' => now()]);
        $app = Application::factory()->create([
            'job_id' => $job->id, 'user_id' => $applicant->id, 'status' => 'submitted',
        ]);

        Notification::fake();

        $this->actingAs($companyUser)
            ->patch(route('company.applications.update', $app), ['status' => 'accepted'])
            ->assertRedirect();

        Notification::assertSentTo($applicant, ApplicationStatusUpdated::class);
    }

    public function test_status_change_to_interviewed_sends_interview_mail_and_database(): void
    {
        [$companyUser, , $job] = $this->companyWithPendingJob();
        $applicant = User::factory()->create(['role' => 'umum', 'email_verified_at' => now()]);
        $app = Application::factory()->create([
            'job_id' => $job->id, 'user_id' => $applicant->id, 'status' => 'submitted',
        ]);

        Notification::fake();

        $this->actingAs($companyUser)->patch(route('company.applications.update', $app), [
            'status' => 'interviewed',
            'interview_date' => now()->addDays(3)->format('Y-m-d'),
            'interview_time' => '10:00',
            'interview_type' => 'offline',
            'interview_location' => 'Ruang Interview',
        ])->assertRedirect();

        Notification::assertSentTo($applicant, InterviewScheduled::class);
    }
}
