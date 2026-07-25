<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'participant_id', 'session_id', 'event_id', 'certificate_number',
        'participant_name', 'event_name', 'total_score', 'rank',
        'file_path', 'downloaded_count', 'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'total_score' => 'decimal:2',
            'issued_at' => 'datetime',
        ];
    }

    public function participant() { return $this->belongsTo(Participant::class); }
    public function session() { return $this->belongsTo(ExamSession::class, 'session_id'); }
    public function event() { return $this->belongsTo(Event::class); }
}
