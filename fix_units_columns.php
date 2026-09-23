<?php

require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    if (!Schema::hasColumn('units', 'gps_provider')) {
        DB::statement("ALTER TABLE units ADD COLUMN gps_provider VARCHAR(50) DEFAULT 'AKSH' AFTER imei");
        echo "Added gps_provider column.\n";
    } else {
        echo "gps_provider column already exists.\n";
    }

    if (!Schema::hasColumn('units', 'gps_password')) {
        DB::statement("ALTER TABLE units ADD COLUMN gps_password VARCHAR(50) DEFAULT '123456' AFTER gps_provider");
        echo "Added gps_password column.\n";
    } else {
        echo "gps_password column already exists.\n";
    }
    
    echo "Done.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
