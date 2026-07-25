<?php 
require __DIR__.'/vendor/autoload.php'; 
$app = require_once __DIR__.'/bootstrap/app.php'; 
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); 
$kernel->bootstrap(); 
$round = App\Models\Round::find(1); 
var_dump([
    'isReady' => $round->isReadyToAdvance(), 
    'auto_advance' => $round->auto_advance, 
    'status' => $round->advancement_status, 
    'allGraded' => $round->allEssaysGraded(),
    'hasEssay' => $round->hasEssayQuestions()
]); 
