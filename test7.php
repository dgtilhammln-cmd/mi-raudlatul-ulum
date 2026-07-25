<?php 
require __DIR__.'/vendor/autoload.php'; 
$app = require_once __DIR__.'/bootstrap/app.php'; 
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); 
$kernel->bootstrap(); 
$pending = App\Models\ExamSession::where('round_id', 20)
    ->whereIn('status', ['submitted', 'auto_submitted'])
    ->whereHas('answers', fn($q) => $q->where('essay_status', 'pending'))
    ->get();
echo "Count of pending: " . $pending->count() . "\n";
foreach($pending as $p) {
    echo "Session ID: {$p->id}, Participant: {$p->participant_id}\n";
    $answers = $p->answers()->where('essay_status', 'pending')->get();
    foreach($answers as $a) {
        echo "- Answer ID: {$a->id}, Question ID: {$a->question_id}, Essay Status: {$a->essay_status}\n";
    }
}
