<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Imports\CertificateImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $events = $request->user()->events()->latest()->get();
        
        $participants = null;
        $selectedEvent = null;
        
        if ($request->has('event_id')) {
            $selectedEvent = Event::where('organizer_id', auth()->id())->findOrFail($request->event_id);
            $participants = \App\Models\Participant::where('event_id', $selectedEvent->id)
                ->whereNotNull('certificate_link')
                ->latest()
                ->paginate(20);
        }

        return view('organizer.certificates.index', compact('events', 'participants', 'selectedEvent'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        // Ensure user owns the event
        $event = Event::findOrFail($request->event_id);
        if ($event->organizer_id !== auth()->id()) {
            abort(403);
        }

        try {
            $import = new CertificateImport($event->id);
            Excel::import($import, $request->file('file'));
            
            return redirect()->route('organizer.certificates.index', ['event_id' => $event->id])
                ->with('success', "Berhasil mengimpor dan memperbarui " . $import->successCount . " data link sertifikat peserta.");
        } catch (\Exception $e) {
            return back()->withErrors(['import' => 'Terjadi kesalahan saat import: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, \App\Models\Participant $participant)
    {
        $request->validate([
            'certificate_link' => 'required|url|max:255'
        ]);

        if ($participant->event->organizer_id !== auth()->id()) abort(403);

        $participant->update(['certificate_link' => $request->certificate_link]);
        return redirect()->route('organizer.certificates.index', ['event_id' => $participant->event_id])
            ->with('success', 'Link sertifikat berhasil diperbarui.');
    }

    public function destroy(\App\Models\Participant $participant)
    {
        if ($participant->event->organizer_id !== auth()->id()) abort(403);

        $participant->update(['certificate_link' => null]);
        return redirect()->route('organizer.certificates.index', ['event_id' => $participant->event_id])
            ->with('success', 'Link sertifikat peserta berhasil dihapus.');
    }

    public function destroyAll(Event $event)
    {
        if ($event->organizer_id !== auth()->id()) abort(403);

        \App\Models\Participant::where('event_id', $event->id)->update(['certificate_link' => null]);
        return redirect()->route('organizer.certificates.index', ['event_id' => $event->id])
            ->with('success', 'Semua link sertifikat untuk event ini berhasil dihapus (di-reset).');
    }
}
