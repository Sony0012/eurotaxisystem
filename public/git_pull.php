<?php
header('Content-Type: text/plain; charset=utf-8');

$root = __DIR__;
if (file_exists(dirname(__DIR__) . '/artisan')) {
    $root = dirname(__DIR__);
}

chdir($root);
echo "ROOT: " . getcwd() . "\n\n";

$cmds = [
    'git status 2>&1',
    'git fetch --all 2>&1',
    'git reset --hard origin/master 2>&1',
    'php artisan optimize:clear 2>&1',
    'php artisan view:clear 2>&1',
    'php artisan config:clear 2>&1',
    'php artisan route:clear 2>&1',
    'php artisan migrate --force 2>&1',
];

foreach ($cmds as $cmd) {
    echo ">>> {$cmd}\n";
    $output = [];
    $ret = 0;
    exec($cmd, $output, $ret);
    echo implode("\n", $output) . "\nReturn code: {$ret}\n\n";
}

echo "=== FINISHED ===\n";
