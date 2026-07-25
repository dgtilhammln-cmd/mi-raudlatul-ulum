<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain; charset=utf-8');

echo "=== PARTICIPANTS EVENT 10 (Ulangan Harian) ===\n";
$p10 = DB::table('participants as p')
    ->join('users as u', 'u.id', '=', 'p.user_id')
    ->where('p.event_id', 10)
    ->get(['p.id','p.event_id','p.user_id','p.participant_code','p.status','u.name','u.participant_id']);
foreach ($p10 as $p) {
    echo "ParticipantID:{$p->id} | UserID:{$p->user_id} | Code:{$p->participant_code} | Name:{$p->name} | ParticipantLogin:{$p->participant_id}\n";
}

echo "\n=== PARTICIPANTS EVENT 11 (Ujian Semester Kelas 6) ===\n";
$p11 = DB::table('participants as p')
    ->join('users as u', 'u.id', '=', 'p.user_id')
    ->where('p.event_id', 11)
    ->get(['p.id','p.event_id','p.user_id','p.participant_code','p.status','u.name','u.participant_id']);
foreach ($p11 as $p) {
    echo "ParticipantID:{$p->id} | UserID:{$p->user_id} | Code:{$p->participant_code} | Name:{$p->name} | ParticipantLogin:{$p->participant_id}\n";
}

echo "\n=== PARTICIPANT_ROUND for Event 11 Rounds (33,34) ===\n";
$pr11 = DB::table('participant_round')->whereIn('round_id', [33,34])->get();
if ($pr11->isEmpty()) {
    echo "KOSONG! Peserta belum di-sync ke babak Event 11.\n";
} else {
    foreach ($pr11 as $row) {
        echo "ParticipantID:{$row->participant_id} | RoundID:{$row->round_id}\n";
    }
}

echo "\n=== USERS yang terdaftar di 2 event (sama persis berdasarkan nama) ===\n";
$names10 = $p10->pluck('name')->toArray();
$names11 = $p11->pluck('name')->toArray();
$sameNames = array_intersect($names10, $names11);
echo "Nama yang ada di kedua event:\n";
foreach ($sameNames as $name) {
    $in10 = $p10->where('name', $name)->first();
    $in11 = $p11->where('name', $name)->first();
    echo "Nama: {$name}\n";
    echo "  Event10 → UserID:{$in10->user_id} | ParticipantID:{$in10->id} | Login:{$in10->participant_id}\n";
    echo "  Event11 → UserID:{$in11->user_id} | ParticipantID:{$in11->id} | Login:{$in11->participant_id}\n";
    if ($in10->user_id !== $in11->user_id) {
        echo "  ⚠️ USER ID BERBEDA! Ini penyebab bug!\n";
    } else {
        echo "  ✅ USER ID sama\n";
    }
}
if (empty($sameNames)) {
    echo "Tidak ada nama yang cocok di kedua event.\n";
}
