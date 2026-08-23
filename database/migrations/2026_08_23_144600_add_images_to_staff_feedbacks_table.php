<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('staff_feedbacks')) {
            Schema::table('staff_feedbacks', function (Blueprint $table) {
                if (!Schema::hasColumn('staff_feedbacks', 'images')) {
                    $table->longText('images')->nullable()->after('page_url');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('staff_feedbacks')) {
            Schema::table('staff_feedbacks', function (Blueprint $table) {
                if (Schema::hasColumn('staff_feedbacks', 'images')) {
                    $table->dropColumn('images');
                }
            });
        }
    }
};
