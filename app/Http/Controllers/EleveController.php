<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Mail\NouvelAvis;
use Illuminate\Support\Facades\Mail;


class EleveController extends Controller
{
    public function dashboard()
{
    $user = auth()->user();

    $reservationsCount = Booking::where('user_id', $user->id)->count();
    $coursesTermines   = Booking::where('user_id', $user->id)->where('status', 'terminé')->count();
    $coursesConfirmes  = Booking::where('user_id', $user->id)->where('status', 'confirmé')->count();

    // Prochains cours confirmés (max 3)
    $prochainsCoours = Booking::where('user_id', $user->id)
        ->where('status', 'confirmé')
        ->where('scheduled_at', '>=', now())
        ->with(['course.teacherProfile.user'])
        ->orderBy('scheduled_at', 'asc')
        ->limit(3)
        ->get();

    return view('eleve.dashboard', compact(
        'user', 'reservationsCount',
        'coursesTermines', 'coursesConfirmes',
        'prochainsCoours'
    ));
}

public function mesReservations()
{
    $user = auth()->user();

    $reservations = Booking::where('user_id', $user->id)
        ->with(['course.teacherProfile.user'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('eleve.mes-reservations', compact('reservations'));
}

public function mesCours()
{
    $user = auth()->user();

    $cours = Booking::where('user_id', $user->id)
        ->whereIn('status', ['en_attente', 'confirmé', 'terminé'])
        ->with(['course.teacherProfile.user'])
        ->orderBy('scheduled_at', 'desc')
        ->paginate(9);

    return view('eleve.mes-cours', compact('cours'));
}
    public function cancelBooking($id)
    {
        $booking = Booking::with(['course.teacherProfile.user'])->findOrFail($id);

        if ($booking->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return back()->with('error', 'Action non autorisée sur cette réservation.');
        }

        $booking->update(['status' => 'annulé']);

        // Notifier le professeur
        Notification::notifier(
            userId: $booking->course->teacherProfile->user->id,
            type:   'course_rejected',
            title:  'Réservation annulée',
            body:   auth()->user()->name . ' a annulé sa réservation pour "' . $booking->course->title . '".',
            link:   route('professeur.reservations')
        );

        // Envoi mail au professeur
        try {
            Mail::to($booking->course->teacherProfile->user->email)
                ->send(new \App\Mail\ReservationAnnulee($booking, 'eleve'));
        } catch (\Throwable $e) {
            Log::error('Échec de l\'envoi du mail d\'annulation de réservation élève', [
                'exception' => $e,
                'booking_id' => $booking->id,
                'user_id' => auth()->id(),
            ]);
        }

        return back()->with('success', 'Réservation annulée avec succès.');
    }

    public function terminerBooking($id)
    {
        $booking = Booking::with('course.teacherProfile.user')->findOrFail($id);

        if ($booking->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return back()->with('error', 'Action non autorisée sur cette réservation.');
        }

        if ($booking->status !== 'confirmé') {
            return back()->with('error', 'Ce cours ne peut pas être marqué comme terminé.');
        }

        $booking->update(['status' => 'terminé']);

        // Notifier le professeur
        Notification::notifier(
            userId: $booking->course->teacherProfile->user->id,
            type:   'course_completed',
            title:  'Cours terminé',
            body:   auth()->user()->name . ' a marqué le cours "' . $booking->course->title . '" comme terminé.',
            link:   route('professeur.reservations')
        );

        // Notifier les admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::notifier(
                userId: $admin->id,
                type:   'course_completed',
                title:  'Cours terminé',
                body:   auth()->user()->name . ' a terminé le cours "' . $booking->course->title . '".',
                link:   '/admin/cours'
            );
        }

        return back()->with('success', 'Cours marqué comme terminé ! Vous pouvez maintenant laisser un avis.');
    }

