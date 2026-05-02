<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'missing' to the units table status enum
        DB::statement("ALTER TABLE units MODIFY status ENUM('active', 'maintenance', 'coding', 'retired', 'vacant', 'at_risk', 'missing') NOT NULL DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back, rows with 'missing' will be truncated/warned
        DB::statement("ALTER TABLE units MODIFY status ENUM('active', 'maintenance', 'coding', 'retired', 'vacant', 'at_risk') NOT NULL DEFAULT 'active'");
    }
};
