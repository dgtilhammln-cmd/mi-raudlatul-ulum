<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSession extends Model
{
    protected $fillable = [
        'participant_id', 'round_id', 'token', 'started_at', 'submitted_at',
        'status', 'ip_address', 'user_agent', 'violation_count',
        'score_pg', 'score_essay', 'total_score',
        'correct_count', 'wrong_count', 'unanswered_count',
        'result_status', 'result_published_at', 'rank',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
            'result_published_at' => 'datetime',
            'score_pg' => 'decimal:2',
            'score_essay' => 'decimal:2',
            'total_score' => 'decimal:2',
        ];
    }

    public function participant() { return $this->belongsTo(Participant::class); }
    public function round() { return $this->belongsTo(Round::class); }
    public function examQuestions() { return $this->hasMany(ExamQuestion::class, 'session_id')->orderBy('display_order'); }
    public function answers() { return $this->hasMany(Answer::class, 'session_id'); }
    public function violations() { return $this->hasMany(Violation::class, 'session_id'); }
    public function certificate() { return $this->hasOne(Certificate::class, 'session_id'); }

    public function isSubmitted(): bool
    {
        return in_array($this->status, ['submitted', 'auto_submitted']);
    }

    public function isFinal(): bool
    {
        return $this->result_status === 'final';
    }

    public function hasEssay(): bool
    {
        return $this->examQuestions()
            ->whereHas('question', fn($q) => $q->where('type', 'essay'))
            ->exists();
    }

    public function getRemainingSeconds(): int
    {
        if (!$this->started_at) return $this->round->duration_minutes * 60;
        $endTime = $this->started_at->addMinutes($this->round->duration_minutes);
        return max(0, now()->diffInSeconds($endTime, false));
    }
}
