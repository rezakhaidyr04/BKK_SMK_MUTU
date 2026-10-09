<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Notifications\InterviewReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RemindInterviews extends Command
{
    protected $signature = 'interview:remind
                            {--dry-run : Tampilkan target tanpa mengirim notifikasi}';

    protected $description = 'Kirim pengingat H-1 wawancara ke pelamar yang belum/sudah konfirmasi';

    public function handle(): int
    {
        $isDryRun = (bool) $this->option('dry-run');

        // Jendela "besok" penuh hari ini+1 (00:00–23:59) zona aplikasi.
        $start = now()->addDay()->startOfDay();
        $end = now()->addDay()->endOfDay();

        if ($isDryRun) {
            $this->warn('>>> DRY-RUN MODE: tidak ada notifikasi yang dikirim. <<<');
            $this->newLine();
        }

        $this->info("Mencari wawancara {$start->format('d M Y')} yang menunggu/dikonfirmasi...");

        $applications = Application::with(['job', 'user'])
            ->where('status', 'interviewed')
            ->whereIn('interview_status', ['menunggu', 'dikonfirmasi'])
            ->whereBetween('interview_date', [$start, $end])
            ->whereHas('user')
            ->get();

        if ($applications->isEmpty()) {
            $this->info('Tidak ada target reminder H-1.');
            Log::info('[interview:remind] Tidak ada target.');

            return self::SUCCESS;
        }

        $this->info("Ditemukan {$applications->count()} target.");

        if ($isDryRun) {
            $this->table(
                ['ID', 'Pelamar', 'Lowongan', 'Jadwal', 'Konfirmasi'],
                $applications->map(fn (Application $a) => [
                    $a->id,
                    $a->user?->name ?? '-',
                    $a->job?->title ?? '-',
                    optional($a->interview_date)->format('d/m/Y H:i'),
                    $a->interview_status,
                ])->toArray()
            );

            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($applications->count());
        $bar->start();

        $sent = 0;
        $failed = [];

        foreach ($applications as $application) {
            try {
                $application->user->notify(new InterviewReminder($application));
                $sent++;
            } catch (\Throwable $e) {
                $failed[] = $application->id;
                Log::error("[interview:remind] Gagal lamaran #{$application->id}.", ['exception' => $e->getMessage()]);
                $this->newLine();
                $this->error("  Gagal lamaran #{$application->id}: {$e->getMessage()}");
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Selesai. Terkirim: {$sent}, Gagal: ".count($failed).'.');
        Log::info("[interview:remind] Selesai. Terkirim: {$sent}, Gagal: ".count($failed).'.');

        return count($failed) > 0 ? self::FAILURE : self::SUCCESS;
    }
}
