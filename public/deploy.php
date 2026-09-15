<?php
/**
 * Web-based Deployer for EuroTaxi System
 */
header('Content-Type: text/plain; charset=utf-8');

echo "=== EUROTAXI WEB DEPLOY START ===\n\n";

$baseDir = dirname(__DIR__);
if (file_exists($baseDir . '/artisan')) {
    chdir($baseDir);
} elseif (file_exists(__DIR__ . '/artisan')) {
    chdir(__DIR__);
}

echo "Working Directory: " . getcwd() . "\n\n";

// 1. Fetch & Reset Git
echo "--- Step 1: Git Fetch & Hard Reset ---\n";
$out1 = [];
exec("git fetch origin master 2>&1 && git reset --hard origin/master 2>&1", $out1, $ret1);
echo implode("\n", $out1) . "\nReturn Code: {$ret1}\n\n";

// 2. Clear caches
echo "--- Step 2: Clear & Re-cache Application ---\n";
$out2 = [];
exec("php artisan optimize:clear 2>&1", $out2, $ret2);
echo implode("\n", $out2) . "\n\n";

// 3. Migrate database
echo "--- Step 3: Run Database Migrations ---\n";
$out3 = [];
exec("php artisan migrate --force 2>&1", $out3, $ret3);
echo implode("\n", $out3) . "\n\n";

echo "=== DEPLOY FINISHED SUCCESSFULLY ===";

