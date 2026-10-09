<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        $schedule->call(function () {
            // Hapus file CV generated yang berumur lebih dari 24 jam di storage/app/private/cv-files
            $files = \Illuminate\Support\Facades\Storage::disk('private')->files('cv-files');
            $now = now()->timestamp;

            foreach ($files as $file) {
                if (str_starts_with($file, 'cv-files/generated-cv-')) {
                    $lastModified = \Illuminate\Support\Facades\Storage::disk('private')->lastModified($file);

                    if ($now - $lastModified > 86400) { // 24 jam dalam detik
                        \Illuminate\Support\Facades\Storage::disk('private')->delete($file);
                    }
                }
            }
        })->daily();

        // A4: pengingat tracer study mingguan (Senin 08:00) — hanya umum
        // 30+ hari belum isi. Idempoten: yang sudah isi otomatis terlewati.
        $schedule->command('tracer:remind --days=30')->weeklyOn(1, '8:00');

        // D2: reminder H-1 wawancara setiap pagi. Idempoten alami: target
        // selalu "besok", jadi tiap wawancara hanya tersentuh sekali.
        $schedule->command('interview:remind')->dailyAt('07:00');

        // E1: digest job alert tiap Sabtu pagi. Idempoten via last_sent_at:
        // jendela selalu sejak kirim terakhir (maks 7 hari).
        $schedule->command('job-alert:send')->weeklyOn(6, '9:00');

        // F3: reminder MoU tiap Selasa pagi (disebar dari tracer Senin).
        // Target: verified + expired/≤30 hari + punya akun user.
        $schedule->command('mou:remind --days=30')->weeklyOn(2, '9:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
