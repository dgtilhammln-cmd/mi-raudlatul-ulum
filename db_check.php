<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain; charset=utf-8');

echo "=== SERVER TIME ===\n";
echo "Server Now: " . now()->toDateTimeString() . "\n";
echo "Timezone: " . config('app.timezone') . "\n\n";

echo "=== EVENTS ===\n";
$events = DB::table('events')->get(['id','name','slug','scoring_system','status']);
foreach ($events as $e) {
    echo "ID:{$e->id} | Name:{$e->name} | Slug:{$e->slug} | System:{$e->scoring_system} | Status:{$e->status}\n";
}

echo "\n=== ROUNDS ===\n";
$rounds = DB::table('rounds')->get(['id','event_id','name','sequence','start_time','end_time']);
foreach ($rounds as $r) {
    echo "ID:{$r->id} | EventID:{$r->event_id} | Seq:{$r->sequence} | Name:{$r->name} | Start:{$r->start_time} | End:{$r->end_time}\n";
}

echo "\n=== PARTICIPANTS (First 5) ===\n";
$parts = DB::table('participants')->limit(5)->get(['id','event_id','user_id','participant_code','status']);
foreach ($parts as $p) {
    echo "ID:{$p->id} | EventID:{$p->event_id} | UserID:{$p->user_id} | Code:{$p->participant_code} | Status:{$p->status}\n";
}

echo "\n=== PARTICIPANT_ROUND ===\n";
$pr = DB::table('participant_round')->limit(10)->get();
if ($pr->isEmpty()) {
    echo "KOSONG! Tidak ada data di tabel participant_round.\n";
} else {
    foreach ($pr as $row) {
        echo "ParticipantID:{$row->participant_id} | RoundID:{$row->round_id}\n";
    }
}
