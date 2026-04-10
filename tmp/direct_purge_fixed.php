<?php
define('LARAVEL_START', microtime(true));
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$deleted = DB::table('users')->where('id', '!=', 18)->delete();
$remaining = DB::table('users')->count();

echo "DeletedCount: $deleted\n";
echo "RemainingCount: $remaining\n";
foreach (DB::table('users')->get() as $u) {
    echo "User: " . $u->id . " - " . $u->username . " (" . $u->role . ")\n";
}
