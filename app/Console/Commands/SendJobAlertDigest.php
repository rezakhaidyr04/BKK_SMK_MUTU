<?php

namespace App\Console\Commands;

use App\Models\JobAlert;
use App\Notifications\JobAlertDigest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendJobAlertDigest extends Command
{
    protected $signature = 'job-alert:send
                            {--dry-run : Tampilkan target tanpa mengirim email}';

    protected $description = 'Kirim ringkasan mingguan lowongan cocok ke pemilik job alert aktif';

    public function handle(): int
    {
        $isDryRun = (bool) $this->option('dry-run');

        if ($isDryRun) {
            $this->warn('>>> DRY-RUN MODE: tidak ada email yang dikirim. <<<');
            $this->newLine();
        }

        $this->info('Mencari langganan aktif...');

        $alerts = JobAlert::with('user')
            ->active()
            ->whereHas('user', fn ($q) => $q->where('role', 'umum')->where('is_active', true))
            ->latest()
            ->get();

        if ($alerts->isEmpty()) {
            $this->info('Tidak ada langganan aktif.');

            return self::SUCCESS;
        }

        $this->info("Ditemukan {$alerts->count()} langganan.");

        $bar = $this->output->createProgressBar($alerts->count());
        $bar->start();

        $sent = 0;
        $skipped = 0;
        $failed = [];

        foreach ($alerts as $alert) {
            // Jendela: sejak kirim terakhir (maks 7 hari ke belakang).
            $since = $alert->last_sent_at && $alert->last_sent_at->gt(now()->subDays(7))
                ? $alert->last_sent_at
                : now()->subDays(7);

            try {
                $jobs = $alert->matchingJobs($since);

                if ($jobs->isEmpty()) {
                    $alert->update(['last_sent_at' => now()]);
                    $skipped++;
                    $bar->advance();

                    continue;
                }

                if ($isDryRun) {
                    $sent++;
                    $bar->advance();

                    continue;
                }

                $alert->user->notify(new JobAlertDigest($alert, $jobs));
                $alert->update(['last_sent_at' => now()]);
                $sent++;
            } catch (\Throwable $e) {
                $failed[] = $alert->id;
                Log::error("[job-alert:send] Gagal alert #{$alert->id}.", ['exception' => $e->getMessage()]);
                $this->newLine();
                $this->error("  Gagal alert #{$alert->id}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Selesai. Terkirim: {$sent}, tanpa hasil: {$skipped}, gagal: ".count($failed).'.');
        Log::info("[job-alert:send] Selesai. Terkirim: {$sent}, tanpa hasil: {$skipped}, gagal: ".count($failed).'.');

        return count($failed) > 0 ? self::FAILURE : self::SUCCESS;
    }
}
