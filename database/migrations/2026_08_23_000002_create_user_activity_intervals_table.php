<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('user_activity_intervals')) {
            Schema::create('user_activity_intervals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('connection_id', 64)->nullable()->index();
                $table->date('date')->index();
                $table->dateTime('started_at')->index();
                $table->dateTime('ended_at')->index();
                $table->unsignedInteger('duration_seconds')->default(0);
                $table->timestamps();

                $table->index(['user_id', 'date']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_activity_intervals');
    }
};
