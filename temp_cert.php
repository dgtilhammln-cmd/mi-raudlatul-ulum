<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$e = \App\Models\Event::where('slug', 'olimpiade-mti-2026-sistem-kualifikasi-u0t8')->first();
$link = 'https://drive.google.com/file/d/12KfvzoyiQGRQkQqsRfNIVJOATMXuIJ6_/view?usp=sharing';

foreach ($e->participants as $p) {
    $p->certificate_link = $link;
    $p->save();
}

echo "Certificate links updated!\n";
