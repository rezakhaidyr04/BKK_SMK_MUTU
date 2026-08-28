<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CvFile;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CvFile>
 */
class CvFileFactory extends Factory
{
    protected $model = CvFile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'file_path' => 'cv-files/generated-cv-' . fake()->uuid . '.pdf',
            'is_ats_friendly' => true,
        ];
    }
}
