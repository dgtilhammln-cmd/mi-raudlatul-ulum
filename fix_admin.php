<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain; charset=utf-8');

// Cek apakah admin sudah ada
$existing = DB::table('users')->where('email', 'admin@miraudlatululum')->first();

if ($existing) {
    // Update password jika sudah ada
    DB::table('users')->where('email', 'admin@miraudlatululum')->update([
        'password' => bcrypt('miraudlatululum'),
        'role' => 'organizer',
        'is_active' => true,
        'email_verified_at' => now(),
        'updated_at' => now(),
    ]);
    echo "✅ Admin sudah ada, password direset.\n";
    echo "Email: admin@miraudlatululum\n";
    echo "Password: miraudlatululum\n";
} else {
    // Buat baru
    DB::table('users')->insert([
        'name' => 'Admin MI Raudlatul Ulum',
        'email' => 'admin@miraudlatululum',
        'password' => bcrypt('miraudlatululum'),
        'role' => 'organizer',
        'is_active' => true,
        'email_verified_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "✅ Admin baru berhasil dibuat!\n";
    echo "Email: admin@miraudlatululum\n";
    echo "Password: miraudlatululum\n";
}

echo "\n=== Daftar semua Admin/Organizer ===\n";
$admins = DB::table('users')->where('role', 'organizer')->get(['id','name','email','is_active']);
foreach ($admins as $a) {
    $status = $a->is_active ? '✅ Aktif' : '❌ Nonaktif';
    echo "ID:{$a->id} | {$a->name} | {$a->email} | {$status}\n";
}
