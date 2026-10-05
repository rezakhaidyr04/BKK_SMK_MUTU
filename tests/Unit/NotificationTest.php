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

    public function test_application_received_uses_database_channel_only(): void
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

        $this->assertEquals(['database'], $notification->via($companyUser));
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

    public function test_application_status_updated_uses_mail_and_database(): void
    {
        $applicant = User::factory()->create();
        $companyUser = User::factory()->create();
        $company = Company::factory()->create(['user_id' => $companyUser->id]);
        $job = Job::factory()->create(['company_id' => $company->id]);
        $application = Application::factory()->create([
            'job_id' => $job->id,
            'user_id' => $applicant->id,
            'status' => 'accepted',
        ]);

        $notification = new \App\Notifications\ApplicationStatusUpdated($application);

        $this->assertEquals(['mail', 'database'], $notification->via($applicant));
    }

    public function test_application_status_updated_mail_and_payload(): void
    {
        $applicant = User::factory()->create();
        $companyUser = User::factory()->create();
        $company = Company::factory()->create(['user_id' => $companyUser->id]);
        $job = Job::factory()->create(['company_id' => $company->id]);
        $application = Application::factory()->create([
            'job_id' => $job->id,
            'user_id' => $applicant->id,
            'status' => 'interviewed',
        ]);

        $notification = new \App\Notifications\ApplicationStatusUpdated($application);
        $mail = $notification->toMail($applicant);

        $this->assertStringContainsString($job->title, $mail->subject);

        $db = $notification->toArray($applicant);
        $this->assertEquals('application_status', $db['type']);
        $this->assertEquals($application->id, $db['application_id']);
        $this->assertArrayHasKey('url', $db);
    }

    public function test_new_job_posted_database_payload_is_correct(): void
    {
        $user = User::factory()->create(['role' => 'umum']);
        $companyUser = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->create(['user_id' => $companyUser->id]);
        $job = Job::factory()->create(['company_id' => $company->id, 'status' => 'active']);

        $notification = new \App\Notifications\NewJobPosted($job);

        $this->assertEquals(['database'], $notification->via($user));

        $db = $notification->toArray($user);
        $this->assertEquals('new_job', $db['type']);
        $this->assertEquals($job->id, $db['job_id']);
        $this->assertStringContainsString($job->title, $db['message']);
        $this->assertArrayHasKey('url', $db);
    }
}

