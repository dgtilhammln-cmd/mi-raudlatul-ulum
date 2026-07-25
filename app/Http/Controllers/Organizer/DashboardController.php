<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\{Event, ExamSession};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $events = $user->events()->withCount('participants', 'rounds')->latest()->get();

        $totalParticipants = $events->sum('participants_count');
        $totalEvents = $events->count();
        $activeEvents = $events->where('status', 'ongoing')->count();

        // Sesi ujian yang sedang berjalan
        $activeSessions = ExamSession::where('status', 'ongoing')
            ->whereHas('round.event', fn($q) => $q->where('organizer_id', $user->id))
            ->count();

        // Esai yang belum dinilai
        $pendingEssays = \App\Models\Answer::where('essay_status', 'pending')
            ->whereHas('question', fn($q) => $q->where('type', 'essay'))
            ->whereHas('session.round.event', fn($q) => $q->where('organizer_id', $user->id))
            ->count();

        return view('organizer.dashboard', compact(
            'events', 'totalParticipants', 'totalEvents', 'activeEvents',
            'activeSessions', 'pendingEssays'
        ));
    }
}
