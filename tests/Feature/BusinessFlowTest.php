<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Bookmark;
use App\Models\Company;
use App\Models\Event;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * P5.3 Business Flow — status transition, job lifecycle, bookmark toggle, review, event re-register, notification.
 */
class BusinessFlowTest extends TestCase
{
    use RefreshDatabase;

    private function verifiedUmum(array $over = []): User
    {
        return User::factory()->create(array_merge(['role' => 'umum', 'email_verified_at' => now()], $over));
    }

    private function verifiedCompany(array $over = []): User
    {
        $u = User::factory()->create(array_merge(['role' => 'company', 'email_verified_at' => now()], $over));
        Company::factory()->create(['user_id' => $u->id, 'is_verified' => true, 'verification_status' => 'verified']);
        return $u;
    }

    private function companyWithJob(string $status = 'active'): array
    {
        $companyUser = $this->verifiedCompany();
        $company = $companyUser->company;
        $job = Job::factory()->create(['company_id' => $company->id, 'status' => $status, 'deadline' => now()->addWeeks(2)]);
        return [$companyUser, $company, $job];
    }

    // ---------- 1. APPLICATION STATUS FLOW ----------
    public function test_company_can_transition_application_to_under_review(): void
    {
        [$companyUser, $company, $job] = $this->companyWithJob();
        $applicant = $this->verifiedUmum();
        $app = Application::factory()->create(['job_id' => $job->id, 'user_id' => $applicant->id, 'status' => 'submitted']);

        Notification::fake();
        $resp = $this->actingAs($companyUser)->patch(route('company.applications.update', $app), [
            'status' => 'under_review',
        ]);
        $resp->assertRedirect();
        $this->assertEquals('under_review', $app->fresh()->status);
        // interview fields must be null
        $this->assertNull($app->fresh()->interview_date);
        $this->assertNull($app->fresh()->interview_type);
        Notification::assertSentTo($applicant, \App\Notifications\ApplicationStatusUpdated::class);
    }

    public function test_company_can_transition_to_accepted_and_rejected(): void
    {
        [$companyUser, , $job] = $this->companyWithJob();
        $applicant = $this->verifiedUmum();
        $app = Application::factory()->create(['job_id' => $job->id, 'user_id' => $applicant->id, 'status' => 'under_review']);

        Notification::fake();
        $this->actingAs($companyUser)->patch(route('company.applications.update', $app), ['status' => 'accepted'])->assertRedirect();
        $this->assertEquals('accepted', $app->fresh()->status);

        $app2 = Application::factory()->create(['job_id' => $job->id, 'user_id' => $this->verifiedUmum()->id, 'status' => 'submitted']);
        $this->actingAs($companyUser)->patch(route('company.applications.update', $app2), ['status' => 'rejected'])->assertRedirect();
        $this->assertEquals('rejected', $app2->fresh()->status);
        $this->assertNull($app2->fresh()->interview_date);
    }

    public function test_interviewed_requires_interview_fields(): void
    {
        [$companyUser, , $job] = $this->companyWithJob();
        $app = Application::factory()->create(['job_id' => $job->id, 'user_id' => $this->verifiedUmum()->id, 'status' => 'submitted']);

        // missing interview_date/time/type → validation error
        $resp = $this->actingAs($companyUser)->patch(route('company.applications.update', $app), [
            'status' => 'interviewed',
        ]);
        $resp->assertSessionHasErrors(['interview_date', 'interview_time', 'interview_type']);
        $this->assertEquals('submitted', $app->fresh()->status);
    }

