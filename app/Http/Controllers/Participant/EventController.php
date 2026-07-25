<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Get all enrollments for the participant with their related event and certificates
        $participants = Participant::where('user_id', $user->id)
            ->has('event')
            ->with(['event', 'certificates'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('peserta.events.index', compact('participants'));
    }
}
