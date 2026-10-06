<?php

namespace App\Jobs;

use App\Models\Job;
use App\Models\User;
use App\Notifications\NewJobPosted;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

/**
 * M1: satu potong notifikasi "lowongan baru" (±200 penerima).
 *
 * Dipakai NewJobNotifier yang memecah 10.000+ pencari kerja menjadi
 * banyak chunk-job agar publish/approve TIDAK insert 10.000 baris
 * di HTTP request (hang/timeout di shared hosting).
 *
 * Idempoten: lewati user yang sudah punya notifikasi new_job untuk
 * lowongan ini (aman terhadap retry job / publish ulang).
 * Template + penerima (role umum) identik dengan perilaku lama.
 */
class SendJobNotificationsChunk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 300;

    public function __construct(
        public int $jobId,
        public int $minId,
        public int $maxId,
    ) {}

    public function backoff(): array
    {
        return [60, 300];
    }

    public function handle(): void
    {
        $job = Job::find($this->jobId);
        if (! $job) {
            Log::warning('SendJobNotificationsChunk: lowongan #' . $this->jobId . ' sudah dihapus, chunk dilewati.');
            return;
        }

        // User yang sudah ternotifikasi untuk job ini (retry-safe).
        $already = DB::table('notifications')
            ->where('type', NewJobPosted::class)
            ->where('notifiable_type', User::class)
            ->where('data->job_id', $this->jobId)
            ->pluck('notifiable_id')
            ->all();

        User::where('role', 'umum')
            ->whereBetween('id', [$this->minId, $this->maxId])
            ->when(! empty($already), fn ($q) => $q->whereNotIn('id', $already))
            ->select(['id'])
            ->orderBy('id')
            ->chunkById(200, function ($users) use ($job) {
                Notification::send($users, new NewJobPosted($job));
            }, 'id');
    }

    public function failed(\Throwable $exception): void
    {
        Log::warning('SendJobNotificationsChunk failed', [
            'job_id' => $this->jobId,
            'range' => [$this->minId, $this->maxId],
            'error' => $exception->getMessage(),
        ]);
    }
}
