<?php
// Quick migration script - run once then delete
require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'suffix')) {
            $table->string('suffix', 10)->nullable()->after('last_name');
            echo "Added suffix column\n";
        } else {
            echo "suffix column already exists\n";
        }
        if (!Schema::hasColumn('users', 'phone_number')) {
            $table->string('phone_number', 20)->nullable()->after('suffix');
            echo "Added phone_number column\n";
        } else {
            echo "phone_number column already exists\n";
        }
    });
    echo "Done!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
