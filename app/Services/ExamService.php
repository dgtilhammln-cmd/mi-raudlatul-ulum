<?php

namespace App\Services;

use App\Models\{ExamSession, ExamQuestion, Round, Participant, Question, Option, Answer};
use Illuminate\Support\{Str, Collection};
use Illuminate\Support\Facades\DB;

class ExamService
{
    /**
     * Mulai sesi ujian untuk peserta di babak tertentu.
     */
    public function startExam(Participant $participant, Round $round): ExamSession
    {
        // Cek apakah sudah ada sesi
        $existing = ExamSession::where('participant_id', $participant->id)
            ->where('round_id', $round->id)
            ->first();

        if ($existing && $existing->isSubmitted()) {
            throw new \Exception('Anda sudah menyelesaikan ujian ini.');
        }

        if ($existing && $existing->status === 'ongoing') {
            return $existing; // Resume sesi
        }

        // Cek waktu
        if (!$round->isOpen()) {
            throw new \Exception('Babak ujian belum dibuka atau sudah ditutup.');
        }

        return DB::transaction(function () use ($participant, $round) {
            // Buat session
            $session = ExamSession::create([
                'participant_id' => $participant->id,
                'round_id' => $round->id,
                'token' => Str::random(64),
                'started_at' => now(),
                'status' => 'ongoing',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Ambil soal dari bank yang terhubung
            $questions = $this->getRandomizedQuestions($round);

            // Buat exam_questions
            foreach ($questions as $order => $question) {
                $shuffledOptions = null;
                if ($question->isMultipleChoice() && $round->randomize_options) {
                    $shuffledOptions = $question->options->pluck('id')->shuffle()->values()->toArray();
                } elseif ($question->isMultipleChoice()) {
                    $shuffledOptions = $question->options->pluck('id')->toArray();
                }

                ExamQuestion::create([
                    'session_id' => $session->id,
                    'question_id' => $question->id,
                    'display_order' => $order + 1,
                    'shuffled_options' => $shuffledOptions,
                ]);
            }

            $session->update(['unanswered_count' => count($questions)]);

            return $session;
        });
    }

    /**
     * Ambil soal acak dari bank soal yang terhubung ke babak.
     */
    private function getRandomizedQuestions(Round $round): Collection
    {
        $questions = collect();

        foreach ($round->questionBanks as $bank) {
            $count = $bank->pivot->question_count;
            $bankQuestions = $bank->questions()->with('options')->inRandomOrder()->limit($count)->get();
            $questions = $questions->merge($bankQuestions);
        }

        if ($round->randomize_questions) {
            $questions = $questions->shuffle();
        }

        return $questions->take($round->max_questions);
    }

    /**
     * Simpan jawaban (auto-save).
     */
    public function saveAnswer(ExamSession $session, int $questionId, ?int $optionId = null, ?string $essayAnswer = null): Answer
    {
        $question = Question::findOrFail($questionId);

        $data = [
            'answered_at' => now(),
        ];

        if ($question->isMultipleChoice() && $optionId) {
            $option = Option::findOrFail($optionId);
            $data['selected_option_id'] = $optionId;
            $data['is_correct'] = $option->is_correct;
            $data['score'] = $option->is_correct ? $question->score : -$question->negative_score;
        } elseif ($question->isEssay()) {
            $data['essay_answer'] = $essayAnswer;
            $data['essay_status'] = 'pending';
        }

        return Answer::updateOrCreate(
            ['session_id' => $session->id, 'question_id' => $questionId],
            $data
        );
    }

    /**
     * Submit ujian (manual atau auto).
     */
    public function submitExam(ExamSession $session, bool $autoSubmit = false): ExamSession
    {
        if ($session->isSubmitted()) {
            return $session;
        }

        return DB::transaction(function () use ($session, $autoSubmit) {
            // Hitung skor PG
            $scoreCalculator = new ScoreCalculatorService();
            $scores = $scoreCalculator->calculatePGScore($session);

            $hasEssay = $session->examQuestions()
                ->whereHas('question', fn($q) => $q->where('type', 'essay'))
                ->exists();

            $session->update([
                'submitted_at' => now(),
                'status' => $autoSubmit ? 'auto_submitted' : 'submitted',
                'score_pg' => $scores['score_pg'],
                'correct_count' => $scores['correct_count'],
                'wrong_count' => $scores['wrong_count'],
                'unanswered_count' => $scores['unanswered_count'],
                'total_score' => $scores['score_pg'], // Sementara, tunggu esai
                'result_status' => $hasEssay ? 'essay_pending' : 'pg_scored',
            ]);

            // Jika tidak ada esai, langsung finalize
            if (!$hasEssay) {
                $session->update([
                    'result_status' => 'final',
                    'result_published_at' => now(),
                ]);
            }

            return $session->fresh();
        });
    }
}
