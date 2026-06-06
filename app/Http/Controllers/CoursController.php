<?php

namespace App\Http\Controllers;

use App\Models\TeacherProfile;
use Illuminate\Http\Request;

class CoursController extends Controller
{
    public function index(Request $request)
    {
        $categorie = $request->get('categorie');
        $q = $request->get('q');

        $query = TeacherProfile::with([
            'user',
            'courses' => fn ($q) => $q->approved(),
        ])
            ->whereHas('courses', fn ($q) => $q->approved())
            ->withCount(['courses as cours_donnes' => function ($query) {
                $query->approved()->whereHas('bookings', function ($q2) {
                    $q2->where('status', 'terminé');
                });
            }])
            ->orderBy('cours_donnes', 'desc')
            ->orderBy('rating', 'desc');

        if ($categorie) {
            $query->whereHas('courses', function ($q) use ($categorie) {
                $q->approved()->where('category', $categorie);
            });
        }

        if ($q) {
            $query->where(function ($query) use ($q) {
                $query->whereHas('courses', function ($q2) use ($q) {
                    $q2->approved()
                        ->where(function ($q3) use ($q) {
                            $q3->where('title', 'like', "%{$q}%")
                                ->orWhere('category', 'like', "%{$q}%")
                                ->orWhere('description', 'like', "%{$q}%");
                        });
                })->orWhereHas('user', function ($q2) use ($q) {
                    $q2->where('name', 'like', "%{$q}%")
                        ->orWhere('ville', 'like', "%{$q}%");
                });
            });
        }

        $professeurs = $query->paginate(12);

        return view('cours.index', compact('professeurs', 'categorie', 'q'));
    }
}
