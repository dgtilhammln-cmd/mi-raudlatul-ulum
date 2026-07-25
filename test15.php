<?php 
require __DIR__.'/vendor/autoload.php'; 
$app = require_once __DIR__.'/bootstrap/app.php'; 
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); 
$kernel->bootstrap(); 
$round = App\Models\Round::find(20); 
app(App\Services\BracketAdvancementService::class)->tryAutoAdvance($round);
echo "Status is now: " . App\Models\Round::find(20)->advancement_status;
