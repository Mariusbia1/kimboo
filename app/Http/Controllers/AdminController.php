<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TeacherProfile;
use App\Models\Booking;
use App\Models\Course;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalProfesseurs = User::where('role', 'professeur')->count();
        $totalEleves = User::where('role', 'eleve')->count();
        $totalReservations = Booking::count();
        $totalCours = Course::count();
        $professeurs = TeacherProfile::with('user')->latest()->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalProfesseurs', 'totalEleves',
            'totalReservations', 'totalCours', 'professeurs'
        ));
    }

    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.users', compact('users'));
    }

    public function certifierProfesseur($id)
    {
        $profile = TeacherProfile::findOrFail($id);
        $profile->update(['is_verified' => true]);
        return back()->with('success', 'Professeur certifié avec succès !');
    }

    public function decertifierProfesseur($id)
    {
        $profile = TeacherProfile::findOrFail($id);
        $profile->update(['is_verified' => false]);
        return back()->with('success', 'Certification retirée.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'Utilisateur supprimé.');
    }
}
