<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\{Participant, Round, ExamSession};
use App\Models\UserNotification;
use App\Services\{ExamService, ViolationService};
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function start(Request $request, Round $round)
    {
        $user = $request->user();
        $participant = Participant::where('user_id', $user->id)
            ->where('event_id', $round->event_id)
            ->firstOrFail();

        if (!$round->participants()->where('participants.id', $participant->id)->exists()) {
            return back()->withErrors(['exam' => 'Anda tidak memiliki akses untuk mengikuti babak ini.']);
        }

        try {
            $examService = new ExamService();
            $session = $examService->startExam($participant, $round);

            return redirect()->route('peserta.exam.show', $session->token);
        } catch (\Exception $e) {
            return back()->withErrors(['exam' => $e->getMessage()]);
        }
    }

    public function show(string $token)
    {
        $session = ExamSession::where('token', $token)
            ->with(['round', 'examQuestions.question.options', 'answers'])
            ->firstOrFail();

        // Cek apakah milik user yang login
        if ($session->participant->user_id !== auth()->id()) {
            abort(403);
        }

        // Jika sudah submit, redirect ke hasil
        if ($session->isSubmitted()) {
            return redirect()->route('peserta.result', $session->id);
        }

        // Cek waktu habis
        if ($session->getRemainingSeconds() <= 0) {
            $examService = new ExamService();
            $examService->submitExam($session, autoSubmit: true);
            return redirect()->route('peserta.result', $session->id);
        }

        $answers = $session->answers->keyBy('question_id');

        return view('peserta.exam.show', compact('session', 'answers'));
    }

    public function saveAnswer(Request $request, string $token)
    {
        $session = ExamSession::where('token', $token)->firstOrFail();

        if ($session->participant->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($session->isSubmitted()) {
            return response()->json(['error' => 'Ujian sudah selesai.'], 400);
        }

        $request->validate([
            'question_id' => 'required|integer',
            'option_id' => 'nullable|integer',
            'essay_answer' => 'nullable|string',
        ]);

        $examService = new ExamService();
        $answer = $examService->saveAnswer(
            $session,
            $request->question_id,
            $request->option_id,
            $request->essay_answer
        );

        return response()->json(['saved' => true, 'answered_at' => $answer->answered_at]);
    }

    public function submit(Request $request, string $token)
    {
        $session = ExamSession::where('token', $token)->firstOrFail();

        if ($session->participant->user_id !== auth()->id()) {
            abort(403);
        }

        $examService = new ExamService();
        $session = $examService->submitExam($session);
        $session->loadMissing('round');

        // Kirim notifikasi hasil ujian ke peserta
        UserNotification::send(
            userId:      auth()->id(),
            type:        'success',
            icon:        'fas fa-clipboard-check',
            title:       '✅ Ujian Berhasil Dikumpulkan!',
            body:        'Jawaban Anda pada babak "' . $session->round->name . '" telah berhasil dikumpulkan. Hasil dan skor Anda sudah tersedia.',
            actionUrl:   route('peserta.result', $session),
            actionLabel: 'Lihat Hasil Ujian'
        );

        return redirect()->route('peserta.exam.thankyou', $session->id);
    }

    public function violation(Request $request, string $token)
    {
        $session = ExamSession::where('token', $token)->firstOrFail();

        if ($session->isSubmitted()) {
            return response()->json(['auto_submit' => true]);
        }

        $request->validate([
            'type' => 'required|string',
        ]);

        $violationService = new ViolationService();
        $result = $violationService->recordViolation(
            $session,
            $request->type,
            $request->only(['detail', 'browser_info'])
        );

        return response()->json($result);
    }

    public function thankyou(ExamSession $session)
    {
        if ($session->participant->user_id !== auth()->id()) {
            abort(403);
        }

        $session->load(['round.event', 'participant.user']);

        return view('peserta.exam.thankyou', compact('session'));
    }
}
