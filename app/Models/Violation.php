<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    public $timestamps = false;

    protected $fillable = ['session_id', 'type', 'occurred_at', 'metadata'];

    protected function casts(): array
    {
        return [
            'occurred_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    // Primary relationship
    public function session() { return $this->belongsTo(ExamSession::class, 'session_id'); }

    // Alias — some controllers/views use 'examSession'
    public function examSession() { return $this->belongsTo(ExamSession::class, 'session_id'); }
}
