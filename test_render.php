<?php
// We need to set the base path for Laravel
define('LARAVEL_START', microtime(true));

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Boot Laravel
$response = $kernel->handle(
    $request = Illuminate\Http\Request::create('/analytics', 'GET')
);

// We need to act as an authenticated user
use App\Models\User;
use Illuminate\Support\Facades\Auth;

$user = User::first();
if (!$user) {
    echo "No users found in database to authenticate.";
    exit(1);
}
Auth::login($user);

// Now render the controller action directly or view
$controller = $app->make(\App\Http\Controllers\AnalyticsController::class);
$response = $controller->index($request);

if ($response instanceof \Illuminate\View\View) {
    echo $response->render();
} else {
    echo $response->getContent();
}
