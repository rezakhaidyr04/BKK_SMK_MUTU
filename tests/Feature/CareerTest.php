<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CareerTest extends TestCase
{
    use RefreshDatabase;

    private function user(): User
    {
        $u = User::factory()->create(['role' => 'umum']);
        $u->forceFill(['email_verified_at' => now()])->save();
        return $u;
    }

    public function test_profile_page_no_longer_has_career_inputs_but_links_cv(): void
    {
        $resp = $this->actingAs($this->user())->get(route('profile.edit'));
        $resp->assertStatus(200);
        $resp->assertSee('Kelola di Pembuat CV', false);
        $resp->assertDontSee('name="preferred_position"', false);
        $resp->assertDontSee('name="education_history"', false);
        $resp->assertDontSee('name="experience_organization"', false);
    }

    public function test_cv_builder_has_single_combined_form(): void
    {
        $resp = $this->actingAs($this->user())->get(route('cv.builder'));
        $resp->assertStatus(200);
        $resp->assertSee('name="preferred_position"', false);
        $resp->assertSee('name="education_history"', false);
        $resp->assertSee('name="experience_organization"', false);
        $resp->assertSee(route('cv.build'), false);
        $resp->assertSee('Simpan & Buat CV PDF', false);
    }

    public function test_career_update_saves_and_returns_to_cv(): void
    {
        $user = $this->user();
        $resp = $this->actingAs($user)->patch(route('career.update'), [
            'preferred_position' => 'Operator Produksi',
            'bio' => 'Lulusan baru siap kerja.',
            'education_history' => "SMK TI Muhammadiyah Cikampek",
            'experience_organization' => 'Magang bengkel',
            'skills' => ['Excel', 'Komunikasi'],
        ]);
        $resp->assertRedirect(route('cv.builder'));
        $fresh = $user->fresh();
        $this->assertSame('Operator Produksi', $fresh->preferred_position);
        $this->assertSame('Magang bengkel', $fresh->experience_organization);
        $this->assertEqualsCanonicalizing(['Excel', 'Komunikasi'], $fresh->skills->pluck('name')->toArray());
        // Akun tidak ikut berubah
        $this->assertNotNull($fresh->email_verified_at);
    }

    public function test_company_cannot_update_career(): void
    {
        $company = User::factory()->create(['role' => 'company']);
        $resp = $this->actingAs($company)->patch(route('career.update'), ['bio' => 'x']);
        $resp->assertRedirect(route('company.profile.edit'));
    }

    public function test_build_saves_career_and_dispatches_pdf(): void
    {
        \Illuminate\Support\Facades\Queue::fake();

        $user = $this->user();
        $resp = $this->actingAs($user)->post(route('cv.build'), [
            'preferred_position' => 'Operator Produksi',
            'bio' => 'Lulusan baru siap kerja.',
            'education_history' => 'SMK TI Muhammadiyah Cikampek',
            'experience_organization' => 'Magang bengkel',
            'skills' => ['Excel'],
            'include_skills' => '1',
            'ats_keywords' => 'teliti, jujur',
        ]);

        $resp->assertRedirect();
        $resp->assertSessionHas('success');
        $fresh = $user->fresh();
        $this->assertSame('Operator Produksi', $fresh->preferred_position);
        $this->assertSame(['Excel'], $fresh->skills->pluck('name')->toArray());
        \Illuminate\Support\Facades\Queue::assertPushed(\App\Jobs\GenerateCvJob::class, 1);
    }

    public function test_build_unchecked_options_mean_false(): void
    {
        \Illuminate\Support\Facades\Queue::fake();

        $user = $this->user();
        // Tanpa centang apa pun → kedua flag false (bukan default true).
        $this->actingAs($user)->post(route('cv.build'), ['bio' => 'Halo']);

        \Illuminate\Support\Facades\Queue::assertPushed(
            \App\Jobs\GenerateCvJob::class,
            fn ($job) => $job->data['include_skills'] === false && $job->data['include_certificates'] === false
        );
    }

    public function test_build_requires_verified_email(): void
    {
        $user = User::factory()->create(['role' => 'umum', 'email_verified_at' => null]);
        $resp = $this->actingAs($user)->post(route('cv.build'), ['bio' => 'x']);
        // Belum verifikasi → dilempar ke halaman verifikasi, data tidak tersimpan.
        $resp->assertRedirect(route('verification.notice'));
        $this->assertNull($user->fresh()->bio);
    }
}
