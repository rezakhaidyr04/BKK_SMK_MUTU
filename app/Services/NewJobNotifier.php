<?php

namespace App\Services;

use App\Models\Job;
use App\Models\User;
use App\Notifications\NewJobPosted;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

/**
 * Notifikasi "lowongan baru" ke semua pencari kerja (role umum).
 * Dipakai Company\JobController (perusahaan terverifikasi langsung tayang)
 * dan Admin\JobController (approve / buat langsung aktif).
 * Sinkron + try/catch + chunk agar publish tidak pernah gagal
 * gara-gara notifikasi.
 */
class NewJobNotifier
{
    public static function notifySeekers(Job $job): void
    {
        try {
            User::where('role', 'umum')
                ->chunkById(200, function ($users) use ($job) {
                    Notification::send($users, new NewJobPosted($job));
                });
        } catch (\Throwable $e) {
            Log::warning(
                'Notifikasi lowongan baru #' . $job->id . ' gagal dikirim: ' . $e->getMessage()
            );
        }
    }
}
