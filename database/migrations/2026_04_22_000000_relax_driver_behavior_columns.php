<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('driver_behavior', function (Blueprint $table) {
            // Change enum/small columns to varchar(191) to prevent truncation and strict validation mismatches
            $table->string('incident_type', 191)->change();
            $table->string('severity', 191)->change();
            $table->string('charge_status', 191)->default('none')->change(); // Also relaxing charge status
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('driver_behavior', function (Blueprint $table) {
            // Rollback is tricky for enums, but we'll try to restore the basics if needed
            // However, since we're relaxing, rollback shouldn't be urgent.
        });
    }
};
