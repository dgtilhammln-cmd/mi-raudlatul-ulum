<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organizer_id', 'name', 'description', 'banner_url', 'category',
        'start_date', 'end_date', 'status', 'settings', 'poster_image',
        'scoring_system', 'leaderboard_visible', 'bracket_mode',
    ];

    protected $casts = [
        'start_date'          => 'datetime',
        'end_date'            => 'datetime',
        'settings'            => 'array',
        'leaderboard_visible' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = \Illuminate\Support\Str::slug($event->name . '-' . \Illuminate\Support\Str::random(4));
            }
        });

        static::updating(function ($event) {
            if ($event->isDirty('name')) {
                $event->slug = \Illuminate\Support\Str::slug($event->name . '-' . \Illuminate\Support\Str::random(4));
            }
        });
    }

    // ─── Relationships ───────────────────────────────────────────────────────

    public function organizer()     { return $this->belongsTo(User::class, 'organizer_id'); }
    public function rounds()        { return $this->hasMany(Round::class)->orderBy('sequence'); }
    public function questionBanks() { return $this->hasMany(QuestionBank::class); }
    public function participants()  { return $this->hasMany(Participant::class); }
    public function certificates()  { return $this->hasMany(Certificate::class); }

    // ─── Settings Helper ─────────────────────────────────────────────────────

    public function getSetting(string $key, $default = null)
    {
        return data_get($this->settings, $key, $default);
    }

    // ─── Scoring System Helpers ──────────────────────────────────────────────

    public function isPointSystem(): bool
    {
        return $this->scoring_system === 'point';
    }

    public function isQualificationSystem(): bool
    {
        return $this->scoring_system === 'qualification';
    }

    // ─── Bracket Helpers ─────────────────────────────────────────────────────

    /**
     * Label mode bracket
     */
    public function getBracketModeLabel(): string
    {
        return match($this->bracket_mode) {
            'full'    => 'Sistem Penuh (6 Babak)',
            'express' => 'Sistem Praktis (3 Babak)',
            default   => 'Kustom',
        };
    }

    /**
     * Template babak berdasarkan mode bracket
     */
    public function getBracketTemplate(): array
    {
        if ($this->bracket_mode === 'express') {
            return [
                ['sequence' => 1, 'round_type' => 'qualification', 'name' => 'Babak 1 (Penyisihan)',  'advancement_limit' => 150],
                ['sequence' => 2, 'round_type' => 'semi_final',    'name' => 'Babak 2 (Semifinal)',   'advancement_limit' => 75],
                ['sequence' => 3, 'round_type' => 'final',         'name' => 'Babak 3 (Grand Final)', 'advancement_limit' => 3],
            ];
        }

        // full mode — 6 babak
        return [
            ['sequence' => 1, 'round_type' => 'qualification', 'name' => 'Babak 1 (Penyisihan)',       'advancement_limit' => 150],
            ['sequence' => 2, 'round_type' => 'group_stage',   'name' => 'Babak 2 (64 Besar)',         'advancement_limit' => 64],
            ['sequence' => 3, 'round_type' => 'round_of_32',   'name' => 'Babak 3 (32 Besar)',         'advancement_limit' => 32],
            ['sequence' => 4, 'round_type' => 'quarter_final', 'name' => 'Babak 4 (Perempat Final)',   'advancement_limit' => 16],
            ['sequence' => 5, 'round_type' => 'semi_final',    'name' => 'Babak 5 (Semifinal)',        'advancement_limit' => 8],
            ['sequence' => 6, 'round_type' => 'final',         'name' => 'Babak 6 (Grand Final)',      'advancement_limit' => 3],
        ];
    }

    /**
     * Data bracket lengkap untuk diagram: setiap babak + info peserta + status
     */
    public function getBracketData(): array
    {
        $rounds = $this->rounds()->with([
            'participants.user',
            'examSessions' => fn($q) => $q->whereIn('status', ['submitted', 'auto_submitted'])->orderByDesc('total_score'),
        ])->get();

        // Lazy-trigger auto-advance for rounds that are finished but haven't been processed
        $advancementService = app(\App\Services\BracketAdvancementService::class);
        $examService = app(\App\Services\ExamService::class);
        
        foreach ($rounds as $round) {
            // Jika waktu ujian sudah habis, force submit semua sesi yang nyangkut (ongoing)
            if (now()->gt($round->end_time)) {
                $ongoingSessions = $round->examSessions()->where('status', 'ongoing')->get();
                foreach ($ongoingSessions as $session) {
                    try {
                        $examService->submitExam($session, true);
                    } catch (\Exception $e) {
                        // ignore error
                    }
                }
            }

            if ($round->auto_advance && $round->isReadyToAdvance()) {
                $advancementService->tryAutoAdvance($round);
            }
        }

        // Re-fetch rounds after potential advancement changes
        $rounds = $this->rounds()->with([
            'participants.user',
            'examSessions' => fn($q) => $q->whereIn('status', ['submitted', 'auto_submitted'])->orderByDesc('total_score'),
        ])->get();

        $previousRound = null;

        return $rounds->map(function (Round $round) use ($advancementService, &$previousRound) {
            // Peserta yang MASUK babak ini (assigned via participant_round)
            $entrants = $round->participants()->with('user')->get();
            $isProjected = false;

            // --- PROJECTION LOGIC ---
            // Jika babak ini belum ada peserta resmi, dan babak sebelumnya ada limit
            if ($entrants->isEmpty() && $previousRound && $previousRound->advancement_limit > 0) {
                $topN = $previousRound->getTopNParticipants($previousRound->advancement_limit);
                if ($topN->isNotEmpty()) {
                    $entrants = $topN;
                    $isProjected = true;
                }
            }

            // Skor per peserta di babak ini
            $scores = $round->examSessions->keyBy('participant_id');
            // Skor per peserta di babak sebelumnya (untuk proyeksi)
            $prevScores = $previousRound ? $previousRound->examSessions->keyBy('participant_id') : collect();

            // Status peserta di babak ini
            $slots = $entrants->map(function ($p) use ($scores, $prevScores, $round, $isProjected) {
                $session = $scores->get($p->id);
                $prevSession = $prevScores->get($p->id);
                $displayScore = $isProjected ? ($prevSession ? (float) $prevSession->total_score : null) : ($session ? (float) $session->total_score : null);
                
                return [
                    'participant_id'  => $p->id,
                    'name'            => $p->user->name ?? '—',
                    'institution'     => $p->institution ?? '—',
                    'major'           => $p->major ?? $p->grade ?? '—',
                    'avatar_url'      => $p->user->getAvatarUrl() ?? null,
                    'code'            => $p->participant_code,
                    'score'           => $displayScore,
                    'submitted'       => $session ? $session->isSubmitted() : false,
                    'is_eliminated'   => $isProjected ? false : ($p->eliminated_at_round === $round->sequence),
                    'is_champion'     => $isProjected ? false : $p->is_champion,
                    'is_projected'    => $isProjected,
                    'bracket_status'  => $isProjected ? 'projected' : $this->deriveSlotStatus($p, $round, $session),
                ];
            });

            if (!$isProjected) {
                $slots = $slots->sortByDesc('score');
            }
            
            $slots = $slots->values()->toArray();

            $previousRound = $round;

            return [
                'id'                 => $round->id,
                'sequence'           => $round->sequence,
                'round_type'         => $round->round_type,
                'round_type_label'   => $round->round_type_label,
                'name'               => $round->name,
                'start_time'         => $round->start_time?->toIso8601String(),
                'end_time'           => $round->end_time?->toIso8601String(),
                'start_time_label'   => $round->start_time?->translatedFormat('d M Y, H:i'),
                'status'             => $round->status,
                'advancement_limit'  => $round->advancement_limit,
                'advancement_status' => $round->advancement_status,
                'slots'              => $slots,
                'total_entrants'     => count($slots),
                'total_submitted'    => $round->submittedSessionCount(),
                'pending_essays'     => $round->pendingEssayCount(),
                'is_final_round'     => $round->round_type === 'final',
            ];
        })->toArray();
    }

    private function deriveSlotStatus(Participant $p, Round $round, $session): string
    {
        if ($p->is_champion) return 'champion';
        if ($p->eliminated_at_round === $round->sequence) return 'eliminated';
        if ($session && $session->isSubmitted()) return 'submitted';
        if ($round->isOpen()) return 'ongoing';
        if (now()->lt($round->start_time)) return 'upcoming';
        return 'pending';
    }

    /**
     * Siapa juara event ini?
     */
    public function getChampion(): ?Participant
    {
        return $this->participants()->where('is_champion', true)->with('user')->first();
    }

    // ─── Leaderboard (Point System) ──────────────────────────────────────────

    /**
     * Hitung leaderboard: total skor semua babak per peserta.
     */
    public function getLeaderboard(int $limit = 100): \Illuminate\Support\Collection
    {
        return Participant::where('event_id', $this->id)
            ->with(['user', 'examSessions' => function ($q) {
                $q->whereIn('status', ['submitted', 'auto_submitted'])
                  ->whereIn('result_status', ['pg_scored', 'essay_pending', 'final']);
            }])
            ->get()
            ->map(function ($p) {
                $totalScore = $p->examSessions->sum('total_score');
                $roundsDone = $p->examSessions->count();
                return [
                    'participant_id' => $p->id,
                    'name'           => $p->user->name,
                    'avatar_url'     => $p->user->getAvatarUrl(),
                    'institution'    => $p->institution ?? '—',
                    'grade'          => $p->grade ?? '—',
                    'major'          => $p->major ?? null,
                    'code'           => $p->participant_code,
                    'status'         => $p->status,
                    'total_score'    => round($totalScore, 1),
                    'rounds_done'    => $roundsDone,
                ];
            })
            ->sortByDesc('total_score')
            ->values()
            ->take($limit);
    }
}
