<?php
require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
DB::table("chat_messages")->where("id", 17)->update(["reactions" => null]);
echo "Cleared reaction for message 17";

