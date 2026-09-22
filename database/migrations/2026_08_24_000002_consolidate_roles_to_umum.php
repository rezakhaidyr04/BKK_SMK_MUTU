<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        $guardName = config('auth.defaults.guard', 'web');
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        $rolesTable = $tableNames['roles'] ?? 'roles';
        $modelHasRolesTable = $tableNames['model_has_roles'] ?? 'model_has_roles';
        $rolePivotKey = $columnNames['role_pivot_key'] ?? 'role_id';
        $modelMorphKey = $columnNames['model_morph_key'] ?? 'model_id';

        // DML berjalan dalam transaksi; DDL harus di luar transaksi
        // karena MySQL melakukan implicit commit pada setiap DDL.

        // 1) Pastikan role umum tersedia di Spatie.
        $umumRole = Role::findOrCreate('umum', $guardName);

        // 2) Semua role pencari kerja & guru lama digabung ke "umum".
        DB::table('users')
            ->whereIn('role', ['jobseeker', 'student', 'alumni', 'teacher'])
            ->update([
                'role' => 'umum',
                'updated_at' => now(),
            ]);

        // 3) Pindahkan assignment Spatie lama ke role umum.
        $legacyRoleIds = DB::table($rolesTable)
            ->where('guard_name', $guardName)
            ->whereIn('name', ['jobseeker', 'student', 'alumni', 'teacher'])
            ->pluck('id');

        if ($legacyRoleIds->isNotEmpty()) {
            $legacyUserIds = DB::table($modelHasRolesTable)
                ->where('model_type', User::class)
                ->whereIn($rolePivotKey, $legacyRoleIds)
                ->pluck($modelMorphKey)
                ->unique()
                ->values();

            foreach ($legacyUserIds as $userId) {
                $exists = DB::table($modelHasRolesTable)
                    ->where('model_type', User::class)
                    ->where($rolePivotKey, $umumRole->getKey())
                    ->where($modelMorphKey, $userId)
                    ->exists();

                if (! $exists) {
                    DB::table($modelHasRolesTable)->insert([
                        $rolePivotKey => $umumRole->getKey(),
                        'model_type' => User::class,
                        $modelMorphKey => $userId,
                    ]);
                }
            }

            DB::table($modelHasRolesTable)
                ->where('model_type', User::class)
                ->whereIn($rolePivotKey, $legacyRoleIds)
                ->delete();

            DB::table($rolesTable)
                ->whereIn('id', $legacyRoleIds)
                ->delete();
        }

        // 4) Role default kolom users.role menjadi "umum".
        // P0 C-05: sintaks Postgres "ALTER COLUMN ... SET DEFAULT" tidak valid di MySQL.
        // Gunakan Schema Builder agar kompatibel MySQL (membutuhkan doctrine/dbal
        // untuk ->change(), sudah ada di composer.json). Aman untuk DB kosong
        // (hanya ubah default kolom, tidak menghapus data).
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('umum')->change();
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        // Penggabungan role bersifat satu arah: setelah semua pengguna
        // publik memakai role "umum", role asal tidak bisa direkonstruksi.
    }
};
