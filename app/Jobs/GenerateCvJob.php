<?php

namespace App\Jobs;

use App\Models\CvFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use PDF;

class GenerateCvJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    public $userId;
    public $data;
    public $template;
    public $fileName;

    public function backoff(): array
    {
        return [60, 120, 180];
    }

    /**
     * Create a new job instance.
     */
    public function __construct($userId, $data, $template, $fileName)
    {
        $this->userId = $userId;
        $this->data = $data;
        $this->template = $template;
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Idempotency: jangan duplicate jika retry
        $exists = CvFile::where('user_id', $this->userId)
            ->where('file_path', $this->fileName)
            ->exists();
        if ($exists) {
            \Illuminate\Support\Facades\Cache::forget(\App\Services\CvBuilderService::generatingKey($this->userId));
            return;
        }

        $pdf = PDF::loadView('cv.templates.' . $this->template, $this->data)
            ->setPaper('a4', 'portrait');

        Storage::disk('private')->put($this->fileName, $pdf->output());

        CvFile::firstOrCreate(
            ['user_id' => $this->userId, 'file_path' => $this->fileName],
            ['is_ats_friendly' => true]
        );

        // M9: sukses — flag proses dicabut (file lama TIDAK dihapus).
        \Illuminate\Support\Facades\Cache::forget(\App\Services\CvBuilderService::generatingKey($this->userId));
    }

    public function failed(\Throwable $exception): void
    {
        // M9: gagal permanen — cabut flag proses, tandai gagal agar user
        // diberi tahu (CV existing tidak disentuh).
        \Illuminate\Support\Facades\Cache::forget(\App\Services\CvBuilderService::generatingKey($this->userId));
        \Illuminate\Support\Facades\Cache::put(
            \App\Services\CvBuilderService::failedKey($this->userId),
            $this->fileName,
            \App\Services\CvBuilderService::GENERATING_TTL
        );

        \Illuminate\Support\Facades\Log::warning('GenerateCvJob failed', [
            'user_id' => $this->userId,
            'file' => $this->fileName,
            'error' => $exception->getMessage(),
        ]);
    }
}
