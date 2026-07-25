<?php 
require __DIR__.'/vendor/autoload.php'; 
$app = require_once __DIR__.'/bootstrap/app.php'; 
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); 
$kernel->bootstrap(); 
$session = App\Models\ExamSession::find(1893);
var_dump($session->examQuestions()->whereHas('question', fn($q) => $q->where('type', 'essay'))->exists());
