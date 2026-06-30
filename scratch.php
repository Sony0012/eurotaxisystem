<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$driver = \App\Models\Driver::where('first_name', 'Agapito')->first();
echo json_encode($driver, JSON_PRETTY_PRINT);
