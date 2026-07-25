<?php 
require __DIR__.'/vendor/autoload.php'; 
$app = require_once __DIR__.'/bootstrap/app.php'; 
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); 
$kernel->bootstrap(); 
$sessions = App\Models\ExamSession::where('round_id', 20)->get();
foreach($sessions as $session) {
    $pendingEssays = $session->answers()
        ->whereHas('question', fn($q) => $q->where('type', 'essay'))
        ->where('essay_status', 'pending')
        ->count();
    
    if ($pendingEssays > 0 && $session->result_status === 'final') {
        echo "FIXING Session {$session->id} (has {$pendingEssays} pending essays but is final!)\n";
        $session->update(['result_status' => 'essay_pending']);
    } elseif ($pendingEssays === 0 && $session->result_status === 'essay_pending') {
        echo "FIXING Session {$session->id} (no pending essays but is essay_pending!)\n";
        $session->update(['result_status' => 'final']);
    } else {
        echo "Session {$session->id} is {$session->result_status} with {$pendingEssays} pending.\n";
    }
}

// Then try auto advance!
$round = App\Models\Round::find(20);
echo "Ready to advance: " . ($round->isReadyToAdvance() ? 'YES' : 'NO') . "\n";
app(App\Services\BracketAdvancementService::class)->tryAutoAdvance($round);
