<?php
// Since I can't easily run artisan migrate correctly in this state, I'll use a direct DB bridge.
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "Checking columns for 'users' table...\n";

try {
    Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'otp_code')) {
            echo "Adding 'otp_code' column...\n";
            $table->string('otp_code', 6)->nullable()->after('password');
        }
        if (!Schema::hasColumn('users', 'otp_expires_at')) {
            echo "Adding 'otp_expires_at' column...\n";
            $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
        }
        if (!Schema::hasColumn('users', 'verified_at')) {
            echo "Adding 'verified_at' column...\n";
            $table->timestamp('verified_at')->nullable()->after('otp_expires_at');
        }
    });
    echo "Database sync complete!\n";
} catch (\Exception $e) {
    echo "Error syncing database: " . $e->getMessage() . "\n";
}