    public function test_interviewed_online_requires_link_and_offline_requires_location(): void
    {
        [$companyUser, , $job] = $this->companyWithJob();
        $app = Application::factory()->create(['job_id' => $job->id, 'user_id' => $this->verifiedUmum()->id, 'status' => 'submitted']);

        // online without link → error
        $resp = $this->actingAs($companyUser)->patch(route('company.applications.update', $app), [
            'status' => 'interviewed',
            'interview_date' => now()->addDays(3)->format('Y-m-d'),
            'interview_time' => '09:00',
            'interview_type' => 'online',
            // missing link
        ]);
        $resp->assertSessionHasErrors(['interview_link']);

        // online with link → success
        $resp2 = $this->actingAs($companyUser)->patch(route('company.applications.update', $app), [
            'status' => 'interviewed',
            'interview_date' => now()->addDays(3)->format('Y-m-d'),
            'interview_time' => '09:00',
            'interview_type' => 'online',
            'interview_link' => 'https://meet.example.com/room',
        ]);
        $resp2->assertRedirect();
        $this->assertEquals('online', $app->fresh()->interview_type);
        $this->assertEquals('https://meet.example.com/room', $app->fresh()->interview_link);

        // offline without location → error (new app)
        $app2 = Application::factory()->create(['job_id' => $job->id, 'user_id' => $this->verifiedUmum()->id, 'status' => 'submitted']);
        $resp3 = $this->actingAs($companyUser)->patch(route('company.applications.update', $app2), [
            'status' => 'interviewed',
            'interview_date' => now()->addDays(3)->format('Y-m-d'),
            'interview_time' => '14:00',
            'interview_type' => 'offline',
        ]);
        $resp3->assertSessionHasErrors(['interview_location']);

        // offline with location → success
        $resp4 = $this->actingAs($companyUser)->patch(route('company.applications.update', $app2), [
            'status' => 'interviewed',
            'interview_date' => now()->addDays(3)->format('Y-m-d'),
            'interview_time' => '14:00',
            'interview_type' => 'offline',
            'interview_location' => 'Kantor BKK SMK MUTU',
        ]);
        $resp4->assertRedirect();
        $this->assertEquals('offline', $app2->fresh()->interview_type);
        $this->assertEquals('Kantor BKK SMK MUTU', $app2->fresh()->interview_location);
    }

    public function test_interviewed_stores_datetime_and_triggers_interview_notification_only_on_change(): void
    {
        [$companyUser, , $job] = $this->companyWithJob();
        $applicant = $this->verifiedUmum();
        $app = Application::factory()->create(['job_id' => $job->id, 'user_id' => $applicant->id, 'status' => 'submitted']);

        Notification::fake();
        $date = now()->addDays(5)->format('Y-m-d');
        $this->actingAs($companyUser)->patch(route('company.applications.update', $app), [
            'status' => 'interviewed',
            'interview_date' => $date,
            'interview_time' => '10:30',
            'interview_type' => 'offline',
            'interview_location' => 'Ruang Interview',
            'interview_notes' => 'Bawa CV',
        ])->assertRedirect();

        $fresh = $app->fresh();
        $this->assertEquals('interviewed', $fresh->status);
        $this->assertEquals($date . ' 10:30:00', $fresh->interview_date->format('Y-m-d H:i:s'));
        Notification::assertSentTo($applicant, \App\Notifications\InterviewScheduled::class);

        // same status again should not send notification (oldStatus === newStatus)
        Notification::fake();
        $this->actingAs($companyUser)->patch(route('company.applications.update', $fresh), [
            'status' => 'interviewed',
            'interview_date' => $date,
            'interview_time' => '10:30',
            'interview_type' => 'offline',
            'interview_location' => 'Ruang Interview',
        ])->assertRedirect();
        Notification::assertNothingSent();
    }

    public function test_invalid_status_rejected_by_validation(): void
    {
        [$companyUser, , $job] = $this->companyWithJob();
        $app = Application::factory()->create(['job_id' => $job->id, 'user_id' => $this->verifiedUmum()->id, 'status' => 'submitted']);

        $resp = $this->actingAs($companyUser)->patch(route('company.applications.update', $app), [
            'status' => 'invalid_status',
        ]);
        $resp->assertSessionHasErrors(['status']);
        $this->assertEquals('submitted', $app->fresh()->status);
    }

