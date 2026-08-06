<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Application;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition(): array
    {
        return [
            'job_id' => \App\Models\Job::factory(),
            'user_id' => \App\Models\User::factory(),
            'cover_letter' => fake()->paragraph(),
            'attachment_path' => 'applications/' . fake()->uuid() . '.pdf',
            'attachment_name' => 'resume.pdf',
            'attachment_mime' => 'application/pdf',
            'attachment_size' => 102400,
            'status' => 'pending',
            'interview_date' => null,
            'interview_location' => null,
            'interview_type' => null,
            'interview_link' => null,
            'interview_notes' => null,
        ];
    }
}
