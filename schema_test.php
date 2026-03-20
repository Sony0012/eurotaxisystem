<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$gps = Illuminate\Support\Facades\DB::select('DESCRIBE gps_devices');
$dashcam = Illuminate\Support\Facades\DB::select('DESCRIBE dashcam_devices');
echo "GPS schema:\n"; print_r($gps);
echo "\nDashcam schema:\n"; print_r($dashcam);
