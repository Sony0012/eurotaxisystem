<?php
require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$request = Illuminate\Http\Request::create("/chat/react/17", "POST", [], [], [], ["HTTP_X_REQUESTED_WITH" => "XMLHttpRequest", "CONTENT_TYPE" => "application/json"], json_encode(["reaction" => "??"]));
// Bypass CSRF for testing by instantiating the controller directly
$controller = new App\Http\Controllers\ChatController();
Auth::loginUsingId(125); // Login as SuperAdmin
$response = $controller->react($request, 17);
echo $response->getContent();

