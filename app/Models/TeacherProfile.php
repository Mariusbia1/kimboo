<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'bio', 'hourly_rate', 'is_verified',
        'first_course_free', 'rating', 'reviews_count',
        'experience_years', 'lieu_cours', 'a_propos_cours',
        'video_url', 'zone_deplacement'
    ];

    protected $casts = [
        'lieu_cours' => 'array',
        'is_verified' => 'boolean',
        'first_course_free' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function reviews()
    {
        return $this->hasManyThrough(Review::class, Course::class);
    }
}