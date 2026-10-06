<?php

namespace Tests\Feature;

use App\Jobs\SendJobNotificationsChunk;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use App\Notifications\NewJobPosted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * M1: notifikasi massal publish job wajib via antrean (bukan sinkron),
 * penerima tetap (role umum), retry idempoten, template utuh.
 */
class MassNotificationTest extends TestCase
{
    use RefreshDatabase;

    private function verifiedCompany(): User
    {
        $u = User::factory()->create(['role' => 'company', 'email_verified_at' => now()]);
        Company::factory()->create(['user_id' => $u->id, 'is_verified' => true, 'verification_status' => 'verified']);
        return $u;
    }

    private function seeker(): User
    {
        return User::factory()->create(['role' => 'umum', 'email_verified_at' => now()]);
    }

    public function test_m1_publish_dispatches_chunk_without_sync_notifications(): void
    {
        Queue::fake();
        $companyUser = $this->verifiedCompany();
        $this->seeker();
        $this->seeker();
        $job = Job::factory()->create(['company_id' => $companyUser->company->id, 'status' => 'draft', 'deadline' => now()->addWeek()]);

        $this->actingAs($companyUser)->post(route('company.jobs.publish', $job))->assertRedirect();

        // Tepat 1 chunk untuk 2 user (perJob 200), NOL baris notifikasi sinkron.
        Queue::assertPushed(SendJobNotificationsChunk::class, 1);
        $this->assertEquals(0, DB::table('notifications')->where('type', NewJobPosted::class)->count());
    }

    public function test_m1_chunk_delivers_to_all_umum_only_with_intact_template(): void
    {
        $companyUser = $this->verifiedCompany();
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
        $s1 = $this->seeker();
        $s2 = $this->seeker();
        $job = Job::factory()->create(['company_id' => $companyUser->company->id, 'status' => 'active', 'deadline' => now()->addWeek()]);

        $min = (int) User::where('role', 'umum')->min('id');
        $max = (int) User::where('role', 'umum')->max('id');
        (new SendJobNotificationsChunk($job->id, $min, $max))->handle();

        foreach ([$s1, $s2] as $s) {
            $notifs = $s->notifications()->where('type', NewJobPosted::class)->get();
            $this->assertCount(1, $notifs);
            $this->assertEquals($job->id, $notifs->first()->data['job_id']);
            $this->assertEquals('new_job', $notifs->first()->data['type']);
            $this->assertStringContainsString((string) $job->id, $notifs->first()->data['url']);
        }
        $this->assertCount(0, $companyUser->notifications()->where('type', NewJobPosted::class)->get());
        $this->assertCount(0, $admin->notifications()->where('type', NewJobPosted::class)->get());
    }

    public function test_m1_chunk_retry_does_not_duplicate(): void
    {
        $companyUser = $this->verifiedCompany();
        $s1 = $this->seeker();
        $job = Job::factory()->create(['company_id' => $companyUser->company->id, 'status' => 'active', 'deadline' => now()->addWeek()]);

        $min = (int) User::where('role', 'umum')->min('id');
        $max = (int) User::where('role', 'umum')->max('id');
        $chunk = new SendJobNotificationsChunk($job->id, $min, $max);
        $chunk->handle();
        $chunk->handle(); // simulasi retry worker

        $this->assertCount(1, $s1->notifications()->where('type', NewJobPosted::class)->get());
    }

    public function test_m1_missing_job_chunk_skipped_safely(): void
    {
        (new SendJobNotificationsChunk(999999, 1, 100000))->handle();
        $this->assertEquals(0, DB::table('notifications')->where('type', NewJobPosted::class)->count());
        $this->assertTrue(true); // tidak throw
    }
}
