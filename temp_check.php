<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$e = \App\Models\Event::where('slug', 'olimpiade-mti-2026-sistem-kualifikasi-u0t8')->first();
$rounds = $e->rounds()->orderBy('sequence')->get();

foreach($rounds as $r) {
    $r->start_time = now()->subDays(2);
    $r->end_time = now()->subDays(1);
    $r->advancement_status = 'done';
    $r->save();
}

// Make the first participant champion
$p = $e->participants()->first();
if ($p) {
    $p->is_champion = true;
    $p->save();
}

// Make the rest eliminated at sequence 2
foreach ($e->participants()->where('id', '!=', $p->id)->get() as $other) {
    $other->eliminated_at_round = 2;
    $other->save();
}

echo "Qualification event simulated as completed!\n";
