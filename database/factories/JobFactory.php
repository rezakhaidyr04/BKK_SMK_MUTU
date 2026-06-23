<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Job;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    protected $model = Job::class;

    public function definition(): array
    {
        return [
            'company_id' => \App\Models\Company::factory(),
            'title' => fake()->jobTitle(),
            'position' => fake()->jobTitle(),
            'location' => fake()->city(),
            'job_type' => 'full_time',
            'salary_min' => fake()->numberBetween(3000000, 8000000),
            'salary_max' => fake()->numberBetween(8000001, 15000000),
            'description' => fake()->paragraph(),
            'qualifications' => fake()->sentence(),
            'benefits' => fake()->sentence(),
            'deadline' => now()->addWeeks(2),
            'status' => 'active',
        ];
    }
}
