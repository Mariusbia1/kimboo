<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_profile_id', 'title', 'description',
        'category', 'level', 'format', 'price_per_hour',
        'is_active', 'is_group', 'max_students',
        'lieu_cours', 'zone_deplacement',
        'status', 'rejection_reason',
    ];

protected $casts = [
    'lieu_cours' => 'array',
    'is_active' => 'boolean',
    'is_group' => 'boolean',
];
    public function teacherProfile()
    {
        return $this->belongsTo(TeacherProfile::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
