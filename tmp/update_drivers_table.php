<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (!Schema::hasColumn('drivers', 'is_active')) {
    Schema::table('drivers', function (Blueprint $table) {
        $table->boolean('is_active')->default(true)->after('status');
    });
    echo "Column is_active added to drivers table.\n";
} else {
    echo "Column is_active already exists in drivers table.\n";
}
