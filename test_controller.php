<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::first();
\Illuminate\Support\Facades\Auth::login($user);

$request = Illuminate\Http\Request::create('/chat/send', 'POST', [
    'to_user_id' => 0,
    'forward_from_id' => 1
]);

$controller = app()->make(\App\Http\Controllers\ChatController::class);

try {
    $response = $controller->send($request);
    echo "Response: " . $response->getContent() . "\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
