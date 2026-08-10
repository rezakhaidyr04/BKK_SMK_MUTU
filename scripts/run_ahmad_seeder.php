<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

require __DIR__ . '/../database/seeders/AhmadNurHarrySeeder.php';

$seeder = new \Database\Seeders\AhmadNurHarrySeeder();
$seeder->run();

echo "AhmadNurHarrySeeder executed\n";
