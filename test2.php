<?php 
require __DIR__.'/vendor/autoload.php'; 
$app = require_once __DIR__.'/bootstrap/app.php'; 
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); 
$kernel->bootstrap(); 
$round = App\Models\Round::find(1); 
var_dump(app(App\Services\BracketAdvancementService::class)->tryAutoAdvance($round));
var_dump(App\Models\Round::find(1)->advancement_status);
