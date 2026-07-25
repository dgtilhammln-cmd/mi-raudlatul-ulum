<?php 
require __DIR__.'/vendor/autoload.php'; 
$app = require_once __DIR__.'/bootstrap/app.php'; 
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); 
$kernel->bootstrap(); 
$answer = App\Models\Answer::find(52);
var_dump([
    'created_at' => (string) $answer->created_at,
    'updated_at' => (string) $answer->updated_at,
]);
