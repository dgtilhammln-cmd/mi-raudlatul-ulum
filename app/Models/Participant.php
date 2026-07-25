<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = [
        'event_id', 'user_id', 'participant_code', 'access_code',
        'institution', 'grade', 'major', 'status', 'certificate_link',
        // Bracket tracking
        'current_round_sequence', 'eliminated_at_round', 'is_champion',
    ];

    protected $casts = [
        'is_champion' => 'boolean',
    ];

    public function event() { return $this->belongsTo(Event::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function examSessions() { return $this->hasMany(ExamSession::class); }
    public function certificates() { return $this->hasMany(Certificate::class); }

    public function sessionForRound(int $roundId)
    {
        return $this->examSessions()->where('round_id', $roundId)->first();
    }

    // ─── Bracket Helpers ─────────────────────────────────────────────────────

    public function isEliminated(): bool
    {
        return $this->eliminated_at_round !== null && !$this->is_champion;
    }

    public function isChampion(): bool
    {
        return (bool) $this->is_champion;
    }

    /**
     * Apakah peserta aktif (belum gugur) di sistem bracket?
     */
    public function isActiveInBracket(): bool
    {
        return $this->eliminated_at_round === null && !$this->is_champion;
    }

    /**
     * Babak berapa peserta ini sekarang aktif di event
     */
    public function getCurrentRoundLabel(): string
    {
        if ($this->is_champion) return '🏆 Juara';
        if ($this->isEliminated()) {
            $seq = $this->eliminated_at_round;
            return "Gugur di Babak {$seq}";
        }
        $seq = $this->current_round_sequence;
        return $seq > 0 ? "Babak ke-{$seq}" : 'Belum Mulai';
    }
}
