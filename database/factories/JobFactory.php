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
        // Lokasi nasional: sampel acak dari master wilayah agar data
        // dev/testing tersebar, bukan hanya satu daerah.
        $provinces = \App\Support\IndonesiaRegions::provinces();
        $province = $provinces ? $provinces[array_rand($provinces)] : null;
        $cities = $province ? \App\Support\IndonesiaRegions::citiesFor($province) : [];
        $city = $cities ? $cities[array_rand($cities)] : null;
        $districts = $province && $city ? \App\Support\IndonesiaRegions::districtsFor($province, $city) : [];
        $district = $districts ? $districts[array_rand($districts)] : null;

        return [
            'company_id' => \App\Models\Company::factory(),
            'title' => fake()->jobTitle(),
            'position' => fake()->jobTitle(),
            'location' => $city && $province ? $city.', '.$province : fake()->city(),
            'province' => $province,
            'city' => $city,
            'district' => $district,
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
