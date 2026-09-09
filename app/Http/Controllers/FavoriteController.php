<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle($profileId)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $existing = Favorite::where('user_id', $user->id)
            ->where('teacher_profile_id', $profileId)
            ->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'teacher_profile_id' => $profileId,
            ]);
            $liked = true;
        }

        return response()->json(['liked' => $liked]);
    }

    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $favoris = Favorite::where('user_id', $user->id)
            ->with(['teacherProfile.user', 'teacherProfile.courses'])
            ->get()
            ->pluck('teacherProfile');

        return view('eleve.favoris', compact('favoris'));
    }
}