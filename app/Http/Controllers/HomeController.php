<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Course;
use App\Models\Favorite;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function index()
    {
        $professeurs = TeacherProfile::with([
            'user',
            'courses' => fn ($q) => $q->approved(),
        ])
            ->whereHas('courses', fn ($q) => $q->approved())
            ->withCount(['courses as cours_donnes' => function ($q) {
                $q->approved()->whereHas('bookings', function ($q2) {
                    $q2->where('status', 'terminé');
                });
            }])
            ->orderBy('cours_donnes', 'desc')
            ->orderBy('rating', 'desc')
            ->take(4)
            ->get();

        $meilleurProf = TeacherProfile::with([
            'user',
            'courses' => fn ($q) => $q->approved(),
        ])
            ->where('is_verified', true)
            ->whereHas('courses', fn ($q) => $q->approved())
            ->orderBy('rating', 'desc')
            ->first();

        $categories = [
            ['emoji' => '📐', 'label' => 'Mathématiques'],
            ['emoji' => '🍳', 'label' => 'Cuisine'],
            ['emoji' => '⚽', 'label' => 'Sport'],
            ['emoji' => '🌍', 'label' => 'Langues'],
            ['emoji' => '🎵', 'label' => 'Musique'],
            ['emoji' => '💻', 'label' => 'Informatique'],
        ];

        $favorisIds = auth()->check()
            ? Favorite::where('user_id', auth()->id())->pluck('teacher_profile_id')->toArray()
            : [];

        return view('welcome', compact('professeurs', 'categories', 'favorisIds', 'meilleurProf'));
    }

    public function profil($id)
    {
        $profile = TeacherProfile::with([
            'user',
            'courses' => fn ($q) => $q->approved(),
            'reviews.user',
        ])->findOrFail($id);

        $categorie = $profile->courses->first()->category ?? null;

        $similaires = TeacherProfile::with([
            'user',
            'courses' => fn ($q) => $q->approved(),
        ])
            ->where('id', '!=', $id)
            ->whereHas('courses', fn ($q) => $q->approved())
            ->when($categorie, function ($q) use ($categorie) {
                $q->whereHas('courses', function ($q2) use ($categorie) {
                    $q2->approved()->where('category', $categorie);
                });
            })
            ->orderBy('rating', 'desc')
            ->take(3)
            ->get();

        $nombreEleves = $profile->nombreElevesUniques();

        return view('professeur.profil', compact('profile', 'similaires', 'nombreEleves'));
    }

    public function storeBooking(Request $request, $id)
    {
        $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'duration_hours' => ['required', 'integer', 'min:1', 'max:8'],
        ]);

        $profile = TeacherProfile::findOrFail($id);

        $course = Course::approved()
            ->where('teacher_profile_id', $profile->id)
            ->findOrFail($request->course_id);

        $totalPrice = $course->price_per_hour * $request->duration_hours;

        Booking::create([
            'user_id'        => auth()->id(),
            'course_id'      => $course->id,
            'scheduled_at'   => $request->scheduled_at,
            'duration_hours' => $request->duration_hours,
            'total_price'    => $totalPrice,
            'status'         => 'en_attente',
        ]);

        // Notifier le professeur
        $profUser = $profile->user;
        Notification::notifier(
            userId: $profUser->id,
            type:   'course_pending',
            title:  'Nouvelle réservation',
            body:   auth()->user()->name . ' a réservé votre cours "' . $course->title . '".',
            link:   route('professeur.reservations')
        );

        // Envoi mail au professeur
        $booking = \App\Models\Booking::with(['user', 'course.teacherProfile.user'])
            ->where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->latest()
            ->first();

        try {
            Mail::to($profUser->email)->send(new \App\Mail\NouvelleReservation($booking));
        } catch (\Throwable $e) {
            Log::error('Échec de l\'envoi du mail de nouvelle réservation', [
                'exception' => $e,
                'booking_id' => $booking->id,
                'professeur_id' => $profUser->id,
            ]);
        }

        return redirect()->route('eleve.reservations')
            ->with('success', 'Réservation enregistrée avec succès. Le professeur va la confirmer bientôt.');
    }
}
