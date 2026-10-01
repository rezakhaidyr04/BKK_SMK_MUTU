<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Job;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ApplicationReceived;
use App\Repositories\ApplicationRepository;

class NotificationAndMessagingTest extends TestCase
{
    use RefreshDatabase;

    public function test_application_received_notification_is_dispatched()
    {
        Notification::fake();

        $companyUser = User::factory()->create();
        $applicant = User::factory()->create();

        $company = Company::factory()->create(['user_id' => $companyUser->id]);
        $job = Job::factory()->create(['company_id' => $company->id]);

        (new ApplicationRepository())->createApplication([
            'job_id' => $job->id,
            'user_id' => $applicant->id,
            'cover_letter' => 'I apply',
        ]);

        Notification::assertSentTo($companyUser, ApplicationReceived::class);
    }

}
