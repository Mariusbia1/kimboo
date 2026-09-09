<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Standardiser les valeurs purement numériques dans teacher_profiles
        $profiles = DB::table('teacher_profiles')->whereNotNull('zone_deplacement')->get();
        foreach ($profiles as $profile) {
            $raw = trim((string)$profile->zone_deplacement);
            if (is_numeric($raw)) {
                $num = (float)$raw;
                $formatted = ($num > 100) ? "{$num} m" : "{$num} km";
                if ($num >= 1000 && $num % 1000 === 0) {
                    $formatted = ($num / 1000) . ' km';
                }
                DB::table('teacher_profiles')
                    ->where('id', $profile->id)
                    ->update(['zone_deplacement' => $formatted]);
            }
        }

        // Standardiser les valeurs purement numériques dans courses
        $courses = DB::table('courses')->whereNotNull('zone_deplacement')->get();
        foreach ($courses as $course) {
            $raw = trim((string)$course->zone_deplacement);
            if (is_numeric($raw)) {
                $num = (float)$raw;
                $formatted = ($num > 100) ? "{$num} m" : "{$num} km";
                if ($num >= 1000 && $num % 1000 === 0) {
                    $formatted = ($num / 1000) . ' km';
                }
                DB::table('courses')
                    ->where('id', $course->id)
                    ->update(['zone_deplacement' => $formatted]);
            }
        }
    }

    public function down(): void
    {
        // Pas d'annulation requise
    }
};
