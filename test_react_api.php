<?php
require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
// We will mock an auth user
Auth::loginUsingId(125);
$request = Illuminate\Http\Request::create("/chat/react/21", "POST", ["reaction" => "??"]);
$response = app()->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo "Content: " . $response->getContent() . "\n";

