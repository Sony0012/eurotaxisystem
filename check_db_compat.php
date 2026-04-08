<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $tableStatus = DB::select("SHOW TABLE STATUS LIKE 'users'");
    $engine = $tableStatus[0]->Engine ?? 'Unknown';
    
    $describe = DB::select("DESCRIBE users");
    $idType = '';
    foreach($describe as $col) {
        if ($col->Field === 'id') {
            $idType = $col->Type;
            break;
        }
    }

    echo "Table: users" . PHP_EOL;
    echo "Engine: " . $engine . PHP_EOL;
    echo "ID Type: " . $idType . PHP_EOL;
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
