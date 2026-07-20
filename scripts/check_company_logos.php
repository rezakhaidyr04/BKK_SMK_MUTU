<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Company;

$companies = Company::select('id', 'name', 'logo')->get();
foreach ($companies as $c) {
    echo sprintf("%d | %s | %s\n", $c->id, $c->name, $c->logo ?? '<null>');
}
