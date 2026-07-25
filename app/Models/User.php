<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'participant_id', 'password', 'role', 'is_active', 'last_login_at',
        'last_seen_at', 'avatar_path',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ── Scopes ──
    public function scopeOrganizers($query) { return $query->where('role', 'organizer'); }
    public function scopeParticipants($query) { return $query->where('role', 'participant'); }

    // ── Helpers ──
    public function isOrganizer(): bool { return $this->role === 'organizer'; }
    public function isParticipant(): bool { return $this->role === 'participant'; }

    public function isOnline(): bool
    {
        return $this->last_seen_at && $this->last_seen_at->diffInMinutes(now()) < 5;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatar_path ? asset('storage/' . $this->avatar_path) : null;
    }

    // ── Relations ──
    public function events()
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function notifications()
    {
        return $this->hasMany(\App\Models\UserNotification::class);
    }

    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->whereNull('read_at')->count();
    }
}
