<?php

namespace Tests\Unit;

use App\Models\Job;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * P2.6: verifikasi helper Job (scopeActive/scopeExpired/isActive/isExpired)
 * merepresentasikan behavior existing — tanpa mengubah business behavior.
 */
class JobTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_active_for_active_job_with_future_deadline(): void
    {
        $job = Job::factory()->create(['status' => 'active', 'deadline' => now()->addWeek()]);

        $this->assertTrue($job->isActive());
        $this->assertFalse($job->isExpired());
    }

    public function test_is_active_is_false_for_non_active_status(): void
    {
        foreach (['draft', 'pending', 'closed', 'rejected'] as $status) {
            $job = Job::factory()->create(['status' => $status, 'deadline' => now()->addWeek()]);

            $this->assertFalse($job->isActive(), "status {$status} seharusnya tidak active");
            $this->assertFalse($job->isExpired());
        }
    }

    public function test_is_expired_for_past_deadline(): void
    {
        $job = Job::factory()->create(['status' => 'active', 'deadline' => now()->subDay()]);

        $this->assertTrue($job->isExpired());
        $this->assertFalse($job->isActive());
    }

    public function test_job_without_deadline_is_not_expired(): void
    {
        $job = Job::factory()->create(['status' => 'active', 'deadline' => null]);

        $this->assertFalse($job->isExpired());
        $this->assertTrue($job->isActive());
    }

    public function test_scope_active_returns_only_active_with_future_deadline(): void
    {
        $visible = Job::factory()->create(['status' => 'active', 'deadline' => now()->addWeek()]);
        Job::factory()->create(['status' => 'active', 'deadline' => now()->subDay()]);
        Job::factory()->create(['status' => 'draft', 'deadline' => now()->addWeek()]);
        Job::factory()->create(['status' => 'closed', 'deadline' => now()->addWeek()]);

        $ids = Job::active()->pluck('id')->all();

        $this->assertSame([$visible->id], $ids);
    }

    public function test_scope_expired_returns_only_past_deadline(): void
    {
        $expired = Job::factory()->create(['status' => 'active', 'deadline' => now()->subDay()]);
        Job::factory()->create(['status' => 'active', 'deadline' => now()->addWeek()]);
        Job::factory()->create(['status' => 'draft', 'deadline' => now()->addWeek()]);
        Job::factory()->create(['status' => 'active', 'deadline' => null]);

        $ids = Job::expired()->pluck('id')->all();

        $this->assertSame([$expired->id], $ids);
    }
}
