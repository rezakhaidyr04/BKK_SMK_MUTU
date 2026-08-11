<?php
// Phase 1 verification script
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\User;

echo "=== PHASE 1 VERIFICATION ===\n\n";

// 1. Check companies columns
echo "--- companies table columns ---\n";
$cols = Schema::getColumnListing('companies');
echo implode(', ', $cols) . "\n\n";

// 2. Check users columns
echo "--- users table columns ---\n";
$cols = Schema::getColumnListing('users');
echo implode(', ', $cols) . "\n\n";

// 3. Check user_id nullable
echo "--- companies.user_id nullable? ---\n";
$colInfo = DB::select("SHOW COLUMNS FROM companies WHERE Field = 'user_id'");
if (!empty($colInfo)) {
    $c = $colInfo[0];
    echo "Null: " . ($c->Null === 'YES' ? 'YES (nullable ✓)' : 'NO (not nullable ✗)') . "\n\n";
}

// 4. Check Spatie roles
echo "--- Spatie Roles ---\n";
$roles = Role::all();
foreach ($roles as $role) {
    echo "  - {$role->name} (guard: {$role->guard_name})\n";
}
echo "\n";

// 5. Check users with role=company but no Spatie role
echo "--- Users role=company WITHOUT Spatie company role ---\n";
$companyUsers = User::where('role', 'company')->get();
$unsynced = [];
foreach ($companyUsers as $u) {
    if (!$u->hasRole('company')) {
        $unsynced[] = $u->email;
    }
}
if (empty($unsynced)) {
    echo "  All company users are synced ✓\n";
} else {
    echo "  UNSYNCED: " . implode(', ', $unsynced) . "\n";
}
echo "\n";

// 6. Check must_change_password and password_changed_at exist
echo "--- Key columns presence ---\n";
$checks = [
    'companies' => ['mou_path', 'mou_number', 'mou_signed_at', 'mou_expires_at', 'reviewed_by', 'reviewed_at', 'rejection_reason'],
    'users' => ['must_change_password', 'password_changed_at'],
];
foreach ($checks as $table => $columns) {
    echo "Table: $table\n";
    foreach ($columns as $col) {
        $exists = Schema::hasColumn($table, $col);
        echo "  $col: " . ($exists ? "EXISTS ✓" : "MISSING ✗") . "\n";
    }
}
echo "\n";

// 7. Check user_id FK constraint
echo "--- companies.user_id FK ---\n";
$fks = DB::select("
    SELECT CONSTRAINT_NAME, REFERENCED_TABLE_NAME, DELETE_RULE
    FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS rc
    JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
        ON rc.CONSTRAINT_NAME = kcu.CONSTRAINT_NAME
    WHERE kcu.TABLE_NAME = 'companies' AND kcu.COLUMN_NAME = 'user_id'
    AND kcu.TABLE_SCHEMA = DATABASE()
");
foreach ($fks as $fk) {
    echo "  FK: {$fk->CONSTRAINT_NAME} -> {$fk->REFERENCED_TABLE_NAME}, ON DELETE: {$fk->DELETE_RULE}\n";
}
if (empty($fks)) {
    echo "  No FK found for user_id\n";
}

echo "\n=== END VERIFICATION ===\n";
