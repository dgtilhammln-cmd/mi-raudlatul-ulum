<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\{Event, Participant};

class BracketViewController extends Controller
{
    public function show(Event $event)
    {
        $user = auth()->user();

        // Find the participant record for this user in this event
        $myParticipant = Participant::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$myParticipant && !$event->isQualificationSystem()) {
            abort(404);
        }

        $bracketData = $event->getBracketData();
        $champion    = $event->getChampion();

        return view('peserta.bracket.show', compact('event', 'bracketData', 'champion', 'myParticipant'));
    }
}