    public function test_status_change_clears_interview_fields_when_no_longer_interviewed(): void
    {
        [$companyUser, , $job] = $this->companyWithJob();
        $app = Application::factory()->create([
            'job_id' => $job->id,
            'user_id' => $this->verifiedUmum()->id,
            'status' => 'interviewed',
            'interview_date' => now()->addDays(2),
            'interview_type' => 'online',
            'interview_link' => 'https://meet.example.com/abc',
        ]);

        $this->actingAs($companyUser)->patch(route('company.applications.update', $app), [
            'status' => 'accepted',
        ])->assertRedirect();

        $fresh = $app->fresh();
        $this->assertNull($fresh->interview_date);
        $this->assertNull($fresh->interview_type);
        $this->assertNull($fresh->interview_link);
        $this->assertNull($fresh->interview_location);
    }

    // ---------- 2. JOB LIFECYCLE ----------
    public function test_pending_job_not_viewable_and_cannot_be_applied(): void
    {
        $pending = Job::factory()->create(['status' => 'pending', 'deadline' => now()->addWeek()]);
        $this->get(route('jobs.show', $pending))->assertNotFound();

        $umum = $this->verifiedUmum();
        $cover = str_repeat('Saya sangat tertarik dengan posisi ini dan memenuhi kualifikasi. ', 3);
        $this->actingAs($umum)->post(route('jobs.apply', $pending), ['cover_letter' => $cover])->assertSessionHas('error');
        $this->assertDatabaseMissing('applications', ['job_id' => $pending->id]);
    }

    public function test_expired_and_closed_job_cannot_be_applied_via_business_flow(): void
    {
        $expired = Job::factory()->create(['status' => 'active', 'deadline' => now()->subDay()]);
        $closed = Job::factory()->create(['status' => 'closed', 'deadline' => now()->addWeek()]);
        $umum = $this->verifiedUmum();
        $cover = str_repeat('Saya sangat tertarik dengan posisi ini dan memenuhi kualifikasi. ', 3);

        $this->actingAs($umum)->post(route('jobs.apply', $expired), ['cover_letter' => $cover])->assertSessionHas('error');
        $this->actingAs($umum)->post(route('jobs.apply', $closed), ['cover_letter' => $cover])->assertSessionHas('error');
    }

    // ---------- 3. BOOKMARK TOGGLE ----------
    public function test_job_bookmark_toggle_creates_and_removes(): void
    {
        $umum = $this->verifiedUmum();
        $job = Job::factory()->create(['status' => 'active', 'deadline' => now()->addWeek()]);

        // first toggle → created
        $r1 = $this->actingAs($umum)->post(route('jobs.bookmark', $job));
        $r1->assertOk();
        $this->assertTrue($r1->json('bookmarked'));
        $this->assertDatabaseHas('bookmarks', ['user_id' => $umum->id, 'job_id' => $job->id]);

        // second toggle → removed
        $r2 = $this->actingAs($umum)->post(route('jobs.bookmark', $job));
        $r2->assertOk();
        $this->assertFalse($r2->json('bookmarked'));
        $this->assertDatabaseMissing('bookmarks', ['user_id' => $umum->id, 'job_id' => $job->id]);

        // third toggle → created again
        $r3 = $this->actingAs($umum)->post(route('jobs.bookmark', $job));
        $r3->assertOk();
        $this->assertTrue($r3->json('bookmarked'));
    }

    public function test_bookmark_on_soft_deleted_job_still_handled(): void
    {
        $umum = $this->verifiedUmum();
        $job = Job::factory()->create();
        Bookmark::create(['user_id' => $umum->id, 'job_id' => $job->id]);
        $job->delete(); // soft delete

        $resp = $this->actingAs($umum)->get(route('bookmarks.index'));
        $resp->assertOk();
        // withTrashed ensures bookmark still visible but job may be null & handled gracefully
        $this->assertDatabaseHas('bookmarks', ['user_id' => $umum->id, 'job_id' => $job->id]);
    }

