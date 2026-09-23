<?php
require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$request = Illuminate\Http\Request::create("/chat/react/22", "POST", [], [], [], ["HTTP_X_REQUESTED_WITH" => "XMLHttpRequest", "CONTENT_TYPE" => "application/json"], json_encode(["reaction" => "\u{1F606}"]));
$controller = new App\Http\Controllers\ChatController();
Auth::loginUsingId(125);
$response = $controller->react($request, 22);
echo $response->getContent();
echo "\nDB Row:\n";
print_r(DB::table("chat_messages")->where("id", 22)->first());

