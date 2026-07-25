<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain; charset=utf-8');

echo "=== RESET DATA: EVENT, SOAL, PESERTA ===\n\n";

DB::statement('SET FOREIGN_KEY_CHECKS=0');

// 1. Hapus data ujian (jawaban & sesi)
$answers = DB::table('answers')->count();
DB::table('answers')->delete();
echo "✅ Hapus Answers: {$answers} data\n";

$sessions = DB::table('exam_sessions')->count();
DB::table('exam_sessions')->delete();
echo "✅ Hapus Exam Sessions: {$sessions} data\n";

// 2. Hapus participant_round
$pr = DB::table('participant_round')->count();
DB::table('participant_round')->delete();
echo "✅ Hapus Participant Round: {$pr} data\n";

// 3. Hapus sertifikat peserta
$certs = DB::table('certificates')->count();
DB::table('certificates')->delete();
echo "✅ Hapus Certificates: {$certs} data\n";

// 4. Hapus tiket peserta
if (DB::getSchemaBuilder()->hasTable('participant_tickets')) {
    $tickets = DB::table('participant_tickets')->count();
    DB::table('participant_tickets')->delete();
    echo "✅ Hapus Tickets: {$tickets} data\n";
}

// 5. Hapus peserta (role=participant user)
$partUsers = DB::table('participants')->pluck('user_id')->toArray();
$partCount = DB::table('participants')->count();
DB::table('participants')->delete();
echo "✅ Hapus Participants: {$partCount} data\n";

// Hapus user role participant
$delUsers = DB::table('users')->where('role', 'participant')->delete();
echo "✅ Hapus User Peserta: {$delUsers} akun\n";

// 6. Hapus Round Banks
$rb = DB::table('round_banks')->count();
DB::table('round_banks')->delete();
echo "✅ Hapus Round Banks: {$rb} data\n";

// 7. Hapus Rounds
$rounds = DB::table('rounds')->count();
DB::table('rounds')->delete();
echo "✅ Hapus Rounds: {$rounds} data\n";

// 8. Hapus Soal/Questions
$questions = DB::table('questions')->count();
DB::table('questions')->delete();
echo "✅ Hapus Questions: {$questions} data\n";

// 9. Hapus Question Options
if (DB::getSchemaBuilder()->hasTable('options')) {
    $options = DB::table('options')->count();
    DB::table('options')->delete();
    echo "✅ Hapus Options: {$options} data\n";
}

// 10. Hapus Question Banks
$banks = DB::table('question_banks')->count();
DB::table('question_banks')->delete();
echo "✅ Hapus Question Banks: {$banks} data\n";

// 11. Hapus Events (termasuk soft deleted)
$events = DB::table('events')->count();
DB::table('events')->delete();
echo "✅ Hapus Events: {$events} data\n";

// 12. Hapus Import Logs jika ada
if (DB::getSchemaBuilder()->hasTable('import_logs')) {
    DB::table('import_logs')->delete();
    echo "✅ Hapus Import Logs\n";
}

// 13. Hapus Notifications
if (DB::getSchemaBuilder()->hasTable('notifications')) {
    DB::table('notifications')->delete();
    echo "✅ Hapus Notifications\n";
}

DB::statement('SET FOREIGN_KEY_CHECKS=1');

echo "\n=== SELESAI! ===\n";
echo "Data yang DIPERTAHANKAN:\n";
echo "- Akun Organizer/Admin\n";
echo "- Pengaturan Website (Logo, Foto, dll)\n";
$admins = DB::table('users')->where('role', 'organizer')->get(['id','name','email']);
echo "\nAdmin yang tersisa:\n";
foreach ($admins as $a) {
    echo "  ID:{$a->id} | {$a->name} | {$a->email}\n";
}
