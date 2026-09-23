<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $expenses = DB::table('expenses')
        ->whereNull('expenses.deleted_at')
        ->leftJoin('units', 'expenses.unit_id', '=', 'units.id')
        ->select(
            'expenses.id',
            'expenses.date',
            'expenses.category',
            'expenses.description',
            'expenses.vendor_name',
            DB::raw('ABS(expenses.amount) as amount'),
            'expenses.payment_method',
            'expenses.reference_number',
            'expenses.status',
            'units.plate_number'
        )
        ->get();
    echo "SUCCESS! Count: " . count($expenses) . "\n";
    if (count($expenses) > 0) {
        echo "First record details:\n";
        print_r($expenses[0]);
    }
} catch (\Exception $e) {
    echo "FAILURE! Error: " . $e->getMessage() . "\n";
}
