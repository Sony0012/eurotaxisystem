<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

try {
    $out = "";
    
    // Find Arwin Azarcon
    $driver = DB::table('drivers')->where('first_name', 'like', '%Arwin%')->first();
    
    if (!$driver) {
        echo "Arwin not found.";
        exit;
    }
    
    $out .= "Driver ID: {$driver->id}\n";
    
    $bounds = DB::table('boundaries')->where('driver_id', $driver->id)->get();
    $out .= "Total Boundaries: " . count($bounds) . "\n";
    foreach ($bounds as $b) {
        $out .= "- status: {$b->status}, amt: {$b->boundary_amount}, deleted: {$b->deleted_at}\n";
    }

    $raw = DB::table('drivers as d')
        ->where('d.id', $driver->id)
        ->select('d.id', 
            DB::raw("(SELECT COUNT(*) FROM boundaries WHERE driver_id = d.id AND status IN ('paid', 'excess') AND deleted_at IS NULL) as total_paid_count"),
            DB::raw("(SELECT COUNT(*) FROM boundaries WHERE driver_id = d.id AND status IN ('paid', 'excess') AND deleted_at IS NULL AND date >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)) as paid_shifts_count")
        )
        ->first();
        
    $out .= "\nRAW total_paid_count: " . ($raw->total_paid_count ?? 'null') . "\n";
    $out .= "RAW paid_shifts_count: " . ($raw->paid_shifts_count ?? 'null') . "\n";
    
    header('Content-Type: text/plain');
    echo $out;
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
