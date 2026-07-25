<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\{Round, Answer, ExamSession};
use App\Services\ScoreCalculatorService;
use Illuminate\Http\Request;

class EssayGradingController extends Controller
{
    public function index(Round $round)
    {
        $sessions = $round->examSessions()
            ->where('result_status', 'essay_pending')
            ->with(['participant.user', 'answers' => fn($q) => $q->whereHas('question', fn($q2) => $q2->where('type', 'essay'))->with('question')])
            ->get();

        return view('organizer.grading.index', compact('round', 'sessions'));
    }

    public function grade(Request $request, Answer $answer)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:' . $answer->question->score,
            'feedback' => 'nullable|string|max:1000',
        ]);

        $answer->update([
            'score' => $request->score,
            'essay_feedback' => $request->feedback,
            'essay_status' => 'graded',
            'graded_at' => now(),
            'graded_by' => $request->user()->id,
        ]);

        // Cek apakah semua esai di sesi ini sudah dinilai
        $session = $answer->session;
        $pendingEssays = $session->answers()
            ->whereHas('question', fn($q) => $q->where('type', 'essay'))
            ->where('essay_status', 'pending')
            ->count();

        if ($pendingEssays === 0) {
            // Semua esai dinilai, finalisasi skor
            $calculator = new ScoreCalculatorService();
            $calculator->finalizeScore($session);

            // Coba auto-advance jika babak ini memenuhi syarat
            if ($session->round->auto_advance && $session->round->isReadyToAdvance()) {
                app(\App\Services\BracketAdvancementService::class)->tryAutoAdvance($session->round);
            }
        }

        return back()->with('success', 'Nilai esai berhasil disimpan.');
    }

    public function publishAll(Request $request, Round $round)
    {
        $sessions = $round->examSessions()->where('result_status', 'essay_pending')->get();
        $calculator = new ScoreCalculatorService();

        foreach ($sessions as $session) {
            $pendingEssays = $session->answers()
                ->whereHas('question', fn($q) => $q->where('type', 'essay'))
                ->where('essay_status', 'pending')
                ->count();

            if ($pendingEssays === 0) {
                $calculator->finalizeScore($session);
            }
        }

        // Coba auto-advance jika babak ini memenuhi syarat setelah publish
        if ($round->auto_advance && $round->isReadyToAdvance()) {
            app(\App\Services\BracketAdvancementService::class)->tryAutoAdvance($round);
        }

        return back()->with('success', 'Hasil yang sudah dinilai telah dipublish.');
    }
}
