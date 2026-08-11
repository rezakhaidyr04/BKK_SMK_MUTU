<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$fks = DB::select("
    SELECT rc.CONSTRAINT_NAME, rc.DELETE_RULE, kcu.REFERENCED_TABLE_NAME
    FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS rc
    JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
        ON rc.CONSTRAINT_NAME = kcu.CONSTRAINT_NAME
        AND rc.CONSTRAINT_SCHEMA = kcu.TABLE_SCHEMA
    WHERE kcu.TABLE_NAME = 'companies'
    AND kcu.COLUMN_NAME = 'user_id'
    AND kcu.TABLE_SCHEMA = DATABASE()
");

echo "--- companies.user_id FK ---\n";
foreach ($fks as $fk) {
    echo "FK: {$fk->CONSTRAINT_NAME} -> {$fk->REFERENCED_TABLE_NAME}, ON DELETE: {$fk->DELETE_RULE}\n";
}
if (empty($fks)) {
    echo "No FK found for user_id\n";
}
echo "\nDone.\n";
