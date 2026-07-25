<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\{Event, Round, ExamSession, Violation};
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function ranking(Round $round)
    {
        // Pastikan organizer_id cocok
        if ($round->event->organizer_id !== auth()->id()) {
            abort(403);
        }

        $sessions = ExamSession::where('round_id', $round->id)
            ->whereIn('status', ['submitted', 'auto_submitted'])
            ->with(['participant.user', 'violations'])
            ->orderByDesc('total_score')
            ->orderBy('submitted_at')
            ->get();

        return view('organizer.reports.ranking', compact('round', 'sessions'));
    }

    public function violations(Round $round)
    {
        if ($round->event->organizer_id !== auth()->id()) {
            abort(403);
        }

        $violations = Violation::whereHas('examSession', function($q) use ($round) {
                $q->where('round_id', $round->id);
            })
            ->with(['examSession.participant.user'])
            ->latest()
            ->paginate(50);

        return view('organizer.reports.violations', compact('round', 'violations'));
    }
}
