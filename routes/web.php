<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Organizer;
use App\Http\Controllers\Participant;
use App\Http\Controllers\LeaderboardController;

// ═══════════════════════════════════════════════
// LANDING PAGE
// ═══════════════════════════════════════════════
Route::get('/debug-advancement', function() {
    $event = \App\Models\Event::where('scoring_system', 'qualification')->orderBy('id', 'desc')->first();
    $round = $event->rounds()->orderBy('sequence')->first();
    $participant = $event->participants()->whereHas('user', fn($q) => $q->where('name', 'like', '%Hamzah%'))->first();
    $session = \App\Models\ExamSession::where('round_id', $round->id)->where('participant_id', $participant->id)->first();
    
    $preview = app(\App\Services\BracketAdvancementService::class)->previewAdvancement($round);

    return [
        'event_id' => $event->id,
        'round_id' => $round->id,
        'participant_id' => $participant->id,
        'round_info' => [
            'advancement_status' => $round->advancement_status,
            'end_time' => $round->end_time,
            'isReadyToAdvance' => $round->isReadyToAdvance(),
            'auto_advance' => $round->auto_advance,
            'advancement_limit' => $round->advancement_limit,
            'has_essay' => $round->hasEssayQuestions(),
            'all_essays_graded' => $round->allEssaysGraded(),
            'now' => now()->toIso8601String()
        ],
        'hamzah_participant' => [
            'eliminated_at_round' => $participant->eliminated_at_round,
            'current_round_sequence' => $participant->current_round_sequence,
            'status' => $participant->status,
            'event_scoring_system' => $participant->event->scoring_system,
            'is_qualification' => $participant->event->isQualificationSystem(),
        ],
        'hamzah_session' => [
            'status' => $session ? $session->status : null,
            'total_score' => $session ? $session->total_score : null,
        ],
        'preview' => [
            'will_advance_count' => count($preview['will_advance']),
            'will_eliminate_count' => count($preview['will_eliminate']),
            'hamzah_in_advance' => collect($preview['will_advance'])->where('id', 1)->isNotEmpty()
        ]
    ];
});

Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('home');
Route::get('/sitemap.xml', function() {
    return response()->view('sitemap')->header('Content-Type', 'text/xml');
});

// Public Leaderboard (Standalone)
Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
Route::get('/leaderboard/{event:slug}', [LeaderboardController::class, 'public'])->name('leaderboard.public');
Route::get('/api/leaderboard/{event:slug}', [LeaderboardController::class, 'json'])->name('leaderboard.json');

