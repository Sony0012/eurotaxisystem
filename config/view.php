<?php

$viewCompiledPath = storage_path('framework/views');
if (!is_dir($viewCompiledPath)) {
    @mkdir($viewCompiledPath, 0777, true);
}

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    */

    'paths' => [
        resource_path('views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    */

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        $viewCompiledPath
    ),

];

