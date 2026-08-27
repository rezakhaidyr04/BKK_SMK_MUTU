<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileCvFieldsTest extends TestCase
{
    use RefreshDatabase;

    public function test_umum_profile_can_store_cv_fields(): void
    {
        $user = User::factory()->create(['role' => 'umum']);

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'phone' => '081234567890',
                'bio' => 'Saya adalah pencari kerja yang antusias',
                'linkedin_url' => 'https://linkedin.com/in/budi',
                'portfolio_url' => 'https://budi.dev',
                'preferred_position' => 'Web Developer',
                'education_history' => "SD Negeri 1\nSMP Negeri 2\nSMK MUTU Cikampek",
                'experience_organization' => "Magang di toko online\nKetua OSIS",
                'birth_place' => 'Cikampek',
                'birth_date' => '2007-05-10',
                'gender' => 'Laki-laki',
                'address' => 'Cikampek, Jawa Barat',
            ]);

        $response->assertRedirect('/profile');
        $user->refresh();

        $this->assertSame('https://linkedin.com/in/budi', $user->linkedin_url);
        $this->assertSame('https://budi.dev', $user->portfolio_url);
        $this->assertSame('Web Developer', $user->preferred_position);
        $this->assertSame("SD Negeri 1\nSMP Negeri 2\nSMK MUTU Cikampek", $user->education_history);
        $this->assertSame("Magang di toko online\nKetua OSIS", $user->experience_organization);
        $this->assertSame('Cikampek', $user->birth_place);
        $this->assertSame('2007-05-10', $user->birth_date->toDateString());
        $this->assertSame('Laki-laki', $user->gender);
    }
}
