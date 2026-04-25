<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class EleveController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $reservations = Booking::where('user_id', $user->id)
            ->with(['course.teacherProfile.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $reservationsCount = $reservations->count();
        $coursesTermines   = $reservations->where('status', 'terminé')->count();
        $coursesConfirmes  = $reservations->where('status', 'confirmé')->count();

        return view('eleve.dashboard', compact(
            'user', 'reservations', 'reservationsCount',
            'coursesTermines', 'coursesConfirmes'
        ));
    }

    public function cancelBooking($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->update(['status' => 'annulé']);

        return back()->with('success', 'Réservation annulée avec succès.');
    }

    public function terminerBooking($id)
    {
        $booking = Booking::with('course.teacherProfile.user')->findOrFail($id);

        if ($booking->user_id !== auth()->id()) {
            abort(403);
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
        $booking = Booking::with('course.teacherProfile')->findOrFail($id);

        if ($booking->user_id !== auth()->id()) {
            abort(403);
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
    $user->name  = $request->name;
    $user->phone = $request->phone;
    $user->ville = $request->ville;

    // Mot de passe
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect()->route('eleve.edit-profil')
        ->with('success', 'Profil mis à jour avec succès !');
}
}
