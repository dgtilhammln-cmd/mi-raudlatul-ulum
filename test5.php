<?php 
require __DIR__.'/vendor/autoload.php'; 
$app = require_once __DIR__.'/bootstrap/app.php'; 
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); 
$kernel->bootstrap(); 
$events = App\Models\Event::all(); 
foreach($events as $e) {
    echo "ID: {$e->id}, Name: {$e->name}, Slug: {$e->slug}, System: {$e->scoring_system}\n";
}
