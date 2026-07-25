<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

foreach(\App\Models\Event::all() as $event) {
    $firstRound = $event->rounds()->orderBy('sequence')->first();
    if ($firstRound) {
        $firstRound->participants()->syncWithoutDetaching($event->participants()->pluck('id')->toArray());
        echo "Synced participants for event: " . $event->name . "\n";
    }
}
