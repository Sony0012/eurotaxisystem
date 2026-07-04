<?php
require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$messages = DB::table("chat_messages")->orderBy("id", "desc")->limit(1)->get();
print_r($messages);

