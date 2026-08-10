<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::first();
if (!$user) {
    echo 'NO_USER';
    exit;
}

$user->load(['student', 'skills', 'certificates']);

$view = view('cv.templates.modern', [
    'user' => $user,
    'include_photo' => false,
    'include_skills' => true,
    'include_certificates' => false,
    'custom_headline' => '',
    'custom_summary' => '',
    'custom_experience' => '',
    'custom_achievement' => '',
]);

echo $view->render();
