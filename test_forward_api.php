<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/chat/send', 'POST', [
    'to_user_id' => 0,
    'forward_from_id' => 1
]);

// Authenticate as first user
$user = \App\Models\User::first();
\Illuminate\Support\Facades\Auth::login($user);

$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo "Content: " . $response->getContent() . "\n";
