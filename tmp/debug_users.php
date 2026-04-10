<?php
use Illuminate\Support\Facades\DB;
$users = DB::table('users')->get(['id', 'username', 'role']);
$data = "";
foreach ($users as $u) {
    $data .= $u->id . ": " . $u->username . " (" . $u->role . ")\n";
}
file_put_contents('tmp/debug_users_list.txt', $data);
echo "User list saved to tmp/debug_users_list.txt\n";
