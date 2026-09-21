<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Booking;
use App\Http\Requests\UpdateTeacherProfileRequest;
use App\Http\Requests\StoreCourseRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use App\Mail\NouvelleReservation;
use App\Mail\ReservationConfirmee;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProfesseurController extends Controller
{
   public function dashboard()
{
    $user = auth()->user();
    $profile = $user->teacherProfile;
    $courses = $profile ? $profile->courses : collect();

    $reservationsCount = $profile ? \App\Models\Booking::whereHas('course', function($q) use ($profile) {
        $q->where('teacher_profile_id', $profile->id);
    })->count() : 0;

    // Cagnotte mensuelle
    $cagnotteMensuelle = $profile ? \App\Models\Booking::whereHas('course', function($q) use ($profile) {
        $q->where('teacher_profile_id', $profile->id);
    })->where('status', 'confirmé')
      ->whereMonth('created_at', now()->month)
      ->sum('total_price') : 0;

    $nombreEleves = $profile ? $profile->nombreElevesUniques() : 0;

    // Total cours donnés
    $totalCoursDonnes = $profile ? \App\Models\Booking::whereHas('course', function($q) use ($profile) {
        $q->where('teacher_profile_id', $profile->id);
    })->where('status', 'terminé')->count() : 0;

    // Réservations cette semaine et semaine prochaine
    $reservationsSemaine = $profile ? \App\Models\Booking::whereHas('course', function($q) use ($profile) {
        $q->where('teacher_profile_id', $profile->id);
    })->with(['user', 'course'])
      ->whereBetween('scheduled_at', [now()->startOfWeek(), now()->endOfWeek()->addWeek()])
      ->orderBy('scheduled_at')
      ->get() : collect();

    // Avis reçus groupés par cours
    $avisParCours = $profile ? \App\Models\Review::whereHas('course', function($q) use ($profile) {
        $q->where('teacher_profile_id', $profile->id);
    })
    ->with(['course', 'user'])
    ->orderByDesc('created_at')
    ->get()
    ->groupBy('course_id') : collect();

    return view('professeur.dashboard', compact(
        'user', 'profile', 'courses', 'reservationsCount',
        'cagnotteMensuelle', 'nombreEleves', 'totalCoursDonnes',
        'reservationsSemaine', 'avisParCours'
    ));
}
    public function editProfil()
    {
        $user = auth()->user();
        $profile = $user->teacherProfile;
        return view('professeur.edit-profil', compact('user', 'profile'));
    }

    public function updateProfil(UpdateTeacherProfileRequest $request)
    {
        $user = auth()->user();
        $profile = $user->teacherProfile;

        // Gestion de la photo
        if ($request->hasFile('avatar')) {
            if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
            $user->save();
        }

        $parcours = array_values(array_filter($request->input('parcours_academique', []), function($p) {
            return !empty($p['diplome']) || !empty($p['annees']);
        }));

        \Illuminate\Support\Facades\DB::table('teacher_profiles')
            ->where('id', $profile->id)
            ->update([
                'bio' => $request->bio,
                'a_propos_cours' => $request->a_propos_cours,
                'experience_years' => $request->experience_years,
                'zone_deplacement' => $this->parseZoneDeplacement($request),
                'video_url' => $request->video_url,
                'parcours_academique' => json_encode($parcours),
                'response_time' => $request->response_time,
                'updated_at' => now(),
            ]);

        $user->update([
            'name'  => $request->name,
            'ville' => $request->ville,
            'phone' => $request->phone,
        ]);

        \Illuminate\Support\Facades\Cache::forget('homepage_professeurs');
        \Illuminate\Support\Facades\Cache::forget('homepage_meilleur_prof');

        return redirect()->route('professeur.dashboard')
            ->with('success', 'Profil mis à jour avec succès !');
    }
    public function createCours()
    {
        $profile = auth()->user()->teacherProfile;
        $courses = $profile ? $profile->courses()->latest()->get() : collect();
        $categories = Category::orderBy('name')->pluck('name');
        return view('professeur.create-cours', compact('categories', 'courses'));
    }

    public function storeCours(StoreCourseRequest $request)
    {
        if (auth()->user()->is_suspended) {
            return redirect()->route('professeur.dashboard')
                ->with('error', 'Votre compte est actuellement suspendu. Vous ne pouvez pas publier de cours. Veuillez contacter l\'Assistance Kimboo.');
        }

        $profile = auth()->user()->teacherProfile;
        if (!$profile) {
            return redirect()->route('professeur.dashboard')
                ->with('error', 'Veuillez d\'abord compléter votre profil professeur avant de publier un cours.');
        }

        // Gestion de la catégorie (saisie libre ou sélectionnée)
        $categoryInput = trim($request->filled('new_category') ? $request->new_category : $request->category);
        if (!empty($categoryInput) && $categoryInput !== '__new__') {
            Category::firstOrCreate(
                ['name' => $categoryInput],
                ['slug' => Str::slug($categoryInput), 'created_by_user_id' => auth()->id()]
            );
            $finalCategory = $categoryInput;
        } else {
            $finalCategory = 'Général';
        }

        $cours = Course::create([
            'teacher_profile_id' => $profile->id,
            'title'              => $request->title,
            'description'        => $request->description,
            'category'           => $finalCategory,
            'level'              => $request->level,
            'format'             => $request->format,
            'price_per_hour'     => $request->price_per_hour,
            'first_course_free'  => $request->boolean('first_course_free'),
            'is_active'          => false,      // invisible jusqu'à validation
            'status'             => 'pending',  // en attente admin
            'is_group'           => $request->boolean('is_group'),
            'max_students'       => $request->boolean('is_group') ? $request->max_students : null,
            'lieu_cours'         => $request->lieu_cours ?? [],
            'zone_deplacement'   => $this->parseZoneDeplacement($request),
        ]);

        // Synchroniser le tarif du profil et l'offre 1er cours offert
        $profile->update([
            'hourly_rate'       => $profile->courses()->min('price_per_hour') ?? $request->price_per_hour,
            'first_course_free' => $profile->courses()->where('first_course_free', true)->exists(),
        ]);

        // Notifier tous les admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::notifier(
                userId: $admin->id,
                type:   'course_pending',
                title:  'Nouveau cours à valider',
                body:   auth()->user()->name . ' a soumis un nouveau cours : "' . $cours->title . '"',
                link:   '/admin/cours/' . $cours->id
            );
        }

        // Notifier le prof
        Notification::notifier(
            userId: auth()->id(),
            type:   'course_pending',
            title:  'Cours soumis avec succès',
            body:   'Votre cours "' . $cours->title . '" est en attente de validation par l\'équipe Kimboo.',
            link:   route('professeur.create-cours')
        );

        return redirect()->route('professeur.dashboard')
            ->with('success', 'Cours soumis ! Il sera visible après validation par notre équipe.');
    }

    public function editCours($id)
    {
        $course = Course::findOrFail($id);

        if ($course->teacherProfile->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return redirect()->route('professeur.dashboard')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier ce cours.');
        }

        $categories = Category::orderBy('name')->pluck('name');

        return view('professeur.edit-cours', compact('course', 'categories'));
    }

    public function updateCours(StoreCourseRequest $request, $id)
    {
        if (auth()->user()->is_suspended) {
            return redirect()->route('professeur.dashboard')
                ->with('error', 'Votre compte est actuellement suspendu. Vous ne pouvez pas modifier de cours. Veuillez contacter l\'Assistance Kimboo.');
        }

        $course = Course::findOrFail($id);

        if ($course->teacherProfile->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return redirect()->route('professeur.dashboard')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier ce cours.');
        }

        // Gestion de la catégorie (saisie libre ou sélectionnée)
        $categoryInput = trim($request->filled('new_category') ? $request->new_category : $request->category);
        if (!empty($categoryInput) && $categoryInput !== '__new__') {
            Category::firstOrCreate(
                ['name' => $categoryInput],
                ['slug' => Str::slug($categoryInput), 'created_by_user_id' => auth()->id()]
            );
            $finalCategory = $categoryInput;
        } else {
            $finalCategory = 'Général';
        }

        $course->update([
            'title'              => $request->title,
            'description'        => $request->description,
            'category'           => $finalCategory,
            'level'              => $request->level,
            'format'             => $request->format,
            'price_per_hour'     => $request->price_per_hour,
            'first_course_free'  => $request->boolean('first_course_free'),
            'is_group'           => $request->boolean('is_group'),
            'max_students'       => $request->boolean('is_group') ? $request->max_students : null,
            'lieu_cours'         => $request->lieu_cours ?? [],
            'zone_deplacement'   => $this->parseZoneDeplacement($request),
        ]);

        $profile = $course->teacherProfile;
        if ($profile) {
            $profile->update([
                'hourly_rate'       => $profile->courses()->min('price_per_hour') ?? $request->price_per_hour,
                'first_course_free' => $profile->courses()->where('first_course_free', true)->exists(),
            ]);
        }

        return redirect()->route('professeur.dashboard')
            ->with('success', 'Cours modifié avec succès !');
    }

    public function deleteCours($id)
    {
        $course = Course::findOrFail($id);

        if ($course->teacherProfile->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return redirect()->route('professeur.dashboard')
                ->with('error', 'Vous n\'êtes pas autorisé à supprimer ce cours.');
        }

        $profile = $course->teacherProfile;
        $course->delete();

        if ($profile) {
            $profile->update([
                'hourly_rate'       => $profile->courses()->min('price_per_hour') ?? 0,
                'first_course_free' => $profile->courses()->where('first_course_free', true)->exists(),
            ]);
        }

        return redirect()->route('professeur.dashboard')
            ->with('success', 'Cours supprimé avec succès !');
    }

    public function reservations()
    {
        $profile = auth()->user()->teacherProfile;

        $reservations = Booking::whereHas('course', function($q) use ($profile) {
            $q->where('teacher_profile_id', $profile->id);
        })
        ->with(['user', 'course'])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('professeur.reservations', compact('reservations'));
    }

    public function confirmBooking($id)
    {
        if (auth()->user()->is_suspended) {
            return back()->with('error', 'Votre compte est actuellement suspendu. Veuillez contacter l\'Assistance Kimboo.');
        }

        $booking = Booking::with(['user', 'course.teacherProfile.user'])->findOrFail($id);
        $booking->update(['status' => 'confirmé']);

        // Notifier l'élève
        Notification::notifier(
            userId: $booking->user_id,
            type:   'course_approved',
            title:  'Réservation confirmée',
            body:   'Votre réservation pour "' . $booking->course->title . '" a été confirmée.',
            link:   route('eleve.reservations')
        );

        // Envoi mail à l'élève
        try {
            Mail::to($booking->user->email)->send(new ReservationConfirmee($booking));
        } catch (\Throwable $e) {
            Log::error('Échec de l\'envoi du mail de confirmation de réservation', [
                'exception' => $e,
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
            ]);
        }

        return back()->with('success', 'Réservation confirmée !');
    }

    public function cancelBooking($id)
{
    $booking = Booking::with(['user', 'course.teacherProfile.user'])->findOrFail($id);
    $booking->update(['status' => 'annulé']);

    // Notifier l'élève
    Notification::notifier(
        userId: $booking->user_id,
        type:   'course_rejected',
        title:  'Réservation annulée',
        body:   'Votre réservation pour "' . $booking->course->title . '" a été annulée par le professeur.',
        link:   route('eleve.reservations')
    );

    // Envoi mail à l'élève
    try {
        Mail::to($booking->user->email)->send(new ReservationAnnulee($booking, 'prof'));
    } catch (\Throwable $e) {
        Log::error('Échec de l\'envoi du mail d\'annulation de réservation professeur', [
            'exception' => $e,
            'booking_id' => $booking->id,
            'user_id' => $booking->user_id,
        ]);
    }

    return back()->with('success', 'Réservation annulée.');
}

    public function calendrier(Request $request)
{
    $profile = auth()->user()->teacherProfile;
    $validated = $request->validate([
        'mois'  => ['nullable', 'integer', 'min:1', 'max:12'],
        'annee' => ['nullable', 'integer', 'min:2020', 'max:2100'],
    ]);
    $mois  = $validated['mois'] ?? now()->month;
    $annee = $validated['annee'] ?? now()->year;

    $coursduMois = Booking::whereHas('course', function($q) use ($profile) {
            $q->where('teacher_profile_id', $profile->id);
        })
        ->whereIn('status', ['confirmé', 'terminé'])
        ->whereMonth('scheduled_at', $mois)
        ->whereYear('scheduled_at', $annee)
        ->with(['course', 'user'])
        ->orderBy('scheduled_at')
        ->get();

    $prochainsCoours = Booking::whereHas('course', function($q) use ($profile) {
            $q->where('teacher_profile_id', $profile->id);
        })
        ->where('status', 'confirmé')
        ->where('scheduled_at', '>=', now())
        ->with(['course', 'user'])
        ->orderBy('scheduled_at')
        ->get();

    $coursParJour = $coursduMois->groupBy(function($booking) {
        return \Carbon\Carbon::parse($booking->scheduled_at)->format('j');
    });

    $premierJourMois = \Carbon\Carbon::create($annee, $mois, 1);
    $dernierJourMois = $premierJourMois->copy()->endOfMonth();
    $moisPrecedent   = $premierJourMois->copy()->subMonth();
    $moisSuivant     = $premierJourMois->copy()->addMonth();

    return view('professeur.calendrier', compact(
        'coursduMois', 'prochainsCoours', 'coursParJour',
        'premierJourMois', 'dernierJourMois',
        'moisPrecedent', 'moisSuivant',
        'mois', 'annee'
    ));
}

    /**
     * Parse et formate proprement la zone de déplacement avec son unité explicite.
     */
    private function parseZoneDeplacement($request): ?string
    {
        if ($request->filled('zone_unit')) {
            $unit = $request->zone_unit;
            $dist = trim((string)$request->input('zone_distance', ''));
            $prec = trim((string)$request->input('zone_precisions', ''));

            if ($unit === 'ville') {
                return 'Toute la ville' . ($prec !== '' ? " ({$prec})" : '');
            }
            if ($unit === 'aucun') {
                return 'Sans déplacement (en ligne / sur place)';
            }
            if ($dist !== '') {
                return "{$dist} {$unit}" . ($prec !== '' ? " ({$prec})" : '');
            }
            if ($prec !== '') {
                return $prec;
            }
        }

        if ($request->filled('zone_deplacement')) {
            $raw = trim((string)$request->zone_deplacement);
            if (is_numeric($raw)) {
                return ((float)$raw > 100) ? "{$raw} m" : "{$raw} km";
            }
            return $raw;
        }

        return null;
    }
}
