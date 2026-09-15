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
        if (Schema::hasTable('boundaries') && !Schema::hasColumn('boundaries', 'driver_fund')) {
            Schema::table('boundaries', function (Blueprint $table) {
                $table->decimal('driver_fund', 10, 2)->default(0.00)->after('actual_boundary');
            });
        }

        if (!Schema::hasTable('driver_funds')) {
            Schema::create('driver_funds', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('driver_id');
                $table->unsignedBigInteger('boundary_id')->nullable();
                $table->enum('type', ['deposit', 'withdrawal', 'maintenance_share'])->default('deposit');
                $table->decimal('amount', 10, 2);
                $table->decimal('balance_after', 10, 2)->default(0.00);
                $table->string('description', 255)->nullable();
                $table->date('date');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['driver_id', 'date']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('boundaries') && Schema::hasColumn('boundaries', 'driver_fund')) {
            Schema::table('boundaries', function (Blueprint $table) {
                $table->dropColumn('driver_fund');
            });
        }

        Schema::dropIfExists('driver_funds');
    }
};
