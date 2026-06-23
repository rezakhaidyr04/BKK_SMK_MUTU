<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'name' => fake()->company(),
            'industry' => fake()->word(),
            'description' => fake()->paragraph(),
            'logo' => null,
            'website' => fake()->url(),
            'address' => fake()->address(),
            'is_verified' => false,
        ];
    }
}
