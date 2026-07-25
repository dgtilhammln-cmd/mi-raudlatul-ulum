<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$rounds = \App\Models\Round::all();
foreach ($rounds as $r) {
    echo "Round {$r->sequence}: Start: {$r->start_time} End: {$r->end_time} IsOpen: " . ($r->isOpen() ? 'true' : 'false') . " Now: " . now() . "\n";
}
