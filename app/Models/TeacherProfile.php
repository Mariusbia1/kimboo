<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'bio', 'hourly_rate', 'is_verified', 'is_featured',
        'first_course_free', 'rating', 'reviews_count',
        'experience_years', 'lieu_cours', 'a_propos_cours',
        'video_url', 'zone_deplacement', 'parcours_academique',
        'response_time'
    ];

    protected $appends = ['formatted_response_time', 'formatted_zone_deplacement'];

    protected $casts = [
        'lieu_cours' => 'array',
        'parcours_academique' => 'array',
        'is_verified' => 'boolean',
        'is_featured' => 'boolean',
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

    public function approvedCourses()
    {
        return $this->hasMany(Course::class)->approved();
    }

    public function reviews()
    {
        return $this->hasManyThrough(Review::class, Course::class);
    }

    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, Course::class);
    }

    public function nombreElevesUniques(): int
    {
        return (int) $this->bookings()
            ->distinct('bookings.user_id')
            ->count('bookings.user_id');
    }

    public function favoritedBy()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Retourne le temps de réponse formaté de façon explicite en minutes ou en heures.
     */
    public function getFormattedResponseTimeAttribute(): string
    {
        if (!$this->response_time) {
            return 'En quelques heures';
        }

        $val = (int) $this->response_time;
        if ($val < 60) {
            return $val . ' ' . ($val <= 1 ? 'minute' : 'minutes');
        }

        $hours = round($val / 60, 1);
        if ($hours == (int)$hours) {
            $hours = (int)$hours;
        }

        return $hours . ' ' . ($hours <= 1 ? 'heure' : 'heures');
    }

    /**
     * Retourne la zone de déplacement formatée clairement avec l'unité (km ou m).
     */
    public function getFormattedZoneDeplacementAttribute(): ?string
    {
        if (!$this->zone_deplacement) {
            return null;
        }

        $zone = trim((string)$this->zone_deplacement);
        if ($zone === '') {
            return null;
        }

        if (is_numeric($zone)) {
            $num = (float)$zone;
            if ($num > 100) {
                return $num . ' m';
            }
            return $num . ' km';
        }

        return $zone;
    }

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('homepage_professeurs');
            \Illuminate\Support\Facades\Cache::forget('homepage_meilleur_prof');
        });
        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('homepage_professeurs');
            \Illuminate\Support\Facades\Cache::forget('homepage_meilleur_prof');
        });
    }
}