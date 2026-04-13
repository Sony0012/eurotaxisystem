<?php
// Deployment Trigger for Euro Taxi System - Automated Sync
$secret_key = "eurotaxi_power_sync_2026";

if (($_GET['key'] ?? '') !== $secret_key) {
    die("Unauthorized Access");
}

header('Content-Type: text/plain');
echo "--- STARTING FORCED PRODUCTION SYNC ---\n";

function run($cmd) {
    echo "\nExecuting: $cmd\n";
    $output = [];
    $status = 0;
    exec($cmd . " 2>&1", $output, $status);
    echo implode("\n", $output) . "\n";
    echo "Status: $status\n";
    return $status === 0;
}

// 1. Sync from the direct-sync branch which was pushed from local
run("git fetch origin main");
run("git reset --hard direct-sync");

// 2. Run database migrations
run("php artisan migrate --force");

// 3. Optimize application
run("php artisan optimize");

echo "\n--- SYNC COMPLETED SUCCESSFULLY ---";
