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
        'first_course_free',
        'is_active', 'is_group', 'max_students',
        'lieu_cours', 'zone_deplacement',
        'status', 'rejection_reason',
    ];

    protected $appends = ['formatted_format', 'formatted_zone_deplacement'];

    protected $casts = [
        'lieu_cours' => 'array',
        'is_active' => 'boolean',
        'is_group' => 'boolean',
        'first_course_free' => 'boolean',
    ];

    public function getFormattedFormatAttribute(): string
    {
        return match ($this->format) {
            'Les deux' => 'En ligne & Présentiel',
            'En ligne' => 'En ligne',
            'Présentiel' => 'Présentiel',
            default => $this->format ?? 'En ligne & Présentiel',
        };
    }

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
