<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$expenses = \Illuminate\Support\Facades\DB::table('expenses')->where('amount', '<', 0)->get();
echo "Negative Expenses:\n";
print_r($expenses->toArray());

$salaries = \Illuminate\Support\Facades\DB::table('salaries')->where('total_salary', '<', 0)->get();
echo "\nNegative Salaries:\n";
print_r($salaries->toArray());

$maintenance = \Illuminate\Support\Facades\DB::table('maintenance')->where('cost', '<', 0)->get();
echo "\nNegative Maintenance:\n";
print_r($maintenance->toArray());

$affectedExpenses = \Illuminate\Support\Facades\DB::table('expenses')->where('amount', '<', 0)->update(['amount' => \Illuminate\Support\Facades\DB::raw('ABS(amount)')]);
$affectedSalaries = \Illuminate\Support\Facades\DB::table('salaries')->where('total_salary', '<', 0)->update(['total_salary' => \Illuminate\Support\Facades\DB::raw('ABS(total_salary)')]);
$affectedMaintenance = \Illuminate\Support\Facades\DB::table('maintenance')->where('cost', '<', 0)->update(['cost' => \Illuminate\Support\Facades\DB::raw('ABS(cost)')]);

echo "\nFixed: $affectedExpenses expenses, $affectedSalaries salaries, $affectedMaintenance maintenance records.";
