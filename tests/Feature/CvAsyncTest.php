<?php

namespace Tests\Feature;

use App\Jobs\GenerateCvJob;
use App\Models\CvFile;
use App\Models\User;
use App\Services\CvBuilderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * M9: generate CV async — dispatch (bukan sync), anti-duplikat,
 * gagal tidak merusak CV existing, sukses menghasilkan file.
 */
class CvAsyncTest extends TestCase
{
    use RefreshDatabase;

    private function umum(): User
    {
        return User::factory()->create(['role' => 'umum', 'email_verified_at' => now()]);
    }

    public function test_m9_request_dispatches_job_without_running_dompdf(): void
    {
        Queue::fake();
        Storage::fake('private');
        $user = $this->umum();

        $this->actingAs($user)->post(route('cv.generate'), [
            'include_skills' => true,
            'include_certificates' => false,
        ])->assertRedirect();

        Queue::assertPushed(GenerateCvJob::class, 1);
        // DomPDF TIDAK jalan di request: belum ada file/baris CV.
        $this->assertEquals(0, CvFile::where('user_id', $user->id)->count());
        $this->assertTrue(Cache::has(CvBuilderService::generatingKey($user->id)));
    }

    public function test_m9_duplicate_request_does_not_dispatch_twice(): void
    {
        Queue::fake();
        Storage::fake('private');
        $user = $this->umum();
        $payload = ['include_skills' => true, 'include_certificates' => false];

        $this->actingAs($user)->post(route('cv.generate'), $payload)->assertRedirect();
        $this->actingAs($user)->post(route('cv.generate'), $payload)
            ->assertRedirect()
            ->assertSessionHas('info');

        Queue::assertPushed(GenerateCvJob::class, 1);
    }

    public function test_m9_failed_job_keeps_existing_cv_and_flags_failed(): void
    {
        Storage::fake('private');
        $user = $this->umum();
        Storage::disk('private')->put('cv-files/lama.pdf', 'lama');
        $existing = CvFile::create(['user_id' => $user->id, 'file_path' => 'cv-files/lama.pdf', 'is_ats_friendly' => true]);
        Cache::put(CvBuilderService::generatingKey($user->id), 'cv-files/baru.pdf', 600);

        // Template tidak ada → DomPDF gagal sebelum menulis apa pun.
        $job = new GenerateCvJob($user->id, ['user' => $user], 'template-tidak-ada', 'cv-files/baru.pdf');
        try {
            $job->handle();
            $this->fail('handle() seharusnya melempar exception untuk template tidak ada.');
        } catch (\Throwable) {
            $job->failed(new \RuntimeException('template tidak ada'));
        }

        // CV lama utuh, flag proses dicabut, flag gagal tampil.
        $this->assertDatabaseHas('cv_files', ['id' => $existing->id, 'file_path' => 'cv-files/lama.pdf']);
        Storage::disk('private')->assertExists('cv-files/lama.pdf');
        Storage::disk('private')->assertMissing('cv-files/baru.pdf');
        $this->assertFalse(Cache::has(CvBuilderService::generatingKey($user->id)));
        $this->assertTrue(Cache::has(CvBuilderService::failedKey($user->id)));

        // Banner gagal tampil di halaman builder.
        $this->actingAs($user)->get(route('cv.builder'))->assertOk()->assertSee('Pembuatan CV gagal');
    }

    public function test_m9_successful_job_creates_cv_and_clears_flag(): void
    {
        Storage::fake('private');
        $user = $this->umum();
        $user->skills()->create(['name' => 'PHP']);
        $this->actingAs($user);

        $service = new CvBuilderService();
        $this->assertSame('queued', $service->generateCv(['include_skills' => true, 'include_certificates' => false]));

        // Driver sync di test = worker inline: file + baris ada, flag bersih.
        $this->assertEquals(1, CvFile::where('user_id', $user->id)->count());
        $file = CvFile::where('user_id', $user->id)->firstOrFail();
        Storage::disk('private')->assertExists($file->file_path);
        $this->assertFalse(Cache::has(CvBuilderService::generatingKey($user->id)));
    }
}
