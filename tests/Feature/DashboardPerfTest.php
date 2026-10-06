<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use App\Queries\UmumDashboardQuery;
use App\Services\JobMatchingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * M2: skills dihitung sekali — skor/ranking identik, query turun signifikan.
 */
class DashboardPerfTest extends TestCase
{
    use RefreshDatabase;

    public function test_m2_injected_skills_gives_identical_scores(): void
    {
        $user = User::factory()->create(['role' => 'umum', 'email_verified_at' => now(), 'address' => 'Karawang']);
        $user->skills()->create(['name' => 'PHP']);
        $user->skills()->create(['name' => 'Laravel']);
        $cu = User::factory()->create(['role' => 'company', 'email_verified_at' => now()]);
        $company = Company::factory()->create(['user_id' => $cu->id]);
        $jobs = Job::factory()->count(5)->create(['company_id' => $company->id, 'status' => 'active', 'deadline' => now()->addWeek()]);

        $service = new JobMatchingService();
        $precomputed = $service->userSkillNames($user);
        foreach ($jobs as $job) {
            $this->assertSame($service->score($job, $user), $service->score($job, $user, $precomputed));
        }
    }

    public function test_m2_dashboard_queries_user_skills_once(): void
    {
        $user = User::factory()->create(['role' => 'umum', 'email_verified_at' => now()]);
        $user->skills()->create(['name' => 'PHP']);
        $cu = User::factory()->create(['role' => 'company', 'email_verified_at' => now()]);
        $company = Company::factory()->create(['user_id' => $cu->id]);
        Job::factory()->count(10)->create(['company_id' => $company->id, 'status' => 'active', 'deadline' => now()->addWeek()]);

        $pluckQueries = 0;
        DB::listen(function ($q) use (&$pluckQueries) {
            if (str_contains($q->sql, 'user_skills')) {
                $pluckQueries++;
            }
        });

        $data = (new UmumDashboardQuery(app(JobMatchingService::class), app(\App\Services\ProfileCompletionService::class)))->get($user);

        $this->assertEquals(1, $pluckQueries, 'Skills user harus di-query tepat 1x (sebelumnya 1x per job).');
        $this->assertCount(6, $data['recommendedJobs']);
        // Ranking tetap desc berdasarkan match_score.
        $scores = $data['recommendedJobs']->pluck('match_score')->all();
        $sorted = $scores;
        rsort($sorted);
        $this->assertSame($sorted, $scores);
    }
}
