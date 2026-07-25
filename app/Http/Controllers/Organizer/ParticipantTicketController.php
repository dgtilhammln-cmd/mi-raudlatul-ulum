<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\ParticipantTicket;
use Illuminate\Http\Request;

class ParticipantTicketController extends Controller
{
    public function index()
    {
        $tickets = ParticipantTicket::latest()->paginate(20);
        return view('organizer.tickets.index', compact('tickets'));
    }

    public function close(ParticipantTicket $ticket)
    {
        $ticket->update(['status' => 'closed']);
        return back()->with('success', 'Tiket kendala berhasil ditutup.');
    }
}
