<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$round = \App\Models\Round::find(1);
$participant = \App\Models\Participant::find(1);
$session = \App\Models\ExamSession::where('round_id', 1)->where('participant_id', 1)->first();

echo "Round 1 State:\n";
echo "Advancement Status: " . $round->advancement_status . "\n";
echo "End Time: " . $round->end_time . "\n";
echo "Is Ready To Advance: " . ($round->isReadyToAdvance() ? 'Yes' : 'No') . "\n";
echo "Auto Advance: " . ($round->auto_advance ? 'Yes' : 'No') . "\n";
echo "Advancement Limit: " . $round->advancement_limit . "\n";

echo "\nHamzah Participant State:\n";
echo "Eliminated at Round: " . $participant->eliminated_at_round . "\n";
echo "Current Round Sequence: " . $participant->current_round_sequence . "\n";
echo "Status: " . $participant->status . "\n";

echo "\nHamzah Session State:\n";
echo "Session Status: " . ($session ? $session->status : 'No Session') . "\n";
echo "Total Score: " . ($session ? $session->total_score : 'N/A') . "\n";

// Let's preview advancement
if ($round) {
    echo "\nPreview Advancement:\n";
    $preview = app(\App\Services\BracketAdvancementService::class)->previewAdvancement($round);
    echo "Will Advance: " . count($preview['will_advance']) . " participants\n";
    echo "Will Eliminate: " . count($preview['will_eliminate']) . " participants\n";
    
    // Check if Hamzah is in will_advance
    $hamzahWillAdvance = collect($preview['will_advance'])->where('id', 1)->isNotEmpty();
    echo "Hamzah will advance? " . ($hamzahWillAdvance ? 'Yes' : 'No') . "\n";
}
