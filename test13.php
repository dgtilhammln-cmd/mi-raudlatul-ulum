<?php 
require __DIR__.'/vendor/autoload.php'; 
$app = require_once __DIR__.'/bootstrap/app.php'; 
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); 
$kernel->bootstrap(); 
$session = App\Models\ExamSession::find(1893);
$essayQuestionsCount = $session->examQuestions()
    ->whereHas('question', fn($q) => $q->where('type', 'essay'))
    ->count();
var_dump([
    'session_id' => $session->id,
    'essay_questions_count' => $essayQuestionsCount,
    'has_essay' => $session->examQuestions()->whereHas('question', fn($q) => $q->where('type', 'essay'))->exists()
]);
