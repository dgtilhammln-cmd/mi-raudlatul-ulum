<?php 
require __DIR__.'/vendor/autoload.php'; 
$app = require_once __DIR__.'/bootstrap/app.php'; 
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); 
$kernel->bootstrap(); 
$event = App\Models\Event::find(6); 
$round = $event->rounds->first();
var_dump([
    'round_id' => $round->id,
    'isReady' => $round->isReadyToAdvance(), 
    'auto_advance' => $round->auto_advance, 
    'status' => $round->advancement_status, 
    'allGraded' => $round->allEssaysGraded(),
    'hasEssay' => $round->hasEssayQuestions()
]); 
