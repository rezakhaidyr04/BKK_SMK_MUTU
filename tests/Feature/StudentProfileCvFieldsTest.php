<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentProfileCvFieldsTest extends TestCase
{
    use RefreshDatabase;

    public function test_jobseeker_profile_can_store_cv_fields(): void
    {
        $user = User::factory()->create(['role' => 'jobseeker']);
        $user->student()->create(['nisn' => '1234567890']);

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'phone' => '081234567890',
                'bio' => 'Saya adalah siswa yang antusias',
                'linkedin_url' => 'https://linkedin.com/in/budi',
                'portfolio_url' => 'https://budi.dev',
                'preferred_position' => 'Web Developer',
                'education_history' => "SD Negeri 1\nSMP Negeri 2\nSMK MUTU Cikampek",
                'experience_organization' => "Magang di toko online\nKetua OSIS",
                'birth_place' => 'Cikampek',
                'birth_date' => '2007-05-10',
                'gender' => 'Laki-laki',
                'address' => 'Cikampek, Jawa Barat',
                'major' => 'Teknik Informatika',
                'graduation_year' => '2026',
            ]);

        $response->assertRedirect('/profile');
        $user->refresh();
        $user->load('student');

        $this->assertSame('https://linkedin.com/in/budi', $user->student->linkedin_url);
        $this->assertSame('https://budi.dev', $user->student->portfolio_url);
        $this->assertSame('Web Developer', $user->student->preferred_position);
        $this->assertSame("SD Negeri 1\nSMP Negeri 2\nSMK MUTU Cikampek", $user->student->education_history);
        $this->assertSame("Magang di toko online\nKetua OSIS", $user->student->experience_organization);
        $this->assertSame('Cikampek', $user->student->birth_place);
        $this->assertSame('2007-05-10', $user->student->birth_date);
        $this->assertSame('Laki-laki', $user->student->gender);
    }
}
