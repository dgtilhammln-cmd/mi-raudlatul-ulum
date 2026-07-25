<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    public $timestamps = false;

    protected $fillable = ['session_id', 'question_id', 'display_order', 'shuffled_options'];

    protected function casts(): array
    {
        return ['shuffled_options' => 'array'];
    }

    public function session() { return $this->belongsTo(ExamSession::class, 'session_id'); }
    public function question() { return $this->belongsTo(Question::class); }
}
