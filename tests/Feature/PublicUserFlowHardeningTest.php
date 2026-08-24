<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicUserFlowHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_jobseeker_can_complete_core_public_flow(): void
    {
        $registerResponse = $this->post('/register', [
            'name' => 'Public Jobseeker',
            'email' => 'public-jobseeker@example.com',
            'role' => 'jobseeker',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $registerResponse->assertRedirect('/dashboard');

        $user = User::where('email', 'public-jobseeker@example.com')->firstOrFail();
        $this->assertSame('jobseeker', $user->role);
        $this->assertNull($user->student);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk();

        $this->actingAs($user)
            ->get('/profile')
            ->assertOk();

        $this->actingAs($user)
            ->patch('/profile', [
                'name' => 'Updated Public Jobseeker',
                'email' => 'public-jobseeker@example.com',
                'phone' => '081234567890',
                'bio' => 'Pencari kerja umum tanpa NISN.',
                'education_history' => 'SMA Negeri 1',
                'experience_organization' => 'Relawan acara komunitas',
                'address' => 'Karawang',
            ])
            ->assertRedirect('/profile');

        $user->refresh()->load('student');
        $this->assertSame('jobseeker', $user->role);
        $this->assertNotNull($user->student);
        $this->assertNull($user->student->nisn);
        $this->assertNull($user->student->graduation_year);

        $this->actingAs($user)
            ->get('/cv/builder')
            ->assertOk();

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

        $this->actingAs($user)
            ->get('/jobs')
            ->assertOk();

        $this->actingAs($user)
            ->post(route('jobs.apply', $job), [
                'cover_letter' => str_repeat('Saya siap belajar dan bekerja dengan baik. ', 4),
            ])
            ->assertRedirect(route('jobs.show', $job));

        $this->assertDatabaseHas('applications', [
            'user_id' => $user->id,
            'job_id' => $job->id,
            'status' => 'submitted',
        ]);
    }

    public function test_legacy_jobseeker_with_student_profile_keeps_access_to_profile_cv_and_application(): void
    {
        $user = User::factory()->create([
            'role' => 'jobseeker',
            'email_verified_at' => now(),
        ]);

        $user->student()->create([
            'nisn' => '1234567890',
            'major' => 'Rekayasa Perangkat Lunak',
            'graduation_year' => 2024,
            'address' => 'Cikampek',
            'education_history' => 'SMK MUTU Cikampek',
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

        $this->actingAs($user)->get('/dashboard')->assertOk();
        $this->actingAs($user)->get('/profile')->assertOk();
        $this->actingAs($user)->get('/cv/builder')->assertOk();
        $this->actingAs($user)->get('/jobs')->assertOk();

        $this->actingAs($user)
            ->post(route('jobs.apply', $job), [
                'cover_letter' => str_repeat('Saya memiliki pengalaman profil legacy yang tetap aman. ', 3),
            ])
            ->assertRedirect(route('jobs.show', $job));

        $application = Application::where('user_id', $user->id)->first();
        $this->assertNotNull($application);
    }

    public function test_role_isolation_for_jobseeker_company_and_teacher(): void
    {
        $jobseeker = User::factory()->create([
            'role' => 'jobseeker',
            'email_verified_at' => now(),
        ]);

        $companyUser = User::factory()->create([
            'role' => 'company',
            'email_verified_at' => now(),
        ]);

        Company::factory()->create([
            'user_id' => $companyUser->id,
            'is_verified' => true,
            'verification_status' => 'verified',
        ]);

        $teacher = User::factory()->create([
            'role' => 'teacher',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($jobseeker)->get('/admin/users')->assertStatus(403);
        $this->actingAs($jobseeker)->get('/company/jobs')->assertStatus(403);

        $this->actingAs($companyUser)->get('/admin/users')->assertStatus(403);
        $this->actingAs($companyUser)->patch('/profile', [
            'name' => 'Should Redirect',
            'email' => $companyUser->email,
        ])->assertRedirect(route('company.profile.edit'));

        $this->actingAs($teacher)->get('/admin/users')->assertStatus(403);
        $this->actingAs($teacher)->get('/dashboard')->assertOk();
    }
}
