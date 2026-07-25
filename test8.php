<?php 
require __DIR__.'/vendor/autoload.php'; 
$app = require_once __DIR__.'/bootstrap/app.php'; 
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); 
$kernel->bootstrap(); 
$round = App\Models\Round::find(20); 
$sessions = $round->examSessions()->where('result_status', 'essay_pending')->get(); 
echo $sessions->count();
