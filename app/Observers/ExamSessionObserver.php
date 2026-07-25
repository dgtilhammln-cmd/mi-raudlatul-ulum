<?php

namespace App\Observers;

use App\Models\ExamSession;
use App\Services\BracketAdvancementService;

class ExamSessionObserver
{
    public function __construct(protected BracketAdvancementService $bracketService) {}

    /**
     * Triggered whenever an ExamSession is updated.
     * We check if:
     *   1. Status changed to submitted/auto_submitted (MCQ-only round)
     *   2. result_status changed to 'final' (all essays graded)
     *
     * In either case, try to auto-advance the round.
     */
    public function updated(ExamSession $session): void
    {
        $triggerStatuses = ['submitted', 'auto_submitted'];

        $statusChanged       = $session->isDirty('status') && in_array($session->status, $triggerStatuses);
        $resultStatusChanged = $session->isDirty('result_status') && $session->result_status === 'final';

        if ($statusChanged || $resultStatusChanged) {
            // Load round with event for the check
            $round = $session->round()->with('event')->first();
            if ($round) {
                $this->bracketService->tryAutoAdvance($round);
            }
        }
    }
}
