<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Total reviews: " . \App\Models\Review::count() . "\n";
echo "Average rating: " . \App\Models\Review::getAverageRating() . "\n";
echo "Total approved: " . \App\Models\Review::getTotalReviews() . "\n";
echo "Satisfaction %: " . \App\Models\Review::getSatisfactionPercentage() . "\n";
