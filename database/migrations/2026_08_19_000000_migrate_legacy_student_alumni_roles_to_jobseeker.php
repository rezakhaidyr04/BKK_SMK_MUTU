<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
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

        DB::transaction(function () use (
            $guardName,
            $rolesTable,
            $modelHasRolesTable,
            $rolePivotKey,
            $modelMorphKey,
        ) {
            // 1) Ensure the new Spatie role exists.
            $jobseekerRole = Role::findOrCreate('jobseeker', $guardName);

            // 2) Migrate legacy users.role values to the new consolidated role.
            DB::table('users')
                ->whereIn('role', ['student', 'alumni'])
                ->update([
                    'role' => 'jobseeker',
                    'updated_at' => now(),
                ]);

            // 3) If legacy Spatie roles exist, attach jobseeker role to the same users
            //    without creating duplicate assignments.
            $legacyRoleIds = DB::table($rolesTable)
                ->where('guard_name', $guardName)
                ->whereIn('name', ['student', 'alumni'])
                ->pluck('id');

            if ($legacyRoleIds->isNotEmpty()) {
                $legacyUserIds = DB::table($modelHasRolesTable)
                    ->where('model_type', User::class)
                    ->whereIn($rolePivotKey, $legacyRoleIds)
                    ->pluck($modelMorphKey)
                    ->unique()
                    ->values();

                if ($legacyUserIds->isNotEmpty()) {
                    $alreadyAssignedUserIds = DB::table($modelHasRolesTable)
                        ->where('model_type', User::class)
                        ->where($rolePivotKey, $jobseekerRole->getKey())
                        ->whereIn($modelMorphKey, $legacyUserIds)
                        ->pluck($modelMorphKey)
                        ->all();

                    $missingUserIds = $legacyUserIds
                        ->reject(fn ($userId) => in_array($userId, $alreadyAssignedUserIds))
                        ->values();

                    foreach ($missingUserIds as $userId) {
                        DB::table($modelHasRolesTable)->insert([
                            $rolePivotKey => $jobseekerRole->getKey(),
                            'model_type' => User::class,
                            $modelMorphKey => $userId,
                        ]);
                    }

                    // 4) Remove legacy student/alumni assignments after replacement.
                    DB::table($modelHasRolesTable)
                        ->where('model_type', User::class)
                        ->whereIn($rolePivotKey, $legacyRoleIds)
                        ->delete();
                }
            }
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        // Intentionally left non-destructive.
        //
        // This migration consolidates legacy `student` and `alumni` users into
        // `jobseeker`. After deployment, brand new public users may also use the
        // `jobseeker` role. Reverting all `jobseeker` users back to `student` or
        // `alumni` would corrupt legitimate new data because the original source
        // role can no longer be inferred safely.
    }
};
