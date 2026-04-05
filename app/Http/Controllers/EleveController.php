<?php

namespace App\Http\Controllers;

use App\Models\Booking;

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
        $coursesTermines = $reservations->where('status', 'terminé')->count();
        $coursesConfirmes = $reservations->where('status', 'confirmé')->count();

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
}
