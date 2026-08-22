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
        if (!Schema::hasTable('user_presence_connections')) {
            Schema::create('user_presence_connections', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('connection_id', 64)->unique();
                $table->string('session_id', 128)->nullable();
                $table->string('device_type', 32)->default('desktop');
                $table->string('browser', 64)->nullable();
                $table->string('platform', 64)->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->dateTime('connected_at');
                $table->dateTime('last_seen_at')->index();
                $table->dateTime('last_activity_at')->nullable();
                $table->dateTime('disconnected_at')->nullable();
                $table->boolean('is_active')->default(true)->index();
                $table->timestamps();

                $table->index(['user_id', 'is_active']);
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
        Schema::dropIfExists('user_presence_connections');
    }
};
