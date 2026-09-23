<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$msgs = \Illuminate\Support\Facades\DB::table('chat_messages')
    ->orderByDesc('id')
    ->limit(5)
    ->get();

foreach ($msgs as $msg) {
    echo "[ID: {$msg->id}] From: {$msg->from_user_id} To: " . ($msg->to_user_id ?? 'NULL') . " Fwd: {$msg->is_forwarded} Msg: {$msg->message}\n";
}
