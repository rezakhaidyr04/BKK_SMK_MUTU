<?php

namespace Database\Factories;

use App\Models\TracerStudy;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TracerStudy>
 */
class TracerStudyFactory extends Factory
{
    protected $model = TracerStudy::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'status_kerja' => fake()->randomElement(TracerStudy::STATUSES),
            'company_name' => fake()->company(),
            'position' => fake()->jobTitle(),
            'salary_range' => fake()->randomElement(TracerStudy::SALARY_RANGES),
            'is_relevant' => fake()->boolean(),
            'tahun_lulus' => fake()->numberBetween(2020, (int) now()->format('Y')),
            'jurusan' => fake()->randomElement(['RPL', 'TKJ', 'TBSM', 'OTKP', 'AKL']),
            'no_wa' => '08'.fake()->numerify('##########'),
            'filled_at' => now(),
        ];
    }
}
