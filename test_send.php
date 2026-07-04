<?php
require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$request = Illuminate\Http\Request::create("/chat/send", "POST", ["to_user_id" => 130, "message" => "test reply", "reply_to_id" => 17]);
$controller = new App\Http\Controllers\ChatController();
Auth::loginUsingId(125);
$response = $controller->send($request);
echo $response->getContent();

