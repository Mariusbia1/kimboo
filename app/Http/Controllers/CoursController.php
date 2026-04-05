<?php

namespace App\Http\Controllers;

use App\Models\TeacherProfile;
use Illuminate\Http\Request;

class CoursController extends Controller
{
    public function index(Request $request)
    {
        $categorie = $request->get('categorie');

        $query = TeacherProfile::with(['user', 'courses'])
            ->orderBy('rating', 'desc');

        if ($categorie) {
            $query->whereHas('courses', function($q) use ($categorie) {
                $q->where('category', $categorie);
            });
        }

        $professeurs = $query->paginate(12);

        return view('cours.index', compact('professeurs', 'categorie'));
    }
}
