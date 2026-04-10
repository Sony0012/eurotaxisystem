<?php
foreach (App\Models\User::all(['id', 'username', 'role']) as $u) {
    echo $u->id . ': ' . $u->username . ' (' . $u->role . ")\n";
}
