<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\ParticipantTicket;
use App\Models\WebSetting;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'wa_number' => 'required|string|max:20',
            'needs' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        ParticipantTicket::create([
            'name' => $request->name,
            'wa_number' => $request->wa_number,
            'needs' => $request->needs,
            'message' => $request->message,
            'status' => 'open'
        ]);

        return response()->json(['success' => true, 'redirect' => null]);
    }
}
