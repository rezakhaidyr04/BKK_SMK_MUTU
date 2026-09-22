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
            return;
        }

        $pdf = PDF::loadView('cv.templates.' . $this->template, $this->data)
            ->setPaper('a4', 'portrait');

        Storage::disk('private')->put($this->fileName, $pdf->output());

        CvFile::firstOrCreate(
            ['user_id' => $this->userId, 'file_path' => $this->fileName],
            ['is_ats_friendly' => true]
        );
    }

    public function failed(\Throwable $exception): void
    {
        \Illuminate\Support\Facades\Log::warning('GenerateCvJob failed', [
            'user_id' => $this->userId,
            'file' => $this->fileName,
            'error' => $exception->getMessage(),
        ]);
    }
}
