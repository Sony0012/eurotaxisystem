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
        if (!Schema::hasTable('staff_feedbacks')) {
            Schema::create('staff_feedbacks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('user_name')->nullable();
                $table->string('user_email')->nullable();
                $table->string('user_role')->nullable();
                $table->string('rating', 32); // very-sad, sad, neutral, happy
                $table->string('rating_label', 64)->nullable(); // Terrible, Bad, Okay, Amazing
                $table->text('feedback');
                $table->string('page_url', 500)->nullable();
                $table->string('status', 32)->default('new'); // new, reviewed, resolved
                $table->string('ip_address', 64)->nullable();
                $table->timestamps();

                $table->index('user_id');
                $table->index('status');
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_feedbacks');
    }
};
