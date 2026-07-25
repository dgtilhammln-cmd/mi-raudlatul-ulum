<?php

namespace App\Services;

use App\Models\{ExamSession, Answer};

class ScoreCalculatorService
{
    /**
     * Hitung skor PG otomatis setelah submit.
     */
    public function calculatePGScore(ExamSession $session): array
    {
        $examQuestions = $session->examQuestions()->with('question')->get();
        $answers = $session->answers()->get()->keyBy('question_id');

        $scorePg = 0;
        $correctCount = 0;
        $wrongCount = 0;
        $unansweredCount = 0;

        foreach ($examQuestions as $eq) {
            $question = $eq->question;

            if ($question->type !== 'multiple_choice') continue;

            $answer = $answers->get($question->id);

            if (!$answer || !$answer->selected_option_id) {
                $unansweredCount++;
                continue;
            }

            if ($answer->is_correct) {
                $correctCount++;
                $scorePg += $question->score;
            } else {
                $wrongCount++;
                $scorePg -= $question->negative_score;
            }
        }

        // Count unanswered essay too
        foreach ($examQuestions as $eq) {
            if ($eq->question->type === 'essay') {
                $answer = $answers->get($eq->question->id);
                if (!$answer || empty($answer->essay_answer)) {
                    $unansweredCount++;
                }
            }
        }

        return [
            'score_pg' => max(0, $scorePg),
            'correct_count' => $correctCount,
            'wrong_count' => $wrongCount,
            'unanswered_count' => $unansweredCount,
        ];
    }

    /**
     * Finalisasi skor setelah esai dinilai.
     */
    public function finalizeScore(ExamSession $session): void
    {
        $essayScore = $session->answers()
            ->whereHas('question', fn($q) => $q->where('type', 'essay'))
            ->where('essay_status', 'graded')
            ->sum('score');

        $session->update([
            'score_essay' => $essayScore,
            'total_score' => $session->score_pg + $essayScore,
            'result_status' => 'final',
            'result_published_at' => now(),
        ]);
    }
}
