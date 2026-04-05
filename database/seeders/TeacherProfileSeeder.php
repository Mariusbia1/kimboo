<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\TeacherProfile;

class TeacherProfileSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = [
            [
                'email' => 'francois@kimboo.net',
                'bio' => 'Confiance + méthode = résultats | 10 ans d\'xp | 2000h de cours',
                'hourly_rate' => 15000,
                'is_verified' => true,
                'first_course_free' => true,
                'rating' => 4.9,
                'reviews_count' => 43,
                'experience_years' => '10 ans',
            ],
            [
                'email' => 'aminata@kimboo.net',
                'bio' => 'Spécialiste lycée et université | Cours en ligne disponibles',
                'hourly_rate' => 12000,
                'is_verified' => true,
                'first_course_free' => true,
                'rating' => 4.8,
                'reviews_count' => 31,
                'experience_years' => '7 ans',
            ],
            [
                'email' => 'jeanpaul@kimboo.net',
                'bio' => 'Chef professionnel | Cours particuliers et groupes',
                'hourly_rate' => 10000,
                'is_verified' => true,
                'first_course_free' => false,
                'rating' => 5.0,
                'reviews_count' => 18,
                'experience_years' => '15 ans',
            ],
            [
                'email' => 'sophie@kimboo.net',
                'bio' => 'Certifiée TOEFL | Débutants et intermédiaires bienvenus',
                'hourly_rate' => 8000,
                'is_verified' => false,
                'first_course_free' => true,
                'rating' => 4.7,
                'reviews_count' => 56,
                'experience_years' => '5 ans',
            ],
        ];

        foreach ($profiles as $profile) {
            $user = User::where('email', $profile['email'])->first();
            if ($user) {
                TeacherProfile::create([
                    'user_id' => $user->id,
                    'bio' => $profile['bio'],
                    'hourly_rate' => $profile['hourly_rate'],
                    'is_verified' => $profile['is_verified'],
                    'first_course_free' => $profile['first_course_free'],
                    'rating' => $profile['rating'],
                    'reviews_count' => $profile['reviews_count'],
                    'experience_years' => $profile['experience_years'],
                ]);
            }
        }
    }
}
