<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Job;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * M8: statistik dihitung database dari SELURUH data valid
 * (bukan sampel tampilan / bukan agregat PHP).
 */
class AggregateStatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_m8_company_stats_cover_all_reviews_not_sample(): void
    {
        $cu = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->create(['user_id' => $cu->id, 'name' => 'PT Statistik']);
        // 12 review: 10× rating 5 + 2× rating 3 → avg 4.7, count 12.
        for ($i = 0; $i < 10; $i++) {
            Review::factory()->create([
                'user_id' => User::factory()->create(['role' => 'umum'])->id,
                'company_id' => $company->id, 'company_name' => $company->name,
                'rating' => 5, 'status' => 'approved',
            ]);
        }
        for ($i = 0; $i < 2; $i++) {
            Review::factory()->create([
                'user_id' => User::factory()->create(['role' => 'umum'])->id,
                'company_id' => $company->id, 'company_name' => $company->name,
                'rating' => 3, 'status' => 'approved',
            ]);
        }

        // Aturan lama (take-10) akan menulis 10 / 4.6. Aturan baru: 12 / 4.7.
        $this->get(route('companies.show', $company))->assertOk()
            ->assertSee('Berdasarkan 12 ulasan');
    }

    public function test_m8_job_page_rating_covers_all_reviews(): void
    {
        $cu = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->create(['user_id' => $cu->id, 'name' => 'PT Rating Penuh']);
        $job = Job::factory()->create(['company_id' => $company->id, 'status' => 'active', 'deadline' => now()->addWeek()]);
        for ($i = 0; $i < 12; $i++) {
            Review::factory()->create([
                'user_id' => User::factory()->create(['role' => 'umum'])->id,
                'company_id' => $company->id, 'company_name' => $company->name,
                'rating' => 4, 'status' => 'approved',
            ]);
        }

        $this->get(route('jobs.show', $job))->assertOk()->assertSee('(12 ulasan)');
    }

    public function test_m8_application_and_job_counts_match_database(): void
    {
        $cu = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->create(['user_id' => $cu->id]);
        $job = Job::factory()->create(['company_id' => $company->id, 'status' => 'active', 'deadline' => now()->addWeek()]);
        $job2 = Job::factory()->create(['company_id' => $company->id, 'status' => 'closed', 'deadline' => now()->addWeek()]);
        foreach ([1, 2, 3] as $i) {
            \App\Models\Application::factory()->create([
                'job_id' => $job->id,
                'user_id' => User::factory()->create(['role' => 'umum'])->id,
                'status' => 'submitted',
            ]);
        }

        $this->assertEquals(2, Job::where('company_id', $company->id)->count());
        $this->assertEquals(1, Job::where('company_id', $company->id)->active()->count());
        $this->assertEquals(3, \App\Models\Application::where('job_id', $job->id)->count());
        $this->assertEquals(0, \App\Models\Application::where('job_id', $job2->id)->count());
    }
}
