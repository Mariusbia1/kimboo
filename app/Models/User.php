<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'avatar', 'role', 'ville', 'payment_method',
        'is_suspended', 'suspended_at', 'suspension_reason',
        'password', 'message_attempts', 'message_blocked_until',
        'last_login_at', 'time_spent_seconds',
    ];

    public const PAYMENT_METHODS = [
        'wave'         => 'Wave',
        'orange_money' => 'Orange Money',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_suspended' => 'boolean',
            'suspended_at' => 'datetime',
            'message_blocked_until' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    public function getFormattedTimeSpentAttribute(): string
    {
        $seconds = (int) ($this->time_spent_seconds ?? 0);
        if ($seconds < 60) {
            return $seconds > 0 ? "{$seconds}s" : '0 min';
        }
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        if ($hours > 0) {
            return "{$hours}h " . ($minutes > 0 ? "{$minutes}m" : '');
        }

        return "{$minutes} min";
    }

    public function teacherProfile()
    {
        return $this->hasOne(TeacherProfile::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function isProfesseur()
    {
        return $this->role === 'professeur';
    }

    public function isEleve()
    {
        return $this->role === 'eleve';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function suspend(?string $reason = null): void
    {
        $this->update([
            'is_suspended'      => true,
            'suspended_at'      => now(),
            'suspension_reason' => $reason,
        ]);
    }

    public function reactivate(): void
    {
        $this->update([
            'is_suspended'          => false,
            'suspended_at'          => null,
            'suspension_reason'     => null,
            'message_attempts'      => 0,
            'message_blocked_until' => null,
        ]);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
