<?php

namespace Database\Factories;

use App\Models\JobAlert;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobAlert>
 */
class JobAlertFactory extends Factory
{
    protected $model = JobAlert::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'keyword' => fake()->word(),
            'job_type' => null,
            'province' => null,
            'city' => null,
            'district' => null,
            'is_active' => true,
        ];
    }
}
