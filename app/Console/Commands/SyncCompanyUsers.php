<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Company;

class SyncCompanyUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:companies
                            {--dry-run : Preview users that would be synchronized without modifying the database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Company records for users with role=company that are missing Company entries';

    public function handle(): int
    {
        $isDryRun = (bool) $this->option('dry-run');

        if ($isDryRun) {
            $this->warn('>>> DRY-RUN MODE: tidak ada perubahan database yang akan dilakukan. <<<');
            $this->newLine();
        }

        $this->info('Mencari pengguna aktif dengan role=company tanpa Company record...');

        // Hanya proses user aktif.
        // whereDoesntHave('company') secara default mengikuti global scope Eloquent,
        // sehingga Company yang soft-deleted tidak dihitung — user tersebut TIDAK akan
        // dibuatkan Company baru oleh command ini (mencegah duplikasi tersembunyi).
        $users = User::where('role', 'company')
            ->where('is_active', true)
            ->whereDoesntHave('company')
            ->get();

        $total = $users->count();

        if ($users->isEmpty()) {
            $this->info('Tidak ditemukan pengguna company aktif tanpa Company record. Tidak ada yang diproses.');
            Log::info('[sync:companies] Tidak ada kandidat ditemukan.');
            return self::SUCCESS;
        }

        $this->info("Ditemukan {$total} kandidat.");
        $this->newLine();

        // Mode dry-run: tampilkan kandidat tanpa menyentuh database.
        if ($isDryRun) {
            $this->table(
                ['ID', 'Nama', 'Email', 'is_active'],
                $users->map(fn (User $u) => [
                    $u->id,
                    $u->name ?: '(kosong)',
                    $u->email,
                    $u->is_active ? 'true' : 'false',
                ])->toArray()
            );
            $this->newLine();
            $this->warn("Total kandidat: {$total}");
            $this->info('Dry-run selesai. Tidak ada perubahan database yang dilakukan.');
            return self::SUCCESS;
        }

        Log::info("[sync:companies] Memulai sinkronisasi. Kandidat ditemukan: {$total}.");

        $bar       = $this->output->createProgressBar($total);
        $bar->start();

        $countCreated = 0;
        $countFailed  = 0;
        $failedUsers  = [];

        // Proses tiap user secara individual agar kegagalan satu user
        // tidak membatalkan keberhasilan user lainnya (partial success).
        foreach ($users as $user) {
            try {
                DB::transaction(function () use ($user) {
                    Company::create([
                        'user_id'             => $user->id,
                        'name'                => $user->name ?: 'Perusahaan',
                        'is_verified'         => false,
                        // 'not_submitted' adalah status intentional untuk Company yang
                        // dibuat oleh sistem/admin — konsisten dengan AdminUserController.
                        // 'pending' dipakai setelah perusahaan mengajukan verifikasi.
                        'verification_status' => 'pending',
                    ]);
                });

                Log::info("[sync:companies] Company dibuat untuk user ID {$user->id} ({$user->email}).");
                $countCreated++;
            } catch (\Throwable $e) {
                $countFailed++;
                $failedUsers[] = $user->id;

                Log::error("[sync:companies] Gagal membuat Company untuk user ID {$user->id} ({$user->email}).", [
                    'exception' => $e->getMessage(),
                    'trace'     => $e->getTraceAsString(),
                ]);

                $this->newLine();
                $this->error("  Gagal memproses user ID {$user->id} ({$user->email}): {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // ── Ringkasan ──────────────────────────────────────────────────────────
        $this->info("Selesai.");
        $this->line("  Total kandidat  : {$total}");
        $this->info("  Berhasil dibuat : {$countCreated}");

        if ($countFailed > 0) {
            $this->warn("  Gagal           : {$countFailed} (user ID: " . implode(', ', $failedUsers) . ')');
        } else {
            $this->line("  Gagal           : 0");
        }

        Log::info("[sync:companies] Selesai. Berhasil: {$countCreated}, Gagal: {$countFailed}.");

        return $countFailed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
