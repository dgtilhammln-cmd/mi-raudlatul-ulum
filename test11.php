<?php 
require __DIR__.'/vendor/autoload.php'; 
$app = require_once __DIR__.'/bootstrap/app.php'; 
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); 
$kernel->bootstrap(); 
$session = App\Models\ExamSession::find(1893);
var_dump([
    'created_at' => (string) $session->created_at,
    'updated_at' => (string) $session->updated_at,
    'submitted_at' => (string) $session->submitted_at,
    'result_published_at' => (string) $session->result_published_at
]);
