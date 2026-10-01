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
            'cover_letter' => null,
            'cover_letter_path' => 'cover_letters/' . fake()->uuid() . '.pdf',
            'cover_letter_name' => 'surat-lamaran.pdf',
            'cover_letter_mime' => 'application/pdf',
            'cover_letter_size' => 102400,
            'attachment_path' => 'applications/' . fake()->uuid() . '.pdf',
            'attachment_name' => 'resume.pdf',
            'attachment_mime' => 'application/pdf',
            'attachment_size' => 102400,
            'skck_path' => null,
            'skck_name' => null,
            'skck_mime' => null,
            'skck_size' => null,
            'status' => 'submitted',
            'interview_date' => null,
            'interview_location' => null,
            'interview_type' => null,
            'interview_link' => null,
            'interview_notes' => null,
        ];
    }
}
