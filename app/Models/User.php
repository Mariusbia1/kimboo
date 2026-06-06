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
        'password', 'message_attempts', 'message_blocked_until',
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
            'message_blocked_until' => 'datetime',
        ];
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
    public function favorites()
{
    return $this->hasMany(Favorite::class);
}
}
