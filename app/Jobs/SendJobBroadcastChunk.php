<?php

namespace App\Jobs;

use App\Mail\JobBroadcastMail;
use App\Models\Job;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Satu potong broadcast email lowongan (±50 penerima).
 *
 * Dipakai Admin\JobController::broadcast() yang memecah 10.000+ pencari
 * kerja menjadi banyak chunk-job agar:
 * - request HTTP langsung kembali (tidak hang berjam-jam),
 * - memori kecil (hanya ±50 user per job),
 * - satu chunk gagal tidak menggagalkan sisanya (retry per-chunk),
 * - durasi chunk (±50 SMTP ≈ 1-3 menit) selalu di bawah retry_after
 *   antrean database (400 dtk) sehingga tidak ada eksekusi ganda.
 */
class SendJobBroadcastChunk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
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
            Log::warning('SendJobBroadcastChunk: lowongan #' . $this->jobId . ' sudah dihapus, chunk dilewati.');
            return;
        }

        User::where('role', 'umum')
            ->where('is_active', true)
            ->whereBetween('id', [$this->minId, $this->maxId])
            ->select(['id', 'name', 'email'])
            ->orderBy('id')
            ->chunkById(100, function ($users) use ($job) {
                foreach ($users as $user) {
                    try {
                        Mail::to($user->email)->send(new JobBroadcastMail($job, $user));
                    } catch (\Throwable $e) {
                        Log::warning('Broadcast lowongan #' . $job->id . ' gagal ke ' . $user->email . ': ' . $e->getMessage());
                    }
                }
            }, 'id');
    }

    public function failed(\Throwable $exception): void
    {
        Log::warning('SendJobBroadcastChunk failed', [
            'job_id' => $this->jobId,
            'range' => [$this->minId, $this->maxId],
            'error' => $exception->getMessage(),
        ]);
    }
}
