<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('boundaries', function (Blueprint $table) {
            $table->decimal('debt_payment_amount', 10, 2)->default(0)->after('actual_boundary');
            $table->decimal('debt_balance_snapshot', 10, 2)->default(0)->after('debt_payment_amount');
        });
    }

    public function down()
    {
        Schema::table('boundaries', function (Blueprint $table) {
            $table->dropColumn(['debt_payment_amount', 'debt_balance_snapshot']);
        });
    }
};
