<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $cours = [
            [
                'email' => 'francois@kimboo.net',
                'title' => 'Cours de Lingala',
                'description' => 'Apprenez le Lingala de zéro avec une méthode éprouvée.',
                'category' => 'Langues',
                'level' => 'Tous niveaux',
                'format' => 'Présentiel',
                'price_per_hour' => 15000,
            ],
            [
                'email' => 'aminata@kimboo.net',
                'title' => 'Mathématiques lycée',
                'description' => 'Algèbre, géométrie et préparation aux examens.',
                'category' => 'Mathématiques',
                'level' => 'Lycée',
                'format' => 'En ligne',
                'price_per_hour' => 12000,
            ],
            [
                'email' => 'jeanpaul@kimboo.net',
                'title' => 'Cuisine ivoirienne',
                'description' => 'Apprenez à cuisiner les plats traditionnels ivoiriens.',
                'category' => 'Cuisine',
                'level' => 'Débutant',
                'format' => 'Présentiel',
                'price_per_hour' => 10000,
            ],
            [
                'email' => 'sophie@kimboo.net',
                'title' => 'Anglais conversationnel',
                'description' => 'Gagnez en confiance à l\'oral et préparez le TOEFL.',
                'category' => 'Langues',
                'level' => 'Intermédiaire',
                'format' => 'En ligne',
                'price_per_hour' => 8000,
            ],
        ];

        foreach ($cours as $c) {
            $user = User::where('email', $c['email'])->first();
            if ($user && $user->teacherProfile) {
                Course::create([
                    'teacher_profile_id' => $user->teacherProfile->id,
                    'title' => $c['title'],
                    'description' => $c['description'],
                    'category' => $c['category'],
                    'level' => $c['level'],
                    'format' => $c['format'],
                    'price_per_hour' => $c['price_per_hour'],
                    'is_active' => true,
                ]);
            }
        }
    }
}
