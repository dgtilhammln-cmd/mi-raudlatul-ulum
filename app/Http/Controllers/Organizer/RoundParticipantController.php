<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\{Round, Participant};
use Illuminate\Http\Request;

class RoundParticipantController extends Controller
{
    public function index(Round $round)
    {
        $event = $round->event;
        $allParticipants = $event->participants()->with('user')->get()->sortBy(function($p) {
            return $p->user->name ?? '';
        });
        $assignedParticipantIds = $round->participants()->pluck('participants.id')->toArray();

        return view('organizer.rounds.participants', compact('round', 'event', 'allParticipants', 'assignedParticipantIds'));
    }

    public function sync(Request $request, Round $round)
    {
        $request->validate([
            'participant_ids' => 'array',
            'participant_ids.*' => 'exists:participants,id'
        ]);

        $round->participants()->sync($request->participant_ids ?? []);

        return back()->with('success', 'Akses Peserta Babak berhasil disimpan!');
    }
}
