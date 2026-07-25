<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\{Event, Round};
use Illuminate\Http\Request;

class RoundController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'duration_minutes' => 'required|integer|min:5|max:480',
            'max_questions' => 'required|integer|min:1|max:200',
            'round_type' => 'nullable|string',
            'advancement_limit' => 'nullable|integer|min:1',
            'auto_advance' => 'nullable|boolean',
            'randomize_questions' => 'nullable',
            'randomize_options' => 'nullable',
            'warning_threshold' => 'nullable|integer|min:1|max:20',
            'auto_submit_threshold' => 'nullable|integer|min:2|max:30',
            'bank_id' => 'nullable|exists:question_banks,id',
        ], [
            'name.required' => 'Nama babak wajib diisi.',
            'start_time.required' => 'Waktu mulai wajib diisi.',
            'start_time.date' => 'Format waktu mulai tidak valid.',
            'end_time.required' => 'Waktu selesai wajib diisi.',
            'end_time.date' => 'Format waktu selesai tidak valid.',
        ]);

        $startTime = \Carbon\Carbon::parse($request->start_time);
        $endTime = \Carbon\Carbon::parse($request->end_time);

        if ($endTime->lte($startTime)) {
            return back()->withInput()->withErrors(['end_time' => 'Waktu selesai harus setelah waktu mulai.']);
        }

        $round = Round::create([
            'event_id' => $event->id,
            'sequence' => $event->rounds()->max('sequence') + 1,
            'name' => $request->name,
            'round_type' => $request->round_type,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration_minutes' => $request->duration_minutes,
            'max_questions' => $request->max_questions,
            'advancement_limit' => $request->advancement_limit,
            'auto_advance' => $request->boolean('auto_advance', true),
            'randomize_questions' => $request->boolean('randomize_questions'),
            'randomize_options' => $request->boolean('randomize_options'),
            'warning_threshold' => $request->warning_threshold ?? 3,
            'auto_submit_threshold' => $request->auto_submit_threshold ?? 5,
        ]);

        if ($request->filled('bank_id')) {
            $round->questionBanks()->attach($request->bank_id, ['question_count' => $round->max_questions]);
        }

        // Auto-sync existing participants to the first round
        if ($round->sequence == 1) {
            $participantIds = $event->participants()->pluck('participants.id')->toArray();
            $round->participants()->syncWithoutDetaching($participantIds);
        }

        return redirect()->route('organizer.events.show', $event)
            ->with('success', 'Babak "' . $round->name . '" berhasil ditambahkan!');
    }

    public function update(Request $request, Round $round)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'duration_minutes' => 'required|integer|min:5|max:480',
            'max_questions' => 'required|integer|min:1|max:200',
            'round_type' => 'nullable|string',
            'advancement_limit' => 'nullable|integer|min:1',
            'auto_advance' => 'nullable|boolean',
            'warning_threshold' => 'nullable|integer|min:1|max:20',
            'auto_submit_threshold' => 'nullable|integer|min:2|max:30',
            'bank_id' => 'nullable|exists:question_banks,id',
        ], [
            'name.required' => 'Nama babak wajib diisi.',
            'start_time.required' => 'Waktu mulai wajib diisi.',
            'end_time.required' => 'Waktu selesai wajib diisi.',
        ]);

        $startTime = \Carbon\Carbon::parse($request->start_time);
        $endTime = \Carbon\Carbon::parse($request->end_time);

        if ($endTime->lte($startTime)) {
            return back()->withInput()->withErrors(['end_time' => 'Waktu selesai harus setelah waktu mulai.']);
        }

        $round->update([
            'name' => $request->name,
            'round_type' => $request->round_type,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration_minutes' => $request->duration_minutes,
            'max_questions' => $request->max_questions,
            'advancement_limit' => $request->advancement_limit,
            'auto_advance' => $request->boolean('auto_advance', true),
            'randomize_questions' => $request->boolean('randomize_questions'),
            'randomize_options' => $request->boolean('randomize_options'),
            'warning_threshold' => $request->warning_threshold ?? 3,
            'auto_submit_threshold' => $request->auto_submit_threshold ?? 5,
        ]);

        if ($request->filled('bank_id')) {
            $round->questionBanks()->sync([
                $request->bank_id => ['question_count' => $round->max_questions]
            ]);
        } else {
            $round->questionBanks()->detach();
        }

        return redirect()->route('organizer.events.show', $round->event)
            ->with('success', 'Babak berhasil diperbarui!');
    }

    public function destroy(Round $round)
    {
        $event = $round->event;
        $round->delete();

        return redirect()->route('organizer.events.show', $event)
            ->with('success', 'Babak berhasil dihapus.');
    }
}
