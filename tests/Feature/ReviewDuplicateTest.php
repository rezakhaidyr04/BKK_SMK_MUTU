<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Anti-spam ulasan: satu pengguna satu ulasan per perusahaan.
 */
class ReviewDuplicateTest extends TestCase
{
    use RefreshDatabase;

    private function verifiedUmum(): User
    {
        return User::factory()->create(['role' => 'umum', 'email_verified_at' => now()]);
    }

    private function payload(array $over = []): array
    {
        return array_merge([
            'rating' => 5,
            'comment' => str_repeat('Lingkungan kerja nyaman dan pembimbing ramah. ', 2),
        ], $over);
    }

    public function test_second_review_for_same_company_rejected(): void
    {
        $companyUser = User::factory()->create(['role' => 'company']);
        Company::factory()->create(['user_id' => $companyUser->id, 'name' => 'PT Duplikat']);
        $user = $this->verifiedUmum();

        $this->actingAs($user)->post(route('reviews.store'), $this->payload([
            'company_name' => 'PT Duplikat',
        ]))->assertSessionHas('success');

        $this->actingAs($user)->post(route('reviews.store'), $this->payload([
            'company_name' => 'PT Duplikat',
        ]))->assertSessionHas('error');

        $this->assertEquals(1, Review::where('user_id', $user->id)->count());
    }

    public function test_reviews_for_different_companies_allowed(): void
    {
        $companyUser = User::factory()->create(['role' => 'company']);
        Company::factory()->create(['user_id' => $companyUser->id, 'name' => 'PT Satu']);
        Company::factory()->create(['name' => 'PT Dua']);
        $user = $this->verifiedUmum();

        $this->actingAs($user)->post(route('reviews.store'), $this->payload([
            'company_name' => 'PT Satu',
        ]))->assertSessionHas('success');

        $this->actingAs($user)->post(route('reviews.store'), $this->payload([
            'company_name' => 'PT Dua',
        ]))->assertSessionHas('success');

        $this->assertEquals(2, Review::where('user_id', $user->id)->count());
    }

    public function test_review_links_company_id_when_name_matches(): void
    {
        $companyUser = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->create(['user_id' => $companyUser->id, 'name' => 'PT Taut']);
        $user = $this->verifiedUmum();

        $this->actingAs($user)->post(route('reviews.store'), $this->payload([
            'company_name' => 'pt taut',
        ]))->assertSessionHas('success');

        $review = Review::where('user_id', $user->id)->firstOrFail();
        $this->assertEquals($company->id, $review->company_id);
        $this->assertEquals('PT Taut', $review->company_name);
    }

    public function test_company_cannot_bookmark_job(): void
    {
        $companyUser = User::factory()->create(['role' => 'company', 'email_verified_at' => now()]);
        $job = \App\Models\Job::factory()->create(['status' => 'active', 'deadline' => now()->addWeek()]);

        $this->actingAs($companyUser)->post(route('jobs.bookmark', $job))->assertForbidden();
        $this->assertDatabaseMissing('bookmarks', ['user_id' => $companyUser->id]);
    }

    public function test_cv_builder_form_has_no_dummy_prefill(): void
    {
        $user = $this->verifiedUmum();

        $resp = $this->actingAs($user)->get(route('cv.builder'));
        $resp->assertOk();
        $resp->assertDontSee('Frontend Development, HTML', false);
        $resp->assertDontSee('mengoordinasikan komunikasi publik', false);
        $resp->assertDontSee('Frontend Developer', false);
    }
}
