<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Notifications\MouExpiring;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RemindMouExpiry extends Command
{
    protected $signature = 'mou:remind
                            {--dry-run : Tampilkan target tanpa mengirim notifikasi}
                            {--days=30 : Ambang hari kedaluwarsa}';

    protected $description = 'Ingatkan perusahaan terverifikasi yang MoU-nya kedaluwarsa ≤30 hari';

    public function handle(): int
    {
        $isDryRun = (bool) $this->option('dry-run');
        $days = max(1, (int) $this->option('days'));

        if ($isDryRun) {
            $this->warn('>>> DRY-RUN MODE: tidak ada notifikasi yang dikirim. <<<');
            $this->newLine();
        }

        $this->info("Mencari MoU kedaluwarsa dalam {$days} hari (termasuk yang baru lewat)...");

        // Verified + punya tanggal + kedaluwarsa ≤ ambang ATAU baru lewat ≤ ambang.
        // Batas bawah simetris: yang mati lebih lama dari ambang tidak di-spam tiap minggu.
        $companies = Company::with('user')
            ->where('verification_status', 'verified')
            ->whereNotNull('mou_expires_at')
            ->where('mou_expires_at', '<=', now()->addDays($days))
            ->where('mou_expires_at', '>=', now()->subDays($days))
            ->whereHas('user')
            ->get();

        if ($companies->isEmpty()) {
            $this->info('Tidak ada target MoU.');
            Log::info('[mou:remind] Tidak ada target.');

            return self::SUCCESS;
        }

        $this->info("Ditemukan {$companies->count()} perusahaan.");

        if ($isDryRun) {
            $this->table(
                ['ID', 'Perusahaan', 'No. MoU', 'Kedaluwarsa', 'Status'],
                $companies->map(fn (Company $c) => [
                    $c->id,
                    $c->name,
                    $c->mou_number ?? '-',
                    optional($c->mou_expires_at)->format('d/m/Y'),
                    $c->mouExpired() ? 'kedaluwarsa' : '≤'.$days.' hari',
                ])->toArray()
            );

            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($companies->count());
        $bar->start();

        $sent = 0;
        $failed = [];

        foreach ($companies as $company) {
            try {
                $company->user->notify(new MouExpiring($company));
                $sent++;
            } catch (\Throwable $e) {
                $failed[] = $company->id;
                Log::error("[mou:remind] Gagal perusahaan #{$company->id}.", ['exception' => $e->getMessage()]);
                $this->newLine();
                $this->error("  Gagal perusahaan #{$company->id}: {$e->getMessage()}");
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Selesai. Terkirim: {$sent}, Gagal: ".count($failed).'.');
        Log::info("[mou:remind] Selesai. Terkirim: {$sent}, Gagal: ".count($failed).'.');

        return count($failed) > 0 ? self::FAILURE : self::SUCCESS;
    }
}
