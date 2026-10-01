<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Job;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Ulasan pencari kerja harus tampil di profil perusahaan yang dituju.
 */
class CompanyReviewTest extends TestCase
{
    use RefreshDatabase;

    private function verifiedUmum(array $over = []): User
    {
        return User::factory()->create(array_merge(['role' => 'umum', 'email_verified_at' => now()], $over));
    }

    private function reviewPayload(array $over = []): array
    {
        return array_merge([
            'rating' => 5,
            'comment' => str_repeat('Pengalaman magang di sini sangat berkesan dan pembimbing ramah. ', 2),
        ], $over);
    }

    public function test_approved_review_shows_on_company_profile(): void
    {
        $companyUser = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->create(['user_id' => $companyUser->id, 'name' => 'PT Tekno Raya']);
        $user = $this->verifiedUmum();
        Review::factory()->create([
            'user_id' => $user->id,
            'company_name' => 'PT Tekno Raya',
            'rating' => 4,
            'comment' => 'Lingkungan kerja nyaman dan teratur.',
            'status' => 'approved',
        ]);

        $this->get(route('companies.show', $company))
            ->assertOk()
            ->assertSee('Ulasan Pencari Kerja')
            ->assertSee('Lingkungan kerja nyaman dan teratur.')
            ->assertSee('Berdasarkan 1 ulasan');
    }

    public function test_review_with_different_case_still_matches_profile(): void
    {
        $companyUser = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->create(['user_id' => $companyUser->id, 'name' => 'PT Tekno Raya']);
        $user = $this->verifiedUmum();

        // Tulis dengan huruf kecil semua — store menyamakan ke nama terdaftar.
        $this->actingAs($user)->post(route('reviews.store'), $this->reviewPayload([
            'company_name' => 'pt tekno raya',
        ]))->assertSessionHas('success');

        $this->assertDatabaseHas('reviews', ['company_name' => 'PT Tekno Raya']);

        $this->get(route('companies.show', $company))
            ->assertOk()
            ->assertSee('Berdasarkan 1 ulasan');
    }

    public function test_review_for_other_company_does_not_leak(): void
    {
        $companyUser = User::factory()->create(['role' => 'company']);
        $companyA = Company::factory()->create(['user_id' => $companyUser->id, 'name' => 'PT Alpha']);
        $companyB = Company::factory()->create(['name' => 'PT Beta']);
        $user = $this->verifiedUmum();
        Review::factory()->create([
            'user_id' => $user->id,
            'company_name' => 'PT Beta',
            'rating' => 5,
            'comment' => 'Sangat recommended untuk fresh graduate.',
            'status' => 'approved',
        ]);

        $this->get(route('companies.show', $companyA))
            ->assertOk()
            ->assertSee('Belum ada ulasan')
            ->assertDontSee('Sangat recommended untuk fresh graduate.');

        // Perusahaan B yang dituju tetap menampilkannya.
        $this->get(route('companies.show', $companyB))
            ->assertOk()
            ->assertSee('Sangat recommended untuk fresh graduate.');
    }

    public function test_review_form_prefills_company_from_profile_button(): void
    {
        $companyUser = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->create(['user_id' => $companyUser->id, 'name' => 'PT Tekno Raya']);
        $user = $this->verifiedUmum();

        $this->actingAs($user)->get(route('reviews.create', ['company' => $company->id]))
            ->assertOk()
            ->assertSee('value="PT Tekno Raya"', false);
    }

    public function test_home_testimonial_shows_reviewer_photo_when_available(): void
    {
        $withPhoto = $this->verifiedUmum(['avatar' => 'avatars/siti.jpg']);
        Review::factory()->create([
            'user_id' => $withPhoto->id,
            'rating' => 5,
            'comment' => 'Fitur pembuat CV-nya luar biasa dan sangat membantu.',
            'status' => 'approved',
            'featured' => true,
        ]);
        $noPhoto = $this->verifiedUmum(['avatar' => null]);
        Review::factory()->create([
            'user_id' => $noPhoto->id,
            'rating' => 5,
            'comment' => 'Pelayanan cepat dan informatif sekali.',
            'status' => 'approved',
        ]);

        $response = $this->get(route('home'))->assertOk();
        // Foto asli untuk yang punya avatar...
        $response->assertSee('avatars/siti.jpg');
        // ...inisial tetap untuk yang tidak punya foto.
        $response->assertSee('testimonial-avatar', false);
    }

    public function test_job_page_sidebar_shows_company_rating(): void
    {
        $companyUser = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->create(['user_id' => $companyUser->id, 'name' => 'PT Tekno Raya']);
        $job = Job::factory()->create([
            'company_id' => $company->id,
            'company_name' => $company->name,
            'status' => 'active',
            'deadline' => now()->addWeek(),
        ]);
        $user = $this->verifiedUmum();
        Review::factory()->create([
            'user_id' => $user->id,
            'company_name' => 'PT Tekno Raya',
            'rating' => 5,
            'comment' => 'Proses rekrutmen cepat dan jelas sekali.',
            'status' => 'approved',
        ]);

        $this->get(route('jobs.show', $job))
            ->assertOk()
            ->assertSee('5,0')
            ->assertSee('(1 ulasan)');
    }
}
