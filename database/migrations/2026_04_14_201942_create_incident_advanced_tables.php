<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('incident_involved_parties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('incident_id');
            $table->string('name')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('plate_number')->nullable();
            $table->string('contact_info')->nullable();
            $table->timestamps();
        });

        Schema::create('incident_damages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('incident_id');
            $table->unsignedBigInteger('spare_part_id')->nullable();
            $table->string('part_name');
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->integer('qty')->default(1);
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->enum('type', ['own_unit', 'third_party'])->default('own_unit');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('incident_damages');
        Schema::dropIfExists('incident_involved_parties');
    }
};
