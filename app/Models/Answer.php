<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'session_id', 'question_id', 'selected_option_id',
        'is_correct', 'score', 'essay_answer', 'essay_feedback',
        'essay_status', 'graded_at', 'graded_by', 'answered_at',
    ];

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
            'score' => 'decimal:2',
            'graded_at' => 'datetime',
            'answered_at' => 'datetime',
        ];
    }

    public function session() { return $this->belongsTo(ExamSession::class, 'session_id'); }
    public function question() { return $this->belongsTo(Question::class); }
    public function selectedOption() { return $this->belongsTo(Option::class, 'selected_option_id'); }
    public function grader() { return $this->belongsTo(User::class, 'graded_by'); }

    public function isGraded(): bool { return $this->essay_status === 'graded'; }
}
