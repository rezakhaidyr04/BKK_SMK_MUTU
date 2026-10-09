<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\JobReport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobReport>
 */
class JobReportFactory extends Factory
{
    protected $model = JobReport::class;

    public function definition(): array
    {
        return [
            'job_id' => Job::factory(),
            'user_id' => User::factory(),
            'reason' => fake()->randomElement(JobReport::REASONS),
            'detail' => fake()->sentence(12),
            'status' => 'menunggu',
        ];
    }
}
