<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$targetUserId = 18; // sunibertson R. sunico as identified earlier

$tablesToTruncate = [
    'boundaries',
    'boundary_rules',
    'coding_records',
    'coding_rules',
    'dashcam_devices',
    'dashcam_events',
    'dashcam_footage',
    'dashcam_settings',
    'dashcam_test_logs',
    'device_alerts',
    'device_import_history',
    'driver_behavior',
    'driver_incentives',
    'drivers',
    'employees',
    'expenses',
    'failed_jobs',
    'franchise_case_units',
    'franchise_cases',
    'franchise_units',
    'gps_devices',
    'gps_logs',
    'gps_settings',
    'gps_test_logs',
    'gps_tracking',
    'maintenance',
    'maintenance_records',
    'managed_expenses',
    'salaries',
    'staff',
    'system_alerts',
    'unit_assignments',
    'units',
    'user_recognized_devices',
    'user_sessions',
    'user_verified_browsers',
    'password_resets',
    'personal_access_tokens'
];

echo "Starting cleanup...\n";

Schema::disableForeignKeyConstraints();

foreach ($tablesToTruncate as $table) {
    if (Schema::hasTable($table)) {
        try {
            echo "Truncating $table...\n";
            DB::table($table)->truncate();
        } catch (\Exception $e) {
            echo "Failed to truncate $table: " . $e->getMessage() . " - trying DELETE instead...\n";
            try {
                DB::table($table)->delete();
            } catch (\Exception $e2) {
                echo "Critical failure on $table: " . $e2->getMessage() . "\n";
            }
        }
    } else {
        echo "Table $table does not exist, skipping...\n";
    }
}

// Special handling for users table
echo "Cleaning users table, keeping ID $targetUserId...\n";
try {
    DB::table('users')->where('id', '!=', $targetUserId)->delete();
    echo "Users table cleaned.\n";
} catch (\Exception $e) {
    echo "Failed to clean users table: " . $e->getMessage() . "\n";
}

Schema::enableForeignKeyConstraints();
echo "Cleanup process finished!\n";
