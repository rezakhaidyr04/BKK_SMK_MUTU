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
