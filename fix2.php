<?php 
require __DIR__.'/vendor/autoload.php'; 
$app = require_once __DIR__.'/bootstrap/app.php'; 
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); 
$kernel->bootstrap(); 

$answers = App\Models\Answer::where('essay_status', 'pending')
    ->whereHas('question', fn($q) => $q->where('type', '!=', 'essay'))
    ->get();

echo "Found " . $answers->count() . " broken answers.\n";
foreach($answers as $answer) {
    $answer->update(['essay_status' => null]);
}

$round = App\Models\Round::find(20);
echo "Ready to advance: " . ($round->isReadyToAdvance() ? 'YES' : 'NO') . "\n";
app(App\Services\BracketAdvancementService::class)->tryAutoAdvance($round);
echo "Status: " . $round->advancement_status . "\n";
