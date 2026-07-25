<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    protected $fillable = [
        'event_id', 'name', 'sequence', 'start_time', 'end_time',
        'duration_minutes', 'max_questions', 'passing_score',
        'randomize_questions', 'randomize_options', 'allow_review',
        'warning_threshold', 'auto_submit_threshold', 'status',
        // Bracket / Qualification fields
        'round_type', 'advancement_limit', 'auto_advance', 'advancement_status',
    ];

    protected function casts(): array
    {
        return [
            'start_time'           => 'datetime',
            'end_time'             => 'datetime',
            'randomize_questions'  => 'boolean',
            'randomize_options'    => 'boolean',
            'allow_review'         => 'boolean',
            'auto_advance'         => 'boolean',
        ];
    }

    // ─── Round Type Labels ───────────────────────────────────────────────────

    public static function roundTypeLabel(string $type): string
    {
        return match($type) {
            'qualification' => 'Babak Kualifikasi',
            'group_stage'   => 'Babak Penyisihan',
            'round_of_64'   => '64 Besar',
            'round_of_32'   => '32 Besar',
            'quarter_final' => 'Perempat Final (8 Besar)',
            'semi_final'    => 'Semifinal (4 Besar)',
            'final'         => 'Grand Final',
            default         => 'Babak',
        };
    }

    public function getRoundTypeLabelAttribute(): string
    {
        return $this->round_type ? static::roundTypeLabel($this->round_type) : ($this->name ?? 'Babak');
    }

    // ─── Relationships ───────────────────────────────────────────────────────

    public function event() { return $this->belongsTo(Event::class); }

    public function questionBanks()
    {
        return $this->belongsToMany(QuestionBank::class, 'round_banks', 'round_id', 'bank_id')
                    ->withPivot('question_count');
    }

    public function examSessions() { return $this->hasMany(ExamSession::class); }

    public function participants()
    {
        return $this->belongsToMany(Participant::class, 'participant_round', 'round_id', 'participant_id')->withTimestamps();
    }

    // ─── Status Helpers ──────────────────────────────────────────────────────

    public function isOpen(): bool
    {
        return now()->gte($this->start_time) && now()->lte($this->end_time);
    }

    public function getStatusAttribute($value): string
    {
        if (now()->lt($this->start_time)) {
            return 'upcoming';
        } elseif (now()->gt($this->end_time)) {
            return 'completed';
        }
        return 'ongoing';
    }

    // ─── Advancement Helpers ─────────────────────────────────────────────────

    /**
     * Apakah babak ini punya soal esai?
     */
    public function hasEssayQuestions(): bool
    {
        return $this->questionBanks()
            ->whereHas('questions', fn($q) => $q->where('type', 'essay'))
            ->exists();
    }

    /**
     * Apakah semua soal esai sudah dinilai?
     */
    public function allEssaysGraded(): bool
    {
        return !ExamSession::where('round_id', $this->id)
            ->whereIn('status', ['submitted', 'auto_submitted'])
            ->whereHas('answers', function($q) {
                $q->where('essay_status', 'pending')
                  ->whereHas('question', fn($q2) => $q2->where('type', 'essay'));
            })
            ->exists();
    }

    /**
     * Apakah babak ini siap diproses advancementnya?
     * - Waktu ujian sudah selesai
     * - Semua peserta sudah submit (atau waktu habis)
     * - Jika ada esai: semua sudah dinilai
     */
    public function isReadyToAdvance(): bool
    {
        // Harus sudah melewati end_time
        if (now()->lt($this->end_time)) {
            return false;
        }

        // Harus belum diproses
        if ($this->advancement_status === 'done') {
            return false;
        }

        // Jika ada esai, semua harus sudah dinilai
        if ($this->hasEssayQuestions() && !$this->allEssaysGraded()) {
            return false;
        }

        return true;
    }

    /**
     * Ambil Top-N peserta dari babak ini berdasarkan total_score
     */
    public function getTopNParticipants(int $n): \Illuminate\Support\Collection
    {
        return ExamSession::where('round_id', $this->id)
            ->whereIn('status', ['submitted', 'auto_submitted'])
            ->with('participant.user')
            ->orderByDesc('total_score')
            ->take($n)
            ->get()
            ->map(fn($s) => $s->participant)
            ->filter();
    }

    /**
     * Hitung peserta yang akan lolos (preview sebelum konfirmasi)
     */
    public function previewAdvancement(): \Illuminate\Support\Collection
    {
        $limit = $this->advancement_limit ?? 0;
        if ($limit <= 0) return collect();
        return $this->getTopNParticipants($limit);
    }

    /**
     * Ambil babak berikutnya dalam event ini
     */
    public function nextRound(): ?Round
    {
        return Round::where('event_id', $this->event_id)
            ->where('sequence', $this->sequence + 1)
            ->first();
    }

    /**
     * Ambil total sesi yang sudah submit di babak ini
     */
    public function submittedSessionCount(): int
    {
        return $this->examSessions()
            ->whereIn('status', ['submitted', 'auto_submitted'])
            ->count();
    }

    /**
     * Jumlah esai yang belum dinilai
     */
    public function pendingEssayCount(): int
    {
        return \App\Models\Answer::whereHas('session', fn($q) => $q->where('round_id', $this->id))
            ->where('essay_status', 'pending')
            ->whereHas('question', fn($q) => $q->where('type', 'essay'))
            ->count();
    }
}
