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

    public function test_new_umum_user_can_complete_core_public_flow(): void
    {
        $registerResponse = $this->post('/register', [
            'name' => 'Pengguna Umum',
            'email' => 'public-umum@example.com',
            'role' => 'umum',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $registerResponse->assertRedirect('/dashboard');

        $user = User::where('email', 'public-umum@example.com')->firstOrFail();
        $this->assertSame('umum', $user->role);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk();

        $this->actingAs($user)
            ->get('/profile')
            ->assertOk();

        $this->actingAs($user)
            ->patch('/profile', [
                'name' => 'Pengguna Umum Diperbarui',
                'email' => 'public-umum@example.com',
                'phone' => '081234567890',
                'bio' => 'Pencari kerja umum.',
                'education_history' => 'SMA Negeri 1',
                'experience_organization' => 'Relawan acara komunitas',
                'address' => 'Karawang',
            ])
            ->assertRedirect('/profile');

        $user->refresh();
        $this->assertSame('umum', $user->role);
        $this->assertSame('SMA Negeri 1', $user->education_history);
        $this->assertSame('Relawan acara komunitas', $user->experience_organization);
        $this->assertSame('Karawang', $user->address);

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

    public function test_profile_fields_can_be_cleared_after_being_filled(): void
    {
        $user = User::factory()->create([
            'role' => 'umum',
            'email_verified_at' => now(),
            'linkedin_url' => 'https://linkedin.com/in/lama',
            'education_history' => 'SMK MUTU Cikampek',
        ]);

        $this->actingAs($user)
            ->patch('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'linkedin_url' => '',
                'education_history' => '',
            ])
            ->assertRedirect('/profile');

        $user->refresh();
        $this->assertNull($user->linkedin_url);
        $this->assertNull($user->education_history);
    }

    public function test_role_isolation_for_umum_and_company(): void
    {
        $umum = User::factory()->create([
            'role' => 'umum',
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

        $this->actingAs($umum)->get('/admin/users')->assertStatus(403);
        $this->actingAs($umum)->get('/company/jobs')->assertStatus(403);

        $this->actingAs($companyUser)->get('/admin/users')->assertStatus(403);
        $this->actingAs($companyUser)->patch('/profile', [
            'name' => 'Should Redirect',
            'email' => $companyUser->email,
        ])->assertRedirect(route('company.profile.edit'));
    }
}
