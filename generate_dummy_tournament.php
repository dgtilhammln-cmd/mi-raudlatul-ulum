<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Event;
use App\Models\User;
use App\Models\Participant;
use App\Models\Round;
use App\Models\ExamSession;
use App\Models\QuestionBank;
use Carbon\Carbon;
use Illuminate\Support\Str;

echo "Starting Simulation: 6 Rounds, 300 Participants...\n";

// Get organizer
$organizer = User::where('role', 'organizer')->first();

// 1. Create Event
$event = Event::create([
    'organizer_id' => $organizer->id,
    'name' => 'Olimpiade Nasional 2026',
    'slug' => 'olimpiade-nasional-2026-'.Str::random(4),
    'type' => 'qualification',
    'status' => 'completed',
    'description' => 'Simulasi event olimpiade dengan 300 peserta dan 6 babak.',
    'bracket_mode' => 'full',
    'leaderboard_visible' => true,
    'start_date' => now()->subDays(10),
    'end_date' => now()->addDays(5),
]);
echo "Event created: {$event->name}\n";

// Generate unique prefix
$prefix = Str::random(4);

// 2. Create 300 Participants
$participants = [];
for ($i = 1; $i <= 300; $i++) {
    $user = User::create([
        'name' => "Peserta Simulasi $i",
        'email' => "peserta{$i}_{$prefix}@simulasi.com",
        'password' => bcrypt('password'),
        'role' => 'participant',
    ]);
    
    $p = Participant::create([
        'event_id' => $event->id,
        'user_id' => $user->id,
        'participant_code' => 'SIM-'.str_pad($i, 3, '0', STR_PAD_LEFT),
        'institution' => ['SMA 1 Surabaya', 'MAN 1 Gresik', 'SMKN 2 Surabaya', 'SMA 5 Bandung', 'SMAN 3 Malang'][array_rand(['SMA 1 Surabaya', 'MAN 1 Gresik', 'SMKN 2 Surabaya', 'SMA 5 Bandung', 'SMAN 3 Malang'])],
        'grade' => ['X', 'XI', 'XII'][array_rand(['X', 'XI', 'XII'])],
        'major' => ['IPA', 'IPS'][array_rand(['IPA', 'IPS'])],
        'certificate_link' => 'https://drive.google.com/file/d/12KfvzoyiQGRQkQqsRfNIVJOATMXuIJ6_/view?usp=sharing'
    ]);
    $participants[] = $p;
}
echo "300 Participants created!\n";

// 3. Create 6 Rounds and link unique Question Banks
$roundConfigs = [
    ['name' => 'Penyisihan 1', 'type' => 'group_stage', 'limit' => 150],
    ['name' => 'Penyisihan 2', 'type' => 'group_stage', 'limit' => 64],
    ['name' => 'Babak 64 Besar', 'type' => 'round_of_64', 'limit' => 32],
    ['name' => 'Babak 32 Besar', 'type' => 'round_of_32', 'limit' => 16],
    ['name' => 'Semifinal', 'type' => 'semi_final', 'limit' => 8],
    ['name' => 'Grand Final', 'type' => 'final', 'limit' => 3], // 3 winners
];

$rounds = [];
foreach ($roundConfigs as $idx => $c) {
    // Create unique bank for this round
    $bank = QuestionBank::create([
        'event_id' => $event->id,
        'name' => "Bank Soal - " . $c['name'],
        'description' => "Soal khusus untuk babak " . $c['name'],
    ]);

    $r = Round::create([
        'event_id' => $event->id,
        'sequence' => $idx + 1,
        'name' => $c['name'],
        'round_type' => $c['type'],
        'start_time' => now()->subDays(10 - $idx)->setHour(8)->setMinute(0),
        'end_time' => now()->subDays(10 - $idx)->setHour(10)->setMinute(0),
        'duration_minutes' => 60,
        'max_questions' => 50,
        'advancement_limit' => $c['limit'],
        'auto_advance' => true,
        'status' => 'completed',
        'advancement_status' => 'done',
    ]);
    
    // Link bank to round
    $r->questionBanks()->attach($bank->id, ['question_count' => 50]);
    
    $rounds[] = $r;
}
echo "6 Rounds created and linked to unique Question Banks!\n";

// 4. Simulate Scores and Elimination
$currentPool = collect($participants);

foreach ($rounds as $idx => $r) {
    echo "Simulating {$r->name} with {$currentPool->count()} participants...\n";
    
    // Attach current pool to round
    $r->participants()->sync($currentPool->pluck('id'));
    
    // Generate scores
    $sessions = [];
    foreach ($currentPool as $p) {
        $score = rand(40, 100) + (rand(0, 9) / 10); // e.g. 85.4
        $s = ExamSession::create([
            'participant_id' => $p->id,
            'round_id' => $r->id,
            'started_at' => clone $r->start_time,
            'finished_at' => (clone $r->start_time)->addMinutes(50),
            'status' => 'submitted',
            'result_status' => 'final',
            'total_score' => $score,
            'pg_score' => $score,
        ]);
        $sessions[] = $s;
    }
    
    // Determine advancement
    $sortedSessions = collect($sessions)->sortByDesc('total_score')->values();
    
    if ($idx == count($rounds) - 1) {
        // Final Round
        $top3 = $sortedSessions->take(3);
        $champion = $top3->first(); // Rank 1 is champion
        
        foreach ($sortedSessions as $i => $s) {
            $p = $currentPool->firstWhere('id', $s->participant_id);
            if ($i == 0) {
                $p->is_champion = true; // Still natively 1 champion for the banner
            } else {
                $p->eliminated_at_round = $r->sequence; // Rank 2 and 3 are "eliminated in final", but they will still be Top 2 and Top 3 in Leaderboard
            }
            $p->save();
        }
        echo "Champion: Participant {$champion->participant_id} (Score: {$champion->total_score})\n";
    } else {
        // Normal advancement
        $advanced = $sortedSessions->take($r->advancement_limit);
        $eliminated = $sortedSessions->skip($r->advancement_limit);
        
        foreach ($eliminated as $s) {
            $p = $currentPool->firstWhere('id', $s->participant_id);
            $p->eliminated_at_round = $r->sequence;
            $p->save();
        }
        
        // Next pool is the advanced ones
        $nextIds = $advanced->pluck('participant_id')->toArray();
        $currentPool = $currentPool->filter(fn($p) => in_array($p->id, $nextIds))->values();
    }
}

echo "Done! Olimpiade Nasional 2026 successfully simulated.\n";
