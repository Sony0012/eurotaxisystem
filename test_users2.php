<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
\Illuminate\Support\Facades\Auth::loginUsingId(1); // Assuming ID 1 is a valid admin/staff

$controller = app(\App\Http\Controllers\ChatController::class);
$response = $controller->users();
echo json_encode($response->getData());