    public function storeAvis(Request $request, $id)
    {
        if (auth()->user()->is_suspended) {
            return back()->with('error', 'Votre compte est actuellement suspendu. Veuillez contacter l\'Assistance Kimboo.');
        }

        $booking = Booking::with('course.teacherProfile')->findOrFail($id);

        if ($booking->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return back()->with('error', 'Action non autorisée.');
        }

        if ($booking->status !== 'terminé') {
            return back()->with('error', 'Vous ne pouvez laisser un avis que sur un cours terminé.');
        }

        if ($booking->reviewed) {
            return back()->with('error', 'Vous avez déjà laissé un avis pour ce cours.');
        }

        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Créer l'avis
        Review::create([
            'user_id'    => auth()->id(),
            'course_id'  => $booking->course_id,
            'booking_id' => $booking->id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        // Marquer la réservation comme notée
        $booking->update(['reviewed' => true]);

        // Mettre à jour la note moyenne du prof
        $profile = $booking->course->teacherProfile;
        $avgRating = Review::where('course_id', $booking->course_id)->avg('rating');
        $reviewsCount = Review::whereHas('course', function($q) use ($profile) {
            $q->where('teacher_profile_id', $profile->id);
        })->count();

        $profile->update([
            'rating'        => round($avgRating, 1),
            'reviews_count' => $reviewsCount,
        ]);

        // Notifier le professeur
        Notification::notifier(
            userId: $profile->user->id,
            type:   'new_review',
            title:  'Nouvel avis',
            body:   auth()->user()->name . ' a laissé un avis ' . $request->rating . '/5 sur votre cours "' . $booking->course->title . '".',
            link:   route('professeur.dashboard')
        );
        // Envoi mail au professeur
        try {
            $review = \App\Models\Review::where('booking_id', $booking->id)->latest()->first();
            if ($review && $profile->user && $profile->user->email) {
                Mail::to($profile->user->email)->send(new NouvelAvis($review->load('user')));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Échec de l\'envoi du mail de nouvel avis', [
                'error' => $e->getMessage(),
                'booking_id' => $booking->id,
            ]);
        }

        return back()->with('success', 'Avis publié avec succès ! Merci pour votre retour.');
    }


public function editProfil()
{
    $user = auth()->user();
    return view('eleve.edit-profil', compact('user'));
}

public function updateProfil(\Illuminate\Http\Request $request)
{
    $user = auth()->user();

    $request->validate([
        'name'                  => 'required|string|max:100',
        'phone'                 => 'nullable|string|max:20',
        'ville'                 => 'nullable|string|max:100',
        'payment_method'        => 'nullable|in:wave,orange_money',
        'avatar'                => 'nullable|image|max:2048',
        'password'              => 'nullable|string|min:8|confirmed',
    ]);

    // Photo de profil
    if ($request->hasFile('avatar')) {
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $path;
    }

    // Infos de base
    $user->name           = $request->name;
    $user->phone          = $request->phone;
    $user->ville          = $request->ville;
    $user->payment_method = $request->payment_method;

    // Mot de passe
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect()->route('eleve.edit-profil')
        ->with('success', 'Profil mis à jour avec succès !');
}

public function destroyAccount(Request $request)
{
    $request->validate([
        'password' => ['required', 'current_password'],
    ]);

    $user = auth()->user();

    if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
        Storage::disk('public')->delete($user->avatar);
    }

    Auth::logout();
    $user->delete();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/')->with('success', 'Votre compte a été supprimé définitivement.');
}

public function calendrier(Request $request)
{
    $user = auth()->user();
    $validated = $request->validate([
        'mois'  => ['nullable', 'integer', 'min:1', 'max:12'],
        'annee' => ['nullable', 'integer', 'min:2020', 'max:2100'],
    ]);
    $mois  = $validated['mois'] ?? now()->month;
    $annee = $validated['annee'] ?? now()->year;

    // Cours du mois sélectionné
    $coursduMois = Booking::where('user_id', $user->id)
        ->whereIn('status', ['confirmé', 'terminé'])
        ->whereMonth('scheduled_at', $mois)
        ->whereYear('scheduled_at', $annee)
        ->with(['course.teacherProfile.user'])
        ->orderBy('scheduled_at')
        ->get();

    // Prochains cours (liste)
    $prochainsCoours = Booking::where('user_id', $user->id)
        ->whereIn('status', ['confirmé'])
        ->where('scheduled_at', '>=', now())
        ->with(['course.teacherProfile.user'])
        ->orderBy('scheduled_at')
        ->get();

    // Grouper par jour pour la vue mensuelle
    $coursParJour = $coursduMois->groupBy(function($booking) {
        return \Carbon\Carbon::parse($booking->scheduled_at)->format('j');
    });

    $premierJourMois = \Carbon\Carbon::create($annee, $mois, 1);
    $dernierJourMois = $premierJourMois->copy()->endOfMonth();

    $moisPrecedent = $premierJourMois->copy()->subMonth();
    $moisSuivant   = $premierJourMois->copy()->addMonth();

    return view('eleve.calendrier', compact(
        'coursduMois', 'prochainsCoours', 'coursParJour',
        'premierJourMois', 'dernierJourMois',
        'moisPrecedent', 'moisSuivant',
        'mois', 'annee'
    ));
}
}