// ═══════════════════════════════════════════════
// AUTH
// ═══════════════════════════════════════════════
Route::middleware('guest')->group(function () {
    // Peserta
    Route::get('/dashboard/login', [LoginController::class, 'showParticipantLogin'])->name('login');
    Route::post('/dashboard/login', [LoginController::class, 'loginParticipant']);

    // Admin / Penyelenggara
    Route::get('/admin/login', [LoginController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/admin/login', [LoginController::class, 'loginAdmin']);
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// ═══════════════════════════════════════════════
// NOTIFICATIONS (auth required)
// ═══════════════════════════════════════════════
Route::middleware('auth')->prefix('api/notifications')->name('notifications.')->group(function () {
    Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
    Route::post('/{notification}/read', [App\Http\Controllers\NotificationController::class, 'markRead'])->name('read');
    Route::post('/read-all', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('read-all');
});

// ═══════════════════════════════════════════════
// ORGANIZER ROUTES
// ═══════════════════════════════════════════════
Route::middleware(['auth', 'organizer'])->prefix('organizer')->name('organizer.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Organizer\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/statistik', [App\Http\Controllers\Organizer\StatisticController::class, 'index'])->name('statistik.index');
    Route::get('/artikel', function() { return view('organizer.upgrade', ['feature' => 'Artikel']); })->name('artikel.index');
    Route::get('/anggota', function() { return view('organizer.upgrade', ['feature' => 'Anggota']); })->name('anggota.index');
    Route::resource('landing-images', Organizer\LandingImageController::class)->only(['index', 'store', 'destroy']);

    // Events
    Route::resource('events', Organizer\EventController::class);
    Route::get('/events/{event:slug}/leaderboard', [LeaderboardController::class, 'organizerEvent'])->name('events.leaderboard');
    Route::get('/leaderboard', [LeaderboardController::class, 'organizerDashboard'])->name('leaderboard');
    Route::patch('/participants/{participant}/access-code', [LeaderboardController::class, 'updateAccessCode'])->name('participants.update-access-code');

    // Bracket (Qualification System)
    Route::get('/events/{event}/bracket', [Organizer\BracketController::class, 'index'])->name('events.bracket');
    Route::get('/events/{event}/bracket/wizard', [Organizer\BracketController::class, 'setupWizard'])->name('events.bracket.wizard');
    Route::post('/events/{event}/bracket/wizard', [Organizer\BracketController::class, 'storeWizard'])->name('events.bracket.wizard.store');
    Route::post('/rounds/{round}/advance', [Organizer\BracketController::class, 'manualAdvance'])->name('rounds.advance');
    Route::get('/rounds/{round}/advance-preview', [Organizer\BracketController::class, 'previewAdvance'])->name('rounds.advance.preview');
    Route::patch('/rounds/{round}/schedule', [Organizer\BracketController::class, 'updateSchedule'])->name('rounds.schedule.update');
    Route::get('/api/events/{event}/bracket-json', [Organizer\BracketController::class, 'bracketJson'])->name('events.bracket.json');

    // Rounds (nested under events)
    Route::post('/events/{event}/rounds', [Organizer\RoundController::class, 'store'])->name('rounds.store');
    Route::put('/rounds/{round}', [Organizer\RoundController::class, 'update'])->name('rounds.update');
    Route::delete('/rounds/{round}', [Organizer\RoundController::class, 'destroy'])->name('rounds.destroy');
    
    // Round Participants
    Route::get('/rounds/{round}/participants', [Organizer\RoundParticipantController::class, 'index'])->name('rounds.participants');
    Route::post('/rounds/{round}/participants/sync', [Organizer\RoundParticipantController::class, 'sync'])->name('rounds.participants.sync');

    // Question Banks
    Route::get('/events/{event}/questions', [Organizer\QuestionBankController::class, 'index'])->name('questions.index');
    Route::post('/events/{event}/questions/banks', [Organizer\QuestionBankController::class, 'store'])->name('questions.banks.store');
    Route::get('/questions/banks/{bank}', [Organizer\QuestionBankController::class, 'show'])->name('questions.bank.show');
    Route::post('/questions/banks/{bank}/questions', [Organizer\QuestionBankController::class, 'storeQuestion'])->name('questions.store');
    Route::post('/questions/banks/{bank}/import', [Organizer\QuestionBankController::class, 'importQuestion'])->name('questions.import');
    Route::delete('/questions/{question}', [Organizer\QuestionBankController::class, 'destroyQuestion'])->name('questions.destroy');

    // Participants & Import
    Route::get('/participants/template', [Organizer\ParticipantController::class, 'template'])->name('participants.template');
    Route::get('/events/{event}/participants', [Organizer\ParticipantController::class, 'index'])->name('participants.index');
    Route::post('events/{event}/participants/import', [Organizer\ParticipantController::class, 'import'])->name('participants.import');
    Route::delete('events/{event}/participants/destroy-all', [Organizer\ParticipantController::class, 'destroyAll'])->name('participants.destroyAll');
    Route::get('/events/{event}/import/{importLog}/result', [Organizer\ParticipantController::class, 'importResult'])->name('participants.import.result');
    Route::get('/events/{event}/import/{importLog}/export', [Organizer\ParticipantController::class, 'exportAccessList'])->name('participants.export-access');
    Route::put('/participants/{participant}', [Organizer\ParticipantController::class, 'update'])->name('participants.update');
    Route::delete('/participants/{participant}', [Organizer\ParticipantController::class, 'destroy'])->name('participants.destroy');

    // Essay Grading
    Route::get('/rounds/{round}/grading', [Organizer\EssayGradingController::class, 'index'])->name('grading.index');
    Route::post('/grading/{answer}', [Organizer\EssayGradingController::class, 'grade'])->name('grading.grade');
    Route::post('/rounds/{round}/grading/publish', [Organizer\EssayGradingController::class, 'publishAll'])->name('grading.publish');

    // Web Settings (Logos, Instagram, Footer)
    Route::post('web-settings/site-logo', [App\Http\Controllers\Organizer\WebSettingsController::class, 'updateSiteLogo'])->name('web-settings.site-logo.update');
    Route::post('web-settings/site-favicon', [App\Http\Controllers\Organizer\WebSettingsController::class, 'updateSiteFavicon'])->name('web-settings.site-favicon.update');
    Route::get('web-settings/logos', [App\Http\Controllers\Organizer\WebSettingsController::class, 'logos'])->name('web-settings.logos');
    Route::post('web-settings/logos', [App\Http\Controllers\Organizer\WebSettingsController::class, 'storeLogo'])->name('web-settings.logos.store');
    Route::delete('web-settings/logos/{logo}', [App\Http\Controllers\Organizer\WebSettingsController::class, 'destroyLogo'])->name('web-settings.logos.destroy');

    Route::get('web-settings/instagram', [App\Http\Controllers\Organizer\WebSettingsController::class, 'instagram'])->name('web-settings.instagram');
    Route::post('web-settings/instagram', [App\Http\Controllers\Organizer\WebSettingsController::class, 'storeInstagram'])->name('web-settings.instagram.store');
    Route::delete('web-settings/instagram/{feed}', [App\Http\Controllers\Organizer\WebSettingsController::class, 'destroyInstagram'])->name('web-settings.instagram.destroy');

    Route::get('web-settings/footer', [App\Http\Controllers\Organizer\WebSettingsController::class, 'footer'])->name('web-settings.footer');
    Route::post('web-settings/footer', [App\Http\Controllers\Organizer\WebSettingsController::class, 'updateFooter'])->name('web-settings.footer.update');

    Route::get('web-settings/hero', [App\Http\Controllers\Organizer\WebSettingsController::class, 'hero'])->name('web-settings.hero');
    Route::post('web-settings/hero', [App\Http\Controllers\Organizer\WebSettingsController::class, 'updateHero'])->name('web-settings.hero.update');

    // Certificates
    Route::get('/certificates', [Organizer\CertificateController::class, 'index'])->name('certificates.index');
    Route::post('/certificates/import', [Organizer\CertificateController::class, 'import'])->name('certificates.import');
    Route::put('/certificates/{participant}', [Organizer\CertificateController::class, 'update'])->name('certificates.update');
    Route::delete('/certificates/{participant}', [Organizer\CertificateController::class, 'destroy'])->name('certificates.destroy');
    Route::delete('/certificates/destroy-all/{event}', [Organizer\CertificateController::class, 'destroyAll'])->name('certificates.destroyAll');

    // Reports
    Route::get('/rounds/{round}/ranking', [Organizer\ReportController::class, 'ranking'])->name('reports.ranking');
    Route::get('/rounds/{round}/violations', [Organizer\ReportController::class, 'violations'])->name('reports.violations');

    // Participant Tickets
    Route::get('/tickets', [Organizer\ParticipantTicketController::class, 'index'])->name('tickets.index');
    Route::post('/tickets/{ticket}/close', [Organizer\ParticipantTicketController::class, 'close'])->name('tickets.close');
});

// ═══════════════════════════════════════════════
// PARTICIPANT ROUTES
// ═══════════════════════════════════════════════
Route::middleware(['auth', 'participant'])->prefix('peserta')->name('peserta.')->group(function () {

    Route::get('/dashboard', [Participant\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/profile/avatar', [Participant\ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::get('/leaderboard', [LeaderboardController::class, 'pesertaDashboard'])->name('leaderboard');
    Route::get('/leaderboard/{event:slug}', [LeaderboardController::class, 'public'])->name('leaderboard.event');
    Route::get('/events', [Participant\EventController::class, 'index'])->name('events');
    Route::get('/events/{event:slug}/bracket', [Participant\BracketViewController::class, 'show'])->name('bracket.show');

    // Exam
    Route::post('/exam/{round}/start', [Participant\ExamController::class, 'start'])->name('exam.start');
    Route::get('/exam/{token}', [Participant\ExamController::class, 'show'])->name('exam.show');
    Route::post('/exam/{token}/save', [Participant\ExamController::class, 'saveAnswer'])->name('exam.save');
    Route::post('/exam/{token}/submit', [Participant\ExamController::class, 'submit'])->name('exam.submit');
    Route::post('/exam/{token}/violation', [Participant\ExamController::class, 'violation'])->name('exam.violation');
    Route::get('/exam/{session}/thankyou', [Participant\ExamController::class, 'thankyou'])->name('exam.thankyou');

    // Results
    Route::get('/result/{session}', [Participant\ResultController::class, 'show'])->name('result');
    Route::get('/certificate/{certificate}/download', [Participant\ResultController::class, 'downloadCertificate'])->name('certificate.download');

    // Contact
    Route::post('/tickets/store', [Participant\TicketController::class, 'store'])->name('tickets.store');
});
