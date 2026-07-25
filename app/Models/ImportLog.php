<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    protected $fillable = [
        'event_id', 'organizer_id', 'filename', 'total_rows',
        'success_count', 'failed_count', 'errors', 'access_codes', 'status',
    ];

    protected function casts(): array
    {
        return [
            'errors' => 'array',
            'access_codes' => 'array',
        ];
    }

    public function event() { return $this->belongsTo(Event::class); }
    public function organizer() { return $this->belongsTo(User::class, 'organizer_id'); }
}
