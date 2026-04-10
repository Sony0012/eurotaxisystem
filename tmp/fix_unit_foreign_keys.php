<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

try {
    // 1. Drop existing foreign keys
    Schema::table('units', function (Blueprint $table) {
        $table->dropForeign('units_ibfk_1');
        $table->dropForeign('fk_units_secondary_driver');
    });
    echo "Foreign keys dropped.\n";

    // 2. Add new foreign keys pointing to drivers table
    Schema::table('units', function (Blueprint $table) {
        $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('set null');
        $table->foreign('secondary_driver_id')->references('id')->on('drivers')->onDelete('set null');
    });
    echo "New foreign keys (pointing to drivers) added.\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
