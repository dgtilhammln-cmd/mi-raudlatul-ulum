<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    /**
     * Tampilkan semua daftar event dan juara-juaranya (Halaman Public SEO-Friendly)
     */
    public function index()
    {
        $events = Event::with(['participants', 'rounds'])
            ->orderByDesc('start_date')
            ->get()
            ->map(function ($event) {
                $winners = collect();
                if ($event->leaderboard_visible) {
                    $winners = $event->getLeaderboard(3);
                }
                $event->winners = $winners;
                return $event;
            });

        return view('leaderboard.index', compact('events'));
    }

    /**
     * JSON API untuk leaderboard real-time (polling dari JS)
     */
    public function json(Event $event): JsonResponse
    {
        if (!$event->leaderboard_visible) {
            return response()->json(['visible' => false, 'data' => []]);
        }

        $leaderboard = $event->getLeaderboard(100);

        return response()->json([
            'visible'        => true,
            'scoring_system' => $event->scoring_system,
            'event_name'     => $event->name,
            'data'           => $leaderboard,
            'updated_at'     => now()->format('H:i:s'),
        ]);
    }

    /**
     * Halaman publik leaderboard standalone (untuk homepage embed)
     */
    public function public(Event $event)
    {
        abort_if(!$event->leaderboard_visible, 404);
        $leaderboard = $event->getLeaderboard(100);
        $rounds = $event->rounds()->withCount('examSessions')->get();
        $bracketData = $event->isQualificationSystem() ? $event->getBracketData() : null;
        return view('leaderboard.public', compact('event', 'leaderboard', 'rounds', 'bracketData'));
    }

    /**
     * Dashboard leaderboard spesifik event untuk penyelenggara
     */
    public function organizerEvent(Event $event)
    {
        abort_if($event->organizer_id !== Auth::id(), 403);
        $leaderboard = $event->getLeaderboard(100);
        return view('leaderboard.organizer_event', compact('event', 'leaderboard'));
    }

    /**
     * Dashboard leaderboard untuk penyelenggara
     * Menampilkan event aktif + riwayat semua event
     */
    public function organizerDashboard()
    {
        $organizer = Auth::user();

        // Event aktif (ongoing/published) dengan leaderboard visible
        $activeEvents = Event::where('organizer_id', $organizer->id)
            ->whereIn('status', ['ongoing', 'published'])
            ->where('leaderboard_visible', true)
            ->where('scoring_system', 'point')
            ->with('participants')
            ->orderByDesc('updated_at')
            ->get();

        // Semua event (termasuk selesai) sebagai riwayat
        $allEvents = Event::where('organizer_id', $organizer->id)
            ->withCount('participants')
            ->orderByDesc('created_at')
            ->get();

        // Load leaderboard untuk event aktif
        $activeLeaderboards = $activeEvents->mapWithKeys(function ($event) {
            return [$event->id => $event->getLeaderboard(50)];
        });

        return view('leaderboard.organizer', compact('activeEvents', 'allEvents', 'activeLeaderboards'));
    }

    /**
     * Dashboard leaderboard untuk peserta
     * Menampilkan event aktif + riwayat event yang pernah diikuti
     */
    public function pesertaDashboard()
    {
        $user = Auth::user();
        $participant = $user->participants()->has('event')->with('event')->latest()->first();

        if (!$participant) {
            return redirect()->route('peserta.dashboard')->withErrors(['error' => 'Anda belum terdaftar di event manapun.']);
        }

        // Semua event yang pernah diikuti peserta ini
        $allParticipations = $user->participants()
            ->has('event')
            ->with(['event' => fn($q) => $q->withCount('participants')])
            ->orderByDesc('created_at')
            ->get();

        // Event aktif yang leaderboard-nya visible
        $activeParticipations = $allParticipations->filter(fn($p) =>
            in_array($p->event->status, ['ongoing', 'published']) &&
            $p->event->leaderboard_visible &&
            $p->event->scoring_system === 'point'
        );

        $activeLeaderboards = $activeParticipations->mapWithKeys(function ($p) use ($user) {
            $lb = $p->event->getLeaderboard(50);
            $myEntry = $lb->firstWhere('participant_id', $p->id);
            $myRank  = $myEntry ? ($lb->search(fn($r) => $r['participant_id'] === $p->id) + 1) : null;
            return [$p->event->id => ['lb' => $lb, 'myRank' => $myRank, 'myEntry' => $myEntry]];
        });

        return view('leaderboard.peserta', compact('allParticipations', 'activeParticipations', 'activeLeaderboards'));
    }

    /**
     * Update kode akses peserta
     */
    public function updateAccessCode(\App\Models\Participant $participant)
    {
        request()->validate(['access_code' => 'required|min:6|max:20']);

        $code = request('access_code');
        $participant->update(['access_code' => $code]);
        $participant->user->update(['password' => \Illuminate\Support\Facades\Hash::make($code)]);

        return response()->json(['success' => true, 'message' => 'Kode akses berhasil diperbarui.']);
    }
}
