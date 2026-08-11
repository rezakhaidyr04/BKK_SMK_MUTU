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
            'user_id'             => \App\Models\User::factory(),
            'name'                => fake()->company(),
            'industry'            => fake()->word(),
            'description'         => fake()->paragraph(),
            'website'             => fake()->url(),
            'address'             => fake()->address(),
            'is_verified'         => false,
            'verification_status' => 'pending',
        ];
    }

    /** Company yang belum memiliki akun user (dibuat admin) */
    public function withoutAccount(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
        ]);
    }

    /** Company yang sudah approved/verified */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_verified'         => true,
            'verification_status' => 'verified',
        ]);
    }
}
