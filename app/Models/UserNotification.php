<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    protected $fillable = ['user_id', 'type', 'icon', 'title', 'body', 'action_url', 'action_label', 'read_at'];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    // ── Relations ──
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ──
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    // ── Helpers ──
    public static function send(int $userId, string $type, string $icon, string $title, string $body, ?string $actionUrl = null, ?string $actionLabel = null): self
    {
        return self::create([
            'user_id'      => $userId,
            'type'         => $type,
            'icon'         => $icon,
            'title'        => $title,
            'body'         => $body,
            'action_url'   => $actionUrl,
            'action_label' => $actionLabel,
        ]);
    }

    public function markRead(): void
    {
        $this->update(['read_at' => now()]);
    }
}
