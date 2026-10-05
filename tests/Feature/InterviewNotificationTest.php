<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use App\Notifications\InterviewScheduled;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * Tahap wawancara wajib mengirim notifikasi web (database) + email (mail).
 */
class InterviewNotificationTest extends TestCase
{
    use RefreshDatabase;

    private function setupCase(): array
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
        $umum = User::factory()->create(['role' => 'umum', 'email_verified_at' => now()]);
        $app = Application::factory()->create([
            'job_id' => $job->id,
            'user_id' => $umum->id,
            'status' => 'submitted',
        ]);
        return [$companyUser, $umum, $job, $app];
    }

    private function interviewPayload(): array
    {
        return [
            'status' => 'interviewed',
            'interview_date' => now()->addDays(3)->format('Y-m-d'),
            'interview_time' => '10:00',
            'interview_type' => 'offline',
            'interview_link' => '',
            'interview_location' => 'Ruang HRD',
            'interview_notes' => '',
        ];
    }

    public function test_interview_dispatches_web_and_mail_notification(): void
    {
        Notification::fake();
        [$companyUser, $umum, $job, $app] = $this->setupCase();

        $this->actingAs($companyUser)
            ->patch(route('company.applications.update', $app), $this->interviewPayload())
            ->assertSessionHas('success');

        Notification::assertSentTo($umum, InterviewScheduled::class);

        $sent = Notification::sent($umum, InterviewScheduled::class);
        $this->assertNotEmpty($sent);
    }

    public function test_interview_mail_has_correct_subject_and_view(): void
    {
        [$companyUser, $umum, $job, $app] = $this->setupCase();
        $app->update([
            'status' => 'interviewed',
            'interview_date' => now()->addDays(3),
            'interview_type' => 'offline',
            'interview_location' => 'Ruang HRD',
        ]);

        $mail = (new InterviewScheduled($app->fresh()))->toMail($umum);
        $this->assertStringContainsString('Undangan Wawancara', $mail->subject);
        $html = $mail->render();
        $this->assertStringContainsString('Undangan Wawancara', $html);
        $this->assertStringContainsString('Ruang HRD', $html);
    }

    public function test_interview_notification_uses_mail_and_database_channels(): void
    {
        [$companyUser, $umum, $job, $app] = $this->setupCase();
        $channels = (new InterviewScheduled($app))->via($umum);
        $this->assertContains('mail', $channels);
        $this->assertContains('database', $channels);
    }

    public function test_resave_without_status_change_does_not_resend(): void
    {
        Notification::fake();
        [$companyUser, $umum, $job, $app] = $this->setupCase();

        $this->actingAs($companyUser)
            ->patch(route('company.applications.update', $app), $this->interviewPayload())
            ->assertSessionHas('success');
        Notification::assertSentTo($umum, InterviewScheduled::class);

        Notification::fake();
        $this->actingAs($companyUser)
            ->patch(route('company.applications.update', $app->fresh()), $this->interviewPayload())
            ->assertSessionHas('success');
        Notification::assertNothingSent();
    }

    public function test_umum_cannot_trigger_interview_notification(): void
    {
        Notification::fake();
        [$companyUser, $umum, $job, $app] = $this->setupCase();

        $this->actingAs($umum)
            ->patch(route('company.applications.update', $app), $this->interviewPayload())
            ->assertForbidden();
        Notification::assertNothingSent();
        $this->assertSame('submitted', $app->fresh()->status);
    }

    public function test_other_company_cannot_trigger_interview_notification(): void
    {
        Notification::fake();
        [$companyUser, $umum, $job, $app] = $this->setupCase();

        $otherCompanyUser = User::factory()->create(['role' => 'company', 'email_verified_at' => now()]);
        Company::factory()->create([
            'user_id' => $otherCompanyUser->id,
            'is_verified' => true,
            'verification_status' => 'verified',
        ]);

        $this->actingAs($otherCompanyUser)
            ->patch(route('company.applications.update', $app), $this->interviewPayload())
            ->assertForbidden();
        Notification::assertNothingSent();
        $this->assertSame('submitted', $app->fresh()->status);
    }

    public function test_interview_email_contains_required_data(): void
    {
        [$companyUser, $umum, $job, $app] = $this->setupCase();
        // Factory tidak mengisi company_name denormalized; set eksplisit
        // agar menyerupai data produksi (Company Job store mengisi nama).
        $job->update(['company_name' => 'PT Maju Testing']);
        $app->update([
            'status' => 'interviewed',
            'interview_date' => now()->addDays(3)->setTime(10, 0),
            'interview_type' => 'offline',
            'interview_location' => 'Ruang HRD',
        ]);

        $mail = (new InterviewScheduled($app->fresh()))->toMail($umum);
        $html = $mail->render();
        $this->assertStringContainsString($umum->name, $html);
        $this->assertStringContainsString($job->fresh()->title, $html);
        $this->assertStringContainsString('PT Maju Testing', $html);
        $this->assertStringContainsString('Ruang HRD', $html);
    }
}
