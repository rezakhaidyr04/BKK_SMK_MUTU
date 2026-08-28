<?php

namespace Tests\Unit;

use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use App\Notifications\ApplicationReceived;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_application_received_uses_database_and_mail_channels(): void
    {
        $companyUser = User::factory()->create();
        $company = Company::factory()->create(['user_id' => $companyUser->id]);
        $job = Job::factory()->create(['company_id' => $company->id]);
        $applicant = User::factory()->create();

        $application = Application::factory()->create([
            'job_id' => $job->id,
            'user_id' => $applicant->id,
        ]);

        $notification = new ApplicationReceived($application);

        $this->assertEquals(['database', 'mail'], $notification->via($companyUser));
    }

    public function test_application_received_database_payload_is_correct(): void
    {
        $companyUser = User::factory()->create();
        $company = Company::factory()->create(['user_id' => $companyUser->id]);
        $job = Job::factory()->create(['company_id' => $company->id]);
        $applicant = User::factory()->create();

        $application = Application::factory()->create([
            'job_id' => $job->id,
            'user_id' => $applicant->id,
        ]);

        $db = (new ApplicationReceived($application))->toDatabase($companyUser);

        $this->assertEquals($application->id, $db['application_id']);
        $this->assertEquals($job->id, $db['job_id']);
        $this->assertEquals($applicant->id, $db['applicant_id']);
        $this->assertEquals($applicant->name, $db['applicant_name']);
    }

    public function test_application_received_mail_subject_contains_job_title(): void
    {
        $companyUser = User::factory()->create();
        $company = Company::factory()->create(['user_id' => $companyUser->id]);
        $job = Job::factory()->create(['company_id' => $company->id]);
        $applicant = User::factory()->create();

        $application = Application::factory()->create([
            'job_id' => $job->id,
            'user_id' => $applicant->id,
        ]);

        $mail = (new ApplicationReceived($application))->toMail($companyUser);

        $this->assertStringContainsString($job->title, $mail->subject);
    }
}
