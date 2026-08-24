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

    public $userId;
    public $data;
    public $template;
    public $fileName;

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
        $pdf = PDF::loadView('cv.templates.' . $this->template, $this->data)
            ->setPaper('a4', 'portrait');

        Storage::disk('private')->put($this->fileName, $pdf->output());

        CvFile::create([
            'user_id' => $this->userId,
            'file_path' => $this->fileName,
            'is_ats_friendly' => true,
        ]);
    }
}
