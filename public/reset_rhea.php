<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('first_name', 'like', '%Rhea%')->first();
if ($user) {
    $user->tutorial_completed = 0;
    $user->save();
    echo "Success: Rhea's tutorial reset. Email: " . $user->email . "\n";
} else {
    echo "Error: Rhea not found.\n";
}
