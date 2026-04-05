<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_profile_id', 'title', 'description',
        'category', 'level', 'format', 'price_per_hour', 'is_active',
        'is_active', 'is_group', 'max_students'
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
}
