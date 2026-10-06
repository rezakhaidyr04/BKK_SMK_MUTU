<?php

namespace App\Services;

use App\Jobs\SendJobNotificationsChunk;
use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Notifikasi "lowongan baru" ke semua pencari kerja (role umum).
 * Dipakai Company\JobController (perusahaan terverifikasi langsung tayang)
 * dan Admin\JobController (approve / buat langsung aktif).
 *
 * M1: TIDAK lagi insert sinkron di HTTP request. Dispatch chunk-job
 * antrean (±200 penerima/job) SETELAH job tersimpan (after_commit pada
 * driver database). Penerima + template identik dengan perilaku lama.
 */
class NewJobNotifier
{
    public static function notifySeekers(Job $job): void
    {
        try {
            $minId = (int) User::where('role', 'umum')->min('id');
            $maxId = (int) User::where('role', 'umum')->max('id');

            if ($minId === 0 || $maxId === 0) {
                return;
            }

            $perJob = 200;
            for ($start = $minId; $start <= $maxId; $start += $perJob) {
                SendJobNotificationsChunk::dispatch($job->id, $start, min($start + $perJob - 1, $maxId));
            }
        } catch (\Throwable $e) {
            Log::warning(
                'Antrean notifikasi lowongan baru #' . $job->id . ' gagal dijadwalkan: ' . $e->getMessage()
            );
        }
    }
}
