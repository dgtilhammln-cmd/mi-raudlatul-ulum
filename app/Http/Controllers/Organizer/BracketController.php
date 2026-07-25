<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\{Event, Round};
use App\Services\BracketAdvancementService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BracketController extends Controller
{
    public function __construct(protected BracketAdvancementService $bracketService) {}

    // ─── Setup Wizard ────────────────────────────────────────────────────────

    /**
     * Show the bracket setup wizard (configure all rounds at once)
     */
    public function setupWizard(Event $event)
    {
        $this->authorizeEvent($event);

        if (!$event->isQualificationSystem()) {
            return redirect()->route('organizer.events.show', $event)
                ->withErrors(['error' => 'Event ini tidak menggunakan sistem kualifikasi.']);
        }

        $template    = $event->getBracketTemplate();
        $existingRounds = $event->rounds()->orderBy('sequence')->get();

        return view('organizer.events.bracket-wizard', compact('event', 'template', 'existingRounds'));
    }

    /**
     * Store all rounds from wizard in one go
     */
    public function storeWizard(Request $request, Event $event)
    {
        $this->authorizeEvent($event);

        $request->validate([
            'rounds'                     => 'required|array|min:1',
            'rounds.*.name'              => 'required|string|max:255',
            'rounds.*.round_type'        => 'required|string',
            'rounds.*.start_time'        => 'required|date',
            'rounds.*.end_time'          => 'required|date',
            'rounds.*.duration_minutes'  => 'required|integer|min:5|max:480',
            'rounds.*.max_questions'     => 'required|integer|min:1|max:200',
            'rounds.*.advancement_limit' => 'nullable|integer|min:1',
        ]);

        // Delete existing rounds if re-configuring
        $event->rounds()->delete();

        foreach ($request->rounds as $seq => $data) {
            $start = Carbon::parse($data['start_time']);
            $end   = Carbon::parse($data['end_time']);

            Round::create([
                'event_id'           => $event->id,
                'sequence'           => $seq + 1,
                'name'               => $data['name'],
                'round_type'         => $data['round_type'],
                'start_time'         => $start,
                'end_time'           => $end,
                'duration_minutes'   => (int) $data['duration_minutes'],
                'max_questions'      => (int) $data['max_questions'],
                'advancement_limit'  => isset($data['advancement_limit']) ? (int) $data['advancement_limit'] : null,
                'auto_advance'       => true,
                'randomize_questions' => true,
                'randomize_options'  => true,
                'warning_threshold'  => 3,
                'auto_submit_threshold' => 5,
            ]);
        }

        // Add all existing participants to first round automatically
        $firstRound = $event->rounds()->orderBy('sequence')->first();
        if ($firstRound) {
            $participantIds = $event->participants()->pluck('id')->toArray();
            $firstRound->participants()->syncWithoutDetaching($participantIds);
        }

        return redirect()->route('organizer.events.bracket', $event)
            ->with('success', 'Semua babak berhasil dikonfigurasi!');
    }

    // ─── Bracket Dashboard ───────────────────────────────────────────────────

    /**
     * Show the interactive bracket diagram for organizer
     */
    public function index(Event $event)
    {
        $this->authorizeEvent($event);
        $event->load(['rounds.participants.user']);

        $bracketData = $event->getBracketData();
        $champion    = $event->getChampion();

        return view('organizer.events.bracket', compact('event', 'bracketData', 'champion'));
    }

    /**
     * JSON endpoint for realtime bracket polling
     */
    public function bracketJson(Event $event)
    {
        // Allow both organizer and participant to poll this
        $bracketData = $event->getBracketData();
        $champion    = $event->getChampion();

        return response()->json([
            'bracket'  => $bracketData,
            'champion' => $champion ? [
                'name'        => $champion->user->name ?? '—',
                'institution' => $champion->institution ?? '—',
                'avatar_url'  => $champion->user->getAvatarUrl() ?? null,
            ] : null,
            'updated_at' => now()->toIso8601String(),
        ]);
    }

    // ─── Manual Advance ──────────────────────────────────────────────────────

    /**
     * Admin manually triggers advancement for a round
     */
    public function manualAdvance(Request $request, Round $round)
    {
        $event = $round->event;
        $this->authorizeEvent($event);

        if (!$event->isQualificationSystem()) {
            return back()->withErrors(['error' => 'Event ini tidak menggunakan sistem kualifikasi.']);
        }

        $result = $this->bracketService->executeAdvancement($round);

        return back()->with('success',
            "{$result['advanced']} peserta lolos ke babak berikutnya. {$result['eliminated']} peserta gugur."
        );
    }

    /**
     * Preview who will advance before committing
     */
    public function previewAdvance(Round $round)
    {
        $this->authorizeEvent($round->event);

        $preview = $this->bracketService->previewAdvancement($round);

        return response()->json($preview);
    }

    // ─── Quick Schedule Update ───────────────────────────────────────────────

    /**
     * Update a single round's schedule from the bracket page (with notification)
     */
    public function updateSchedule(Request $request, Round $round)
    {
        $this->authorizeEvent($round->event);

        $request->validate([
            'start_time' => 'required|date',
            'end_time'   => 'required|date|after:start_time',
        ]);

        $oldTime = $round->start_time?->translatedFormat('d M Y, H:i') ?? '—';
        $newTime = Carbon::parse($request->start_time)->translatedFormat('d M Y, H:i');

        $round->update([
            'start_time' => Carbon::parse($request->start_time),
            'end_time'   => Carbon::parse($request->end_time),
        ]);

        // Notify participants about schedule change
        $this->bracketService->notifyScheduleChange($round, $oldTime, $newTime);

        return back()->with('success', "Jadwal {$round->name} berhasil diperbarui dan notifikasi telah dikirim ke peserta.");
    }

    // ─── Helper ──────────────────────────────────────────────────────────────

    private function authorizeEvent(Event $event): void
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403, 'Akses tidak diizinkan.');
        }
    }
}
