<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Course;
use App\Models\Favorite;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function index()
    {
        $professeurs = \Illuminate\Support\Facades\Cache::remember('homepage_professeurs', 180, function () {
            return TeacherProfile::with([
                'user',
                'courses' => fn ($q) => $q->approved(),
            ])
                ->whereHas('user', fn ($u) => $u->where('is_suspended', false))
                ->whereHas('courses', fn ($q) => $q->approved())
                ->where(function ($q) {
                    $q->where('is_verified', true)
                      ->orWhere('is_featured', true);
                })
                ->withCount(['courses as cours_donnes' => function ($q) {
                    $q->approved()->whereHas('bookings', function ($q2) {
                        $q2->where('status', 'terminé');
                    });
                }])
                ->orderByDesc('is_featured')
                ->orderByDesc('is_verified')
                ->orderBy('cours_donnes', 'desc')
                ->orderBy('rating', 'desc')
                ->take(9)
                ->get();
        });

        $meilleurProf = \Illuminate\Support\Facades\Cache::remember('homepage_meilleur_prof', 180, function () {
            return TeacherProfile::with([
                'user',
                'courses' => fn ($q) => $q->approved(),
            ])
                ->whereHas('user', fn ($u) => $u->where('is_suspended', false))
                ->where('is_verified', true)
                ->whereHas('courses', fn ($q) => $q->approved())
                ->orderBy('rating', 'desc')
                ->first();
        });

        $categories = [
            ['label' => 'Mathématiques'],
            ['label' => 'Cuisine'],
            ['label' => 'Sport'],
            ['label' => 'Langues'],
            ['label' => 'Musique'],
            ['label' => 'Informatique'],
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

        // Si le professeur est suspendu et que le visiteur n'est pas admin, bloquer l'accès public
        if ($profile->user && $profile->user->is_suspended && (!auth()->check() || !auth()->user()->isAdmin())) {
            return redirect()->route('cours.index')->with('error', 'Ce profil enseignant n\'est pas accessible actuellement.');
        }

        $categorie = $profile->courses->first()->category ?? null;

        $similaires = TeacherProfile::with([
            'user',
            'courses' => fn ($q) => $q->approved(),
        ])
            ->where('id', '!=', $id)
            ->whereHas('user', fn ($u) => $u->where('is_suspended', false))
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
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour réserver un cours.');
        }

        if (auth()->user()->is_suspended) {
            $adminUser = User::where('role', 'admin')->first();
            $redirectRoute = $adminUser ? route('messages.show', $adminUser->id) : route('dashboard');
            return redirect($redirectRoute)->with('error', 'Votre compte est actuellement suspendu. Vous ne pouvez pas réserver de cours. Veuillez contacter l\'Assistance Kimboo.');
        }

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

        $booking = Booking::create([
            'user_id'        => auth()->id(),
            'course_id'      => $course->id,
            'scheduled_at'   => $request->scheduled_at,
            'duration_hours' => $request->duration_hours,
            'total_price'    => $totalPrice,
            'status'         => 'en_attente',
        ]);

        $booking->load(['user', 'course.teacherProfile.user']);

        // Notifier le professeur
        $profUser = $profile->user;
        Notification::notifier(
            userId: $profUser->id,
            type:   'course_pending',
            title:  'Nouvelle réservation',
            body:   auth()->user()->name . ' a réservé votre cours "' . $course->title . '".',
            link:   route('professeur.reservations')
        );

        // Notifier l'élève
        Notification::notifier(
            userId: auth()->id(),
            type:   'course_pending',
            title:  'Demande de réservation envoyée',
            body:   'Votre demande pour "' . $course->title . '" a bien été transmise à ' . $profUser->name . '.',
            link:   route('eleve.reservations')
        );

        // Envoi mail au professeur
        try {
            Mail::to($profUser->email)->send(new \App\Mail\NouvelleReservation($booking));
        } catch (\Throwable $e) {
            Log::error('Échec de l\'envoi du mail de nouvelle réservation', [
                'exception' => $e,
                'booking_id' => $booking->id,
                'professeur_id' => $profUser->id,
            ]);
        }

        // Envoi mail récapitulatif à l'élève
        try {
            Mail::to(auth()->user()->email)->send(new \App\Mail\DemandeReservationEleve($booking));
        } catch (\Throwable $e) {
            Log::error('Échec de l\'envoi du mail de confirmation réservation élève', [
                'exception' => $e,
                'booking_id' => $booking->id,
                'user_id' => auth()->id(),
            ]);
        }

        $userRole = auth()->user()->role;
        $redirectRoute = match ($userRole) {
            'professeur' => 'professeur.reservations',
            'admin'      => 'admin.dashboard',
            default      => 'eleve.reservations',
        };

        return redirect()->route($redirectRoute)
            ->with('success', 'Votre demande de réservation a été envoyée avec succès. Un récapitulatif vous a été envoyé par email.');
    }
}
