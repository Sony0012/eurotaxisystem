<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('driver_funds')) {
            DB::statement("ALTER TABLE driver_funds MODIFY COLUMN type VARCHAR(50) NOT NULL DEFAULT 'deposit'");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('driver_funds')) {
            DB::statement("ALTER TABLE driver_funds MODIFY COLUMN type ENUM('deposit', 'withdrawal', 'maintenance_share') NOT NULL DEFAULT 'deposit'");
        }
    }
};
