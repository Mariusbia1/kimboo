<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;

class CoursController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'categorie'  => ['nullable', 'string', 'max:100'],
            'q'          => ['nullable', 'string', 'max:255'],
            'type_cours' => ['nullable', 'string', 'in:en_ligne,face_a_face,autour_de_moi'],
            'distance'   => ['nullable', 'numeric', 'min:1', 'max:50'],
            'tarif'      => ['nullable', 'numeric', 'min:0', 'max:500000'],
        ]);

        $categorie = trim((string) ($validated['categorie'] ?? ''));
        $q         = trim((string) ($validated['q'] ?? ''));
        $typeCours = $validated['type_cours'] ?? null;
        $distance  = $validated['distance'] ?? null;
        $tarif     = $validated['tarif'] ?? null;

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

        // 1. Filtre par Catégorie
        if ($categorie !== '') {
            $escapedCat = addcslashes($categorie, '%_\\');
            $query->whereHas('courses', function ($cQuery) use ($categorie, $escapedCat) {
                $cQuery->approved()->where(function ($sub) use ($categorie, $escapedCat) {
                    $sub->where('category', $categorie)
                        ->orWhere('category', 'like', "%{$escapedCat}%");
                });
            });
        }

        // 2. Filtre par Type de cours (En ligne, Face à face, Autour de moi)
        if ($typeCours) {
            if ($typeCours === 'en_ligne') {
                $query->where(function ($q) {
                    $q->whereJsonContains('lieu_cours', 'webcam')
                      ->orWhereHas('courses', function ($c) {
                          $c->approved()->where(function ($sub) {
                              $sub->where('format', 'En ligne')
                                  ->orWhere('format', 'Les deux')
                                  ->orWhere('format', 'like', '%ligne%')
                                  ->orWhere('format', 'like', '%webcam%');
                          });
                      });
                });
            } elseif ($typeCours === 'face_a_face') {
                $query->where(function ($q) {
                    $q->whereJsonContains('lieu_cours', 'chez_prof')
                      ->orWhereJsonContains('lieu_cours', 'chez_eleve')
                      ->orWhereHas('courses', function ($c) {
                          $c->approved()->where(function ($sub) {
                              $sub->where('format', 'Présentiel')
                                  ->orWhere('format', 'Les deux')
                                  ->orWhere('format', 'like', '%présentiel%')
                                  ->orWhere('format', 'like', '%presentiel%');
                          });
                      });
                });
            } elseif ($typeCours === 'autour_de_moi') {
                $userVille = trim((string) auth()->user()?->ville);
                if (!empty($userVille)) {
                    $escapedVille = addcslashes($userVille, '%_\\');
                    $query->where(function ($q) use ($escapedVille) {
                        $q->whereHas('user', fn ($u) => $u->where('ville', 'like', "%{$escapedVille}%"))
                          ->orWhere('zone_deplacement', 'like', "%{$escapedVille}%");
                    });
                } else {
                    $query->where(function ($q) {
                        $q->whereJsonContains('lieu_cours', 'chez_prof')
                          ->orWhereJsonContains('lieu_cours', 'chez_eleve')
                          ->orWhereHas('courses', fn ($c) => $c->approved()->where('format', '!=', 'En ligne'));
                    });
                }
            }
        }

        // 3. Filtre par Distance max (1 à 50 km) - 50 km = pas de limite
        if ($distance !== null && $distance !== '' && is_numeric($distance) && (int) $distance < 50) {
            $distInt   = (int) $distance;
            $userVille = trim((string) auth()->user()?->ville);
            $escapedVille = !empty($userVille) ? addcslashes($userVille, '%_\\') : '';

            $query->where(function ($q) use ($distInt, $userVille, $escapedVille) {
                // En ligne accessible partout
                $q->whereJsonContains('lieu_cours', 'webcam')
                  ->orWhereHas('courses', fn ($c) => $c->approved()->where(function ($f) {
                      $f->where('format', 'En ligne')
                        ->orWhere('format', 'Les deux')
                        ->orWhere('format', 'like', '%ligne%');
                  }));

                // Zone de déplacement du professeur
                $q->orWhere(function ($sub) use ($distInt, $userVille, $escapedVille) {
                    $sub->whereNotNull('zone_deplacement')
                        ->where('zone_deplacement', '!=', '')
                        ->where(function ($z) use ($distInt, $userVille, $escapedVille) {
                            $z->where('zone_deplacement', 'like', "%{$distInt}%")
                              ->orWhere('zone_deplacement', 'like', "%km%")
                              ->orWhere('zone_deplacement', 'like', "%Abidjan%")
                              ->orWhere('zone_deplacement', 'like', "%toute%");
                            if (!empty($userVille)) {
                                $z->orWhere('zone_deplacement', 'like', "%{$escapedVille}%");
                            }
                        });
                });

                // Même ville
                if (!empty($userVille)) {
                    $q->orWhereHas('user', fn ($u) => $u->where('ville', 'like', "%{$escapedVille}%"));
                }
            });
        }

        // 4. Filtre par Tarif max horaire (2 000 à 70 000 FCFA) - 70 000 = tous tarifs
        if ($tarif !== null && $tarif !== '' && is_numeric($tarif) && (float) $tarif < 70000) {
            $tarifFloat = (float) $tarif;
            $query->where(function ($q) use ($tarifFloat) {
                $q->whereHas('courses', function ($c) use ($tarifFloat) {
                    $c->approved()->where('price_per_hour', '<=', $tarifFloat);
                })->orWhere(function ($sub) use ($tarifFloat) {
                    $sub->where('hourly_rate', '>', 0)
                        ->where('hourly_rate', '<=', $tarifFloat);
                });
            });
        }

        // 5. Recherche textuelle globale
        if ($q !== '') {
            $escapedQ = addcslashes($q, '%_\\');
            $query->where(function ($subQuery) use ($escapedQ) {
                $subQuery->whereHas('courses', function ($cQuery) use ($escapedQ) {
                    $cQuery->approved()->where(function ($w) use ($escapedQ) {
                        $w->where('title', 'like', "%{$escapedQ}%")
                          ->orWhere('category', 'like', "%{$escapedQ}%")
                          ->orWhere('description', 'like', "%{$escapedQ}%")
                          ->orWhere('level', 'like', "%{$escapedQ}%")
                          ->orWhere('format', 'like', "%{$escapedQ}%");
                    });
                })->orWhereHas('user', function ($uQuery) use ($escapedQ) {
                    $uQuery->where('name', 'like', "%{$escapedQ}%")
                           ->orWhere('ville', 'like', "%{$escapedQ}%");
                })->orWhere('bio', 'like', "%{$escapedQ}%")
                  ->orWhere('a_propos_cours', 'like', "%{$escapedQ}%")
                  ->orWhere('zone_deplacement', 'like', "%{$escapedQ}%");
            });
        }

        $professeurs = $query->paginate(12)->withQueryString();

        // Récupérer les catégories en conservant la catégorie unifiée "Langues" (sans sous-langues comme Anglais, etc.)
        $canonicalCategories = [
            'Mathématiques',
            'Physique-Chimie',
            'SVT',
            'Philosophie',
            'Histoire-Géographie',
            'Informatique',
            'Sciences',
            'Langues',
            'Économie',
            'Comptabilité',
            'Musique',
            'Arts',
            'Cuisine',
            'Sport',
        ];
        $activeCats = \App\Models\Course::approved()->select('category')->distinct()->pluck('category')->toArray();
        $dbCats     = Category::orderBy('name')->pluck('name')->toArray();
        $excludedSubLanguages = ['Anglais', 'Français', 'Espagnol', 'Allemand', 'Chinois', 'Arabe', 'Italien', 'Lingala', 'Baoulé', 'Dioula'];

        $categories = collect(array_values(array_unique(array_filter(array_merge($canonicalCategories, $activeCats, $dbCats)))))
            ->reject(fn ($c) => in_array($c, $excludedSubLanguages, true))
            ->values();

        return view('cours.index', compact(
            'professeurs', 'categorie', 'q', 'typeCours', 'distance', 'tarif', 'categories'
        ));
    }
}

