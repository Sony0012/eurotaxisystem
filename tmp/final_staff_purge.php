<?php
use App\Models\User;
use Illuminate\Support\Facades\DB;

$deletedCount = DB::table('users')->where('id', '<>', 18)->delete();
$totalRemaining = DB::table('users')->count();

file_put_contents('tmp/purge_audit.txt', "Deleted: $deletedCount\nRemaining: $totalRemaining\n");
echo "Purge process completed. Deleted $deletedCount records.\n";
foreach (User::all() as $u) {
    echo $u->id . ": " . $u->username . " (" . $u->role . ")\n";
}