    // ---------- 4. REVIEW FLOW ----------
    public function test_unauthenticated_cannot_store_review(): void
    {
        $this->post(route('reviews.store'), [
            'rating' => 5,
            'comment' => str_repeat('Sangat bagus dan membantu. ', 5),
        ])->assertRedirect(route('login'));
        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_review_validation_rejects_invalid_rating_and_short_comment(): void
    {
        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);

        $user = $this->verifiedUmum();

        // rating 6 invalid
        $this->actingAs($user)->post(route('reviews.store'), [
            'rating' => 6,
            'comment' => str_repeat('Bagus ', 5),
        ])->assertSessionHasErrors(['rating']);

        // rating 0 invalid
        $this->actingAs($user)->post(route('reviews.store'), [
            'rating' => 0,
            'comment' => str_repeat('Bagus ', 5),
        ])->assertSessionHasErrors(['rating']);

        // comment too short (<10)
        $this->actingAs($user)->post(route('reviews.store'), [
            'rating' => 4,
            'comment' => 'singkat',
        ])->assertSessionHasErrors(['comment']);

        // comment too long (>1000)
        $this->actingAs($user)->post(route('reviews.store'), [
            'rating' => 4,
            'comment' => str_repeat('a', 1001),
        ])->assertSessionHasErrors(['comment']);
    }

    public function test_review_success_creates_pending_and_stores_optional_fields(): void
    {
        $user = $this->verifiedUmum();
        $resp = $this->actingAs($user)->post(route('reviews.store'), [
            'rating' => 5,
            'comment' => str_repeat('Layanan BKK sangat membantu saya mendapatkan pekerjaan. ', 3),
            'job_title' => 'Frontend Dev',
            'company_name' => 'PT Maju',
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'phone' => '08123456789',
        ]);
        $resp->assertRedirect();
        $resp->assertSessionHas('success');
        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'rating' => 5,
            'status' => 'pending',
            'job_title' => 'Frontend Dev',
        ]);
    }

    public function test_review_create_page_requires_auth(): void
    {
        $this->get(route('reviews.create'))->assertRedirect(route('login'));
        $user = $this->verifiedUmum();
        $this->actingAs($user)->get(route('reviews.create'))->assertOk();
    }

    // ---------- 5. EVENT RE-REGISTER ----------
    public function test_event_re_register_after_cancel_succeeds(): void
    {
        $user = $this->verifiedUmum();
        $event = Event::factory()->create(['start_time' => now()->addDays(5)]);

        $this->actingAs($user)->post(route('events.register', $event))->assertRedirect();
        $this->actingAs($user)->delete(route('events.cancel', $event))->assertRedirect();
        $this->assertDatabaseHas('event_registrations', ['user_id' => $user->id, 'event_id' => $event->id, 'status' => 'cancelled']);

        // re-register should reactivate
        $this->actingAs($user)->post(route('events.register', $event))->assertSessionHas('success');
        $this->assertDatabaseHas('event_registrations', ['user_id' => $user->id, 'event_id' => $event->id, 'status' => 'registered']);
        $this->assertEquals(1, \App\Models\EventRegistration::where('user_id', $user->id)->where('event_id', $event->id)->count());
    }

    // ---------- 6. NOTIFICATION already covered but verify ApplicationStatusUpdated ----------
    public function test_application_status_updated_notification_sent_for_non_interview(): void
    {
        [$companyUser, , $job] = $this->companyWithJob();
        $applicant = $this->verifiedUmum();
        $app = Application::factory()->create(['job_id' => $job->id, 'user_id' => $applicant->id, 'status' => 'submitted']);
        Notification::fake();
        $this->actingAs($companyUser)->patch(route('company.applications.update', $app), ['status' => 'rejected'])->assertRedirect();
        Notification::assertSentTo($applicant, \App\Notifications\ApplicationStatusUpdated::class);
    }
}
