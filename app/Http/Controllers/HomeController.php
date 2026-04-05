<?php

namespace App\Http\Controllers;

use App\Models\TeacherProfile;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $professeurs = TeacherProfile::with(['user', 'courses'])
            ->orderBy('rating', 'desc')
            ->take(4)
            ->get();

        $categories = [
            ['emoji' => '📐', 'label' => 'Mathématiques'],
            ['emoji' => '🍳', 'label' => 'Cuisine'],
            ['emoji' => '⚽', 'label' => 'Sport'],
            ['emoji' => '🌍', 'label' => 'Langues'],
            ['emoji' => '🎵', 'label' => 'Musique'],
            ['emoji' => '💻', 'label' => 'Informatique'],
        ];

        return view('welcome', compact('professeurs', 'categories'));
    }

   public function profil($id)
{
    $profile = TeacherProfile::with(['user', 'courses', 'reviews.user'])->findOrFail($id);

    // Profils similaires — même catégorie, excluant le profil actuel
    $categorie = $profile->courses->first()->category ?? null;

    $similaires = TeacherProfile::with(['user', 'courses'])
        ->where('id', '!=', $id)
        ->when($categorie, function($q) use ($categorie) {
            $q->whereHas('courses', function($q2) use ($categorie) {
                $q2->where('category', $categorie);
            });
        })
        ->orderBy('rating', 'desc')
        ->take(3)
        ->get();

    return view('professeur.profil', compact('profile', 'similaires'));
}

    public function storeBooking(Request $request, $id)
{
    $request->validate([
        'course_id' => ['required', 'exists:courses,id'],
        'scheduled_at' => ['required', 'date', 'after:now'],
        'duration_hours' => ['required', 'integer', 'min:1', 'max:8'],
    ]);

    $course = \App\Models\Course::findOrFail($request->course_id);
    $totalPrice = $course->price_per_hour * $request->duration_hours;

    \App\Models\Booking::create([
        'user_id' => auth()->id(),
        'course_id' => $request->course_id,
        'scheduled_at' => $request->scheduled_at,
        'duration_hours' => $request->duration_hours,
        'total_price' => $totalPrice,
        'status' => 'en_attente',
    ]);

    return redirect()->route('professeur.profil', $id)
        ->with('success', 'Réservation envoyée avec succès !');
}


}
