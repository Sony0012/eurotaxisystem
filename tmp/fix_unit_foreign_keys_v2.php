<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    // 1. Drop existing foreign keys if they exist
    // Handled via raw SQL to be more certain about error handling
    try {
        DB::statement("ALTER TABLE units DROP FOREIGN KEY units_ibfk_1");
        echo "Dropped units_ibfk_1\n";
    } catch (\Exception $e) {
        echo "Could not drop units_ibfk_1: " . $e->getMessage() . "\n";
    }

    try {
        DB::statement("ALTER TABLE units DROP FOREIGN KEY fk_units_secondary_driver");
        echo "Dropped fk_units_secondary_driver\n";
    } catch (\Exception $e) {
        echo "Could not drop fk_units_secondary_driver: " . $e->getMessage() . "\n";
    }

    // 2. Add new foreign keys pointing to drivers table
    Schema::table('units', function ($table) {
        $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('set null');
        $table->foreign('secondary_driver_id')->references('id')->on('drivers')->onDelete('set null');
    });
    echo "New foreign keys (pointing to drivers) added.\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
