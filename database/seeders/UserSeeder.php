<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $professeurs = [
            ['name' => 'Francois Malanda', 'email' => 'francois@kimboo.net', 'ville' => 'Abidjan', 'role' => 'professeur'],
            ['name' => 'Aminata Koné', 'email' => 'aminata@kimboo.net', 'ville' => 'Abidjan', 'role' => 'professeur'],
            ['name' => 'Jean-Paul Atta', 'email' => 'jeanpaul@kimboo.net', 'ville' => 'Abidjan', 'role' => 'professeur'],
            ['name' => 'Sophie Traoré', 'email' => 'sophie@kimboo.net', 'ville' => 'Abidjan', 'role' => 'professeur'],
        ];

        foreach ($professeurs as $prof) {
            User::create([
                'name' => $prof['name'],
                'email' => $prof['email'],
                'password' => Hash::make('password123'),
                'role' => $prof['role'],
                'ville' => $prof['ville'],
                'phone' => '0700000000',
            ]);
        }

        // Un élève de test
        User::create([
            'name' => 'Élève Test',
            'email' => 'eleve@kimboo.net',
            'password' => Hash::make('password123'),
            'role' => 'eleve',
            'ville' => 'Abidjan',
            'phone' => '0700000001',
        ]);
    }
}
