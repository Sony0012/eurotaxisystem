<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
use Illuminate\Support\Facades\DB;

$month = now()->timezone('Asia/Manila')->month;
$year = now()->timezone('Asia/Manila')->year;

$genExMonth = DB::table('expenses')->whereNull('deleted_at')->whereMonth('date', $month)->whereYear('date', $year)->sum('amount');
$salExMonth = DB::table('salaries')->whereMonth('pay_date', $month)->whereYear('pay_date', $year)->sum('total_salary');
$mntExMonth = DB::table('maintenance')->whereNull('deleted_at')->whereMonth('date_started', $month)->whereYear('date_started', $year)->where('status', '!=', 'cancelled')->sum('cost');

$boundary = DB::table('boundaries')->whereNull('deleted_at')->whereMonth('date', $month)->whereYear('date', $year)->sum('actual_boundary');

echo "genExMonth: $genExMonth\n";
echo "salExMonth: $salExMonth\n";
echo "mntExMonth: $mntExMonth\n";
echo "boundary: $boundary\n";
