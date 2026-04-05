<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Booking;
use App\Http\Requests\UpdateTeacherProfileRequest;
use App\Http\Requests\StoreCourseRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProfesseurController extends Controller
{
   public function dashboard()
{
    $user = auth()->user();
    $profile = $user->teacherProfile;
    $courses = $profile ? $profile->courses : collect();

    $reservationsCount = $profile ? Booking::whereHas('course', function($q) use ($profile) {
        $q->where('teacher_profile_id', $profile->id);
    })->count() : 0;

    return view('professeur.dashboard', compact('user', 'profile', 'courses', 'reservationsCount'));
}

    public function editProfil()
    {
        $user = auth()->user();
        $profile = $user->teacherProfile;
        return view('professeur.edit-profil', compact('user', 'profile'));
    }

//     public function updateProfil(UpdateTeacherProfileRequest $request)
// {
//     $user = auth()->user();
//     $profile = $user->teacherProfile;

//     // Gestion de la photo
//     if ($request->hasFile('avatar')) {
//         // Supprimer l'ancienne photo si elle existe
//         if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
//             \Storage::disk('public')->delete($user->avatar);
//         }
//         $path = $request->file('avatar')->store('avatars', 'public');
//         $user->avatar = $path;
//     }

//     $profile->update([
//         'bio' => $request->bio,
//         'a_propos_cours' => $request->a_propos_cours,
//         'hourly_rate' => $request->hourly_rate,
//         'experience_years' => $request->experience_years,
//         'first_course_free' => $request->boolean('first_course_free'),
//         'lieu_cours' => $request->lieu_cours ?? [],
//         'zone_deplacement' => $request->zone_deplacement,
//         'video_url' => $request->video_url,
//     ]);

//     $user->update([
//         'ville' => $request->ville,
//         'phone' => $request->phone,
//     ]);

//     if ($request->hasFile('avatar')) {
//         $user->save();
//     }

//     return redirect()->route('professeur.dashboard')
//         ->with('success', 'Profil mis à jour avec succès !');
// }

public function updateProfil(\Illuminate\Http\Request $request)
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

    $profile->update([
        'bio' => $request->bio,
        'a_propos_cours' => $request->a_propos_cours,
        'hourly_rate' => $request->hourly_rate,
        'experience_years' => $request->experience_years,
        'first_course_free' => $request->has('first_course_free') ? true : false,
        'lieu_cours' => $request->lieu_cours ?? [],
        'zone_deplacement' => $request->zone_deplacement,
        'video_url' => $request->video_url,
    ]);

    $user->update([
        'ville' => $request->ville,
        'phone' => $request->phone,
    ]);

    return redirect()->route('professeur.dashboard')
        ->with('success', 'Profil mis à jour avec succès !');
}

    public function createCours()
    {
        return view('professeur.create-cours');
    }

    public function storeCours(StoreCourseRequest $request)
{
    $profile = auth()->user()->teacherProfile;

    Course::create([
        'teacher_profile_id' => $profile->id,
        'title' => $request->title,
        'description' => $request->description,
        'category' => $request->category,
        'level' => $request->level,
        'format' => $request->format,
        'price_per_hour' => $request->price_per_hour,
        'is_active' => true,
        'is_group' => $request->has('is_group') ? true : false,
        'max_students' => $request->is_group ? $request->max_students : null,
    ]);

    return redirect()->route('professeur.dashboard')
        ->with('success', 'Cours ajouté avec succès !');
}

    public function deleteCours($id)
    {
        $course = Course::findOrFail($id);

        if ($course->teacherProfile->user_id !== auth()->id()) {
            abort(403);
        }

        $course->delete();

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
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'confirmé']);
        return back()->with('success', 'Réservation confirmée !');
    }

    public function cancelBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'annulé']);
        return back()->with('success', 'Réservation annulée.');
    }
}
