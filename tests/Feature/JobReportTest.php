<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\JobReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Bug "tombol Laporkan tidak terjadi apa-apa": modal scope Alpine salah + error validasi hilang.
 */
class JobReportTest extends TestCase
{
    use RefreshDatabase;

    private function umum(): User
    {
        return User::factory()->create(['role' => 'umum']);
    }

    private function activeJob(): Job
    {
        return Job::factory()->create(['status' => 'active', 'deadline' => now()->addWeek()]);
    }

    public function test_show_page_has_report_modal_and_dispatch_buttons(): void
    {
        $job = $this->activeJob();

        // Guest: modal tetap dirender, tombol jadi link ke login.
        $this->get(route('jobs.show', $job))
            ->assertOk()
            ->assertSee('open-job-report.window', false)
            ->assertSee('x-show="open"', false);

        // Umum login: tombol memicu event window agar scope Alpine tidak putus.
        $this->actingAs($this->umum())->get(route('jobs.show', $job))
            ->assertOk()
            ->assertSee('open-job-report.window', false)
            ->assertSee("dispatch('open-job-report')", false);
    }

    public function test_umum_can_report_active_job(): void
    {
        $user = $this->umum();
        $job = $this->activeJob();

        $response = $this->actingAs($user)->post(route('jobs.report', $job), [
            'reason' => 'penipuan',
            'detail' => 'Perusahaan meminta transfer biaya seragam di muka.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('job_reports', [
            'job_id' => $job->id,
            'user_id' => $user->id,
            'reason' => 'penipuan',
            'status' => 'menunggu',
        ]);
    }

    public function test_validation_failure_reopens_modal_with_inline_errors(): void
    {
        $user = $this->umum();
        $job = $this->activeJob();

        $response = $this->actingAs($user)->post(route('jobs.report', $job), [
            'reason' => '',
            'detail' => 'x',
        ]);

        $response->assertSessionHasErrors(['reason', 'detail']);
        $this->assertDatabaseCount('job_reports', 0);

        $page = $this->actingAs($user)->get(route('jobs.show', $job));

        $page->assertOk()
            ->assertSee('open: true', false)
            ->assertSee('Laporan belum terkirim', false)
            ->assertSee('Pilih alasan pelaporan.', false)
            ->assertSee('Detail minimal 10 karakter agar bisa ditindaklanjuti.', false)
            ->assertDontSee('Lamaran belum terkirim', false);
    }

    public function test_invalid_detail_min_keeps_reason_selection(): void
    {
        $user = $this->umum();
        $job = $this->activeJob();

        $this->actingAs($user)->post(route('jobs.report', $job), [
            'reason' => 'pungutan',
            'detail' => 'pendek',
        ])->assertSessionHasErrors('detail');

        $page = $this->actingAs($user)->get(route('jobs.show', $job));

        $page->assertOk();
        $this->assertMatchesRegularExpression('/<option value="pungutan"\s+selected/', $page->getContent());
    }

    public function test_duplicate_report_is_rejected(): void
    {
        $user = $this->umum();
        $job = $this->activeJob();
        JobReport::factory()->create(['job_id' => $job->id, 'user_id' => $user->id]);

        $this->actingAs($user)->post(route('jobs.report', $job), [
            'reason' => 'lainnya',
            'detail' => 'Laporan kedua yang seharusnya ditolak.',
        ])->assertSessionHas('error');

        $this->assertDatabaseCount('job_reports', 1);
    }

    public function test_inactive_job_cannot_be_reported(): void
    {
        $user = $this->umum();
        $job = Job::factory()->create(['status' => 'closed', 'deadline' => now()->addWeek()]);

        $this->actingAs($user)->post(route('jobs.report', $job), [
            'reason' => 'penipuan',
            'detail' => 'Lowongan sudah ditutup tapi dicoba dilaporkan.',
        ])->assertSessionHas('error');

        $this->assertDatabaseCount('job_reports', 0);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $job = $this->activeJob();

        $this->post(route('jobs.report', $job), [
            'reason' => 'penipuan',
            'detail' => 'Tamu tidak boleh melaporkan lowongan.',
        ])->assertRedirect(route('login'));
    }

    public function test_non_umum_user_gets_forbidden(): void
    {
        $company = User::factory()->create(['role' => 'perusahaan']);
        $job = $this->activeJob();

        $this->actingAs($company)->post(route('jobs.report', $job), [
            'reason' => 'penipuan',
            'detail' => 'Perusahaan tidak boleh melaporkan lowongan orang lain.',
        ])->assertForbidden();

        $this->assertDatabaseCount('job_reports', 0);
    }
}
