<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = $request->user()->events()
            ->withCount('participants', 'rounds', 'questionBanks')
            ->latest()
            ->paginate(10);

        return view('organizer.events.index', compact('events'));
    }

    public function create()
    {
        return view('organizer.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:255',
            'description'         => 'nullable|string',
            'category'            => 'nullable|string|max:100',
            'start_date'          => 'required|date',
            'end_date'            => 'required|date|after_or_equal:start_date',
            'poster_image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'scoring_system'      => 'required|in:qualification,point',
            'bracket_mode'        => 'nullable|in:full,express|required_if:scoring_system,qualification',
            'leaderboard_visible' => 'nullable|boolean',
            'anti_cheat_enabled'  => 'nullable|boolean',
        ]);

        $validated['organizer_id'] = $request->user()->id;

        if ($request->hasFile('poster_image')) {
            $validated['poster_image'] = $request->file('poster_image')->store('events/posters', 'public');
        }

        $validated['leaderboard_visible'] = $request->boolean('leaderboard_visible', true);

        $validated['settings'] = [
            'show_score_immediately'   => true,
            'show_answer_review'       => false,
            'essay_review_hours'       => 24,
            'certificate_auto_publish' => true,
            'anti_cheat_enabled'       => $request->boolean('anti_cheat_enabled', true),
        ];

        $event = Event::create($validated);

        if ($event->isQualificationSystem()) {
            return redirect()->route('organizer.events.bracket.wizard', $event)
                ->with('success', 'Event berhasil dibuat! Silakan setup babak kualifikasi.');
        }

        return redirect()->route('organizer.events.show', $event)
            ->with('success', 'Event berhasil dibuat!');
    }

    public function show(Event $event)
    {
        $this->authorizeEvent($event);
        $event->load(['rounds', 'participants.user', 'questionBanks.questions']);

        return view('organizer.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $this->authorizeEvent($event);
        return view('organizer.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorizeEvent($event);

        $validated = $request->validate([
            'name'                => 'required|string|max:255',
            'description'         => 'nullable|string',
            'category'            => 'nullable|string|max:100',
            'start_date'          => 'required|date',
            'end_date'            => 'required|date|after_or_equal:start_date',
            'status'              => 'required|in:draft,published,ongoing,completed,cancelled',
            'poster_image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'scoring_system'      => 'required|in:qualification,point',
            'bracket_mode'        => 'nullable|in:full,express|required_if:scoring_system,qualification',
            'leaderboard_visible' => 'nullable|boolean',
            'anti_cheat_enabled'  => 'nullable|boolean',
        ]);

        if ($request->hasFile('poster_image')) {
            if ($event->poster_image) {
                Storage::disk('public')->delete($event->poster_image);
            }
            $validated['poster_image'] = $request->file('poster_image')->store('events/posters', 'public');
        }

        $validated['leaderboard_visible'] = $request->boolean('leaderboard_visible', true);
        
        $settings = $event->settings ?? [];
        $settings['anti_cheat_enabled'] = $request->boolean('anti_cheat_enabled', true);
        $validated['settings'] = $settings;

        $event->update($validated);

        return redirect()->route('organizer.events.show', $event)
            ->with('success', 'Event berhasil diperbarui!');
    }

    public function destroy(Event $event)
    {
        $this->authorizeEvent($event);
        $event->delete();

        return redirect()->route('organizer.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }

    private function authorizeEvent(Event $event): void
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke event ini.');
        }
    }
}
