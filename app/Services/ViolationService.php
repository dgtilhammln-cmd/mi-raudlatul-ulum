<?php

namespace App\Services;

use App\Models\{ExamSession, Violation};

class ViolationService
{
    /**
     * Catat pelanggaran dan evaluasi threshold.
     *
     * @return array ['recorded' => bool, 'warning' => bool, 'auto_submit' => bool, 'total' => int]
     */
    public function recordViolation(ExamSession $session, string $type, array $metadata = []): array
    {
        // ═══ DEDUPLICATION ═══
        // Focus-loss events (tab_switch, window_blur, fullscreen_exit) all fire
        // simultaneously when a user switches tabs. We must only count them ONCE.
        $focusLossTypes = ['tab_switch', 'window_blur', 'fullscreen_exit'];

        if (in_array($type, $focusLossTypes)) {
            // Normalize all focus-loss events to 'tab_switch'
            $type = 'tab_switch';

            // Check if a focus-loss violation was already recorded within the last 5 seconds
            $recentFocusLoss = Violation::where('session_id', $session->id)
                ->whereIn('type', $focusLossTypes)
                ->where('occurred_at', '>=', now()->subSeconds(5))
                ->exists();

            if ($recentFocusLoss) {
                // Already counted this tab-switch action, return current state without incrementing
                $round = $session->round;
                $total = $session->violation_count;

                return [
                    'recorded' => false,
                    'warning'  => $total >= $round->warning_threshold && $total < $round->auto_submit_threshold,
                    'auto_submit' => false,
                    'total'    => $total,
                    'max'      => $round->auto_submit_threshold,
                ];
            }
        }

        Violation::create([
            'session_id' => $session->id,
            'type' => $type,
            'occurred_at' => now(),
            'metadata' => $metadata,
        ]);

        $session->increment('violation_count');
        $session->refresh();

        $round = $session->round;
        $total = $session->violation_count;

        $warning = $total >= $round->warning_threshold && $total < $round->auto_submit_threshold;
        $autoSubmit = $total >= $round->auto_submit_threshold;

        if ($autoSubmit && !$session->isSubmitted()) {
            $examService = new ExamService();
            $examService->submitExam($session, autoSubmit: true);
        }

        return [
            'recorded' => true,
            'warning' => $warning,
            'auto_submit' => $autoSubmit,
            'total' => $total,
            'max' => $round->auto_submit_threshold,
        ];
    }
}
