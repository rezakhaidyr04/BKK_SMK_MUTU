<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CompanyJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_can_store_job(): void
    {
        $user = User::factory()->create([
            'role' => 'company',
            'email_verified_at' => now(),
        ]);

        Company::factory()->create([
            'user_id' => $user->id,
            'is_verified' => true,
            'verification_status' => 'verified',
        ]);

        $response = $this->actingAs($user)->post(route('company.jobs.store'), [
            'title' => 'Operator Produksi',
            'position' => 'Operator Produksi',
            'location' => 'Bandung',
            'job_type' => 'full_time',
            'salary_min' => 3000000,
            'salary_max' => 4500000,
            'description' => 'Deskripsi pekerjaan operator produksi.',
            'qualifications' => 'Lulusan SMK',
            'benefits' => 'BPJS',
            'deadline' => Carbon::now()->addDays(14)->toDateString(),
            'status' => 'active',
        ]);

        $response->assertRedirect(route('company.jobs.index'));

        $this->assertDatabaseHas('jobs', [
            'company_id' => $user->company->id,
            'title' => 'Operator Produksi',
            'position' => 'Operator Produksi',
            'location' => 'Bandung',
        ]);
    }
}
