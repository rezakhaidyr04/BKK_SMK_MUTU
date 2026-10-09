<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\TracerReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RemindTracerStudy extends Command
{
    protected $signature = 'tracer:remind
                            {--dry-run : Tampilkan target tanpa mengirim notifikasi}
                            {--days=30 : Umur akun minimum (hari) agar diingatkan}';

    protected $description = 'Kirim pengingat tracer study ke pengguna umum yang belum mengisi';

    public function handle(): int
    {
        $isDryRun = (bool) $this->option('dry-run');
        $days = max(0, (int) $this->option('days'));
        $cutoff = now()->subDays($days);

        if ($isDryRun) {
            $this->warn('>>> DRY-RUN MODE: tidak ada notifikasi yang dikirim. <<<');
            $this->newLine();
        }

        $this->info("Mencari pengguna umum terdaftar sebelum {$cutoff->format('d M Y')} tanpa tracer terisi...");

        $users = User::where('role', 'umum')
            ->where('is_active', true)
            ->where('created_at', '<=', $cutoff)
            ->whereDoesntHave('tracerStudy', fn ($q) => $q->whereNotNull('filled_at'))
            ->get(['id', 'name', 'email', 'created_at']);

        if ($users->isEmpty()) {
            $this->info('Tidak ada target. Semua sudah mengisi atau belum cukup umur akun.');
            Log::info('[tracer:remind] Tidak ada target.');

            return self::SUCCESS;
        }

        $this->info("Ditemukan {$users->count()} target.");

        if ($isDryRun) {
            $this->table(
                ['ID', 'Nama', 'Email', 'Terdaftar'],
                $users->map(fn (User $u) => [
                    $u->id,
                    $u->name,
                    $u->email,
                    optional($u->created_at)->format('d/m/Y'),
                ])->toArray()
            );

            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        $sent = 0;
        $failed = [];

        foreach ($users as $user) {
            try {
                $user->notify(new TracerReminder);
                $sent++;
            } catch (\Throwable $e) {
                $failed[] = $user->id;
                Log::error("[tracer:remind] Gagal ke user ID {$user->id}.", ['exception' => $e->getMessage()]);
                $this->newLine();
                $this->error("  Gagal user ID {$user->id}: {$e->getMessage()}");
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Selesai. Terkirim: {$sent}, Gagal: ".count($failed).'.');
        Log::info("[tracer:remind] Selesai. Terkirim: {$sent}, Gagal: ".count($failed).'.');

        return count($failed) > 0 ? self::FAILURE : self::SUCCESS;
    }
}
