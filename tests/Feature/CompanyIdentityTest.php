<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * M5: company_id kanonis — dua perusahaan bernama sama tetap entitas
 * berbeda; ulasan tidak tercampur; asosiasi tidak boleh NULL.
 */
class CompanyIdentityTest extends TestCase
{
    use RefreshDatabase;

    private function verifiedUmum(): User
    {
        return User::factory()->create(['role' => 'umum', 'email_verified_at' => now()]);
    }

    private function reviewPayload(string $company): array
    {
        return [
            'rating' => 5,
            'comment' => str_repeat('Lingkungan kerja nyaman dan pembimbing ramah. ', 2),
            'company_name' => $company,
        ];
    }

    public function test_m5_same_name_companies_keep_separate_reviews(): void
    {
        $userA = User::factory()->create(['role' => 'company']);
        $companyA = Company::factory()->create(['user_id' => $userA->id, 'name' => 'PT Kembar']);
        $userB = User::factory()->create(['role' => 'company']);
        $companyB = Company::factory()->create(['user_id' => $userB->id, 'name' => 'PT Kembar']);
        $this->assertNotEquals($companyA->id, $companyB->id);

        $reviewer = $this->verifiedUmum();
        $this->actingAs($reviewer)->post(route('reviews.store'), $this->reviewPayload('PT Kembar'))
            ->assertSessionHas('success');

        $review = Review::where('user_id', $reviewer->id)->firstOrFail();
        // company_id selalu terisi (L5: tidak boleh NULL).
        $this->assertNotNull($review->company_id);

        // Ulasan tampil di profil A (pemilik company_id), TIDAK di B.
        $this->get(route('companies.show', $companyA))->assertOk()->assertSee($review->comment);
        $this->get(route('companies.show', $companyB))->assertOk()->assertDontSee($review->comment);
    }

    public function test_m5_duplicate_review_blocked_per_company_id(): void
    {
        $userA = User::factory()->create(['role' => 'company']);
        Company::factory()->create(['user_id' => $userA->id, 'name' => 'PT Tunggal']);

        $reviewer = $this->verifiedUmum();
        $this->actingAs($reviewer)->post(route('reviews.store'), $this->reviewPayload('PT Tunggal'))
            ->assertSessionHas('success');
        $this->actingAs($reviewer)->post(route('reviews.store'), $this->reviewPayload('PT Tunggal'))
            ->assertSessionHas('error');
        $this->assertEquals(1, Review::where('user_id', $reviewer->id)->count());
    }

    public function test_m5_review_requires_registered_company(): void
    {
        $reviewer = $this->verifiedUmum();
        // Tanpa company_name → error validasi (asosiasi tidak boleh NULL).
        $this->actingAs($reviewer)->post(route('reviews.store'), [
            'rating' => 5,
            'comment' => str_repeat('Bagus sekali dan sangat membantu. ', 3),
        ])->assertSessionHasErrors(['company_name']);
        // Nama tidak terdaftar → error validasi.
        $this->actingAs($reviewer)->post(route('reviews.store'), $this->reviewPayload('PT Hantu Tidak Ada'))
            ->assertSessionHasErrors(['company_name']);
        $this->assertEquals(0, Review::where('user_id', $reviewer->id)->count());
    }
}
