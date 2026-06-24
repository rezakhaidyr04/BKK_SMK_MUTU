<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class JobApplicationAttachmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_apply_with_attachment(): void
    {
        Storage::fake('public');

        $student = User::factory()->create([
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        $companyUser = User::factory()->create([
            'role' => 'company',
            'email_verified_at' => now(),
        ]);

        $company = Company::factory()->create([
            'user_id' => $companyUser->id,
            'is_verified' => true,
            'verification_status' => 'verified',
        ]);

        $job = Job::factory()->create([
            'company_id' => $company->id,
        ]);

        $response = $this->actingAs($student)->post(route('jobs.apply', $job), [
            'cover_letter' => str_repeat('Saya tertarik melamar posisi ini. ', 5),
            'attachment' => UploadedFile::fake()->create('cv-siswa.pdf', 400, 'application/pdf'),
        ]);

        $response->assertRedirect(route('jobs.show', $job));

        $application = \App\Models\Application::first();
        $this->assertNotNull($application);
        $this->assertNotNull($application->attachment_path);
        Storage::disk('public')->assertExists($application->attachment_path);
    }
}
