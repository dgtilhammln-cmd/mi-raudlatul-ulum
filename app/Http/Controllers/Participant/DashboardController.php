<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\{Participant, ExamSession};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $participants = Participant::where('user_id', $user->id)
            ->has('event')
            ->with(['event.rounds' => function ($query) {
                $query->orderBy('sequence');
            }, 'examSessions'])
            ->get();

        $advancementService = app(\App\Services\BracketAdvancementService::class);
        foreach ($participants as $participant) {
            if ($participant->event && $participant->event->isQualificationSystem()) {
                foreach ($participant->event->rounds as $round) {
                    if ($round->auto_advance && $round->isReadyToAdvance()) {
                        $advancementService->tryAutoAdvance($round);
                    }
                }
            }
        }

        // Re-fetch to get updated statuses
        $participants = Participant::where('user_id', $user->id)
            ->has('event')
            ->with(['event.rounds' => function ($query) {
                $query->orderBy('sequence');
            }, 'examSessions'])
            ->get();

        return view('peserta.dashboard', compact('participants'));
    }
}
