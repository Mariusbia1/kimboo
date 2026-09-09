<?php

namespace App\Http\Controllers;

use App\Models\PageView;
use App\Models\User;
use App\Models\Booking;
use App\Models\Course;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'periode' => ['nullable', 'string', 'in:3,7,30,90,all'],
        ]);

        $periodes = [
            '3'   => '3 derniers jours',
            '7'   => '7 derniers jours',
            '30'  => '30 derniers jours',
            '90'  => '90 derniers jours',
            'all' => 'Tout l\'historique',
        ];

        $periode = $validated['periode'] ?? '30';
        $debut = $periode === 'all' ? null : now()->subDays((int)$periode);

        // Helper query
        $pv = fn() => $debut
            ? PageView::where('viewed_at', '>=', $debut)
            : PageView::query();

        $uq = fn() => $debut
            ? User::where('created_at', '>=', $debut)
            : User::query();

        $bq = fn() => $debut
            ? Booking::where('created_at', '>=', $debut)
            : Booking::query();

        $mq = fn() => $debut
            ? Message::where('created_at', '>=', $debut)
            : Message::query();

        // --- Trafic ---
        $visiteursUniques  = $pv()->distinct('ip')->count('ip');
        $pagesVues         = $pv()->count();
        $nouveauxVisiteurs = $pv()->where('is_new_visitor', true)->distinct('ip')->count('ip');
        $revisiteurs       = $visiteursUniques - $nouveauxVisiteurs;

        // Taux de conversion (visiteurs ayant un compte / visiteurs uniques)
        $tauxConversion = $visiteursUniques > 0
            ? round(($uq()->count() / max($visiteursUniques, 1)) * 100, 1)
            : 0;

        // Pages par session (approx.)
        $sessions = max($visiteursUniques, 1);
        $pagesParSession = round($pagesVues / $sessions, 1);

        // --- Devices ---
        $devices = $pv()->select('device_type', DB::raw('COUNT(*) as total'))
            ->groupBy('device_type')->get();

        // --- Browsers ---
        $browsers = $pv()->select('browser', DB::raw('COUNT(*) as total'))
            ->groupBy('browser')->orderByDesc('total')->limit(5)->get();

        // --- OS ---
        $osList = $pv()->select('os', DB::raw('COUNT(*) as total'))
            ->groupBy('os')->orderByDesc('total')->limit(5)->get();

        // --- Sources de trafic ---
        $sources = $pv()->select(
                DB::raw("CASE
                    WHEN referer IS NULL THEN 'Direct'
                    WHEN referer LIKE '%google%' THEN 'Google'
                    WHEN referer LIKE '%facebook%' THEN 'Facebook'
                    WHEN referer LIKE '%instagram%' THEN 'Instagram'
                    WHEN referer LIKE '%twitter%' OR referer LIKE '%t.co%' THEN 'Twitter/X'
                    ELSE 'Autre'
                END as source"),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('source')->orderByDesc('total')->get();

        // --- Graphiques par jour ---
        $visiteursParJour = $pv()->select(
                DB::raw('DATE(viewed_at) as date'),
                DB::raw('COUNT(DISTINCT ip) as total')
            )->groupBy('date')->orderBy('date')->get();

        // --- Pages les plus vues (normalisées et nommées avec précision) ---
        $rawPageViews = $pv()
            ->select('url', DB::raw('COUNT(*) as total'))
            ->where('url', 'not like', 'storage%')
            ->where('url', 'not like', 'images%')
            ->where('url', 'not like', 'build%')
            ->where('url', 'not like', 'api%')
            ->where('url', 'not like', 'user/heartbeat%')
            ->where('url', 'not like', 'admin%')
            ->where('url', 'not like', '%.php')
            ->groupBy('url')
            ->get();

        $groupedPages = [];
        foreach ($rawPageViews as $item) {
            $clean = trim($item->url, '/');
            // Les profils individuels sont affichés dans la carte dédiée
            if (preg_match('/^professeur\/[0-9]+$/', $clean)) {
                continue;
            }
            $groupedPages[$clean] = ($groupedPages[$clean] ?? 0) + (int) $item->total;
        }
        arsort($groupedPages);
        $topPages = array_slice($groupedPages, 0, 8, true);

        $pageNamesMap = [
            ''                           => ['name' => 'Accueil', 'category' => 'Général'],
            'cours'                      => ['name' => 'Recherche de cours', 'category' => 'Catalogue'],
            'login'                      => ['name' => 'Connexion', 'category' => 'Authentification'],
            'register'                   => ['name' => 'Inscription', 'category' => 'Authentification'],
            'dashboard'                  => ['name' => 'Tableau de bord', 'category' => 'Espace membre'],
            'eleve/dashboard'            => ['name' => 'Espace Élève', 'category' => 'Élève'],
            'professeur/dashboard'       => ['name' => 'Espace Professeur', 'category' => 'Professeur'],
            'politique-confidentialite'  => ['name' => 'Politique de confidentialité', 'category' => 'Légal'],
            'qui-sommes-nous'            => ['name' => 'Qui sommes-nous', 'category' => 'À propos'],
            'comment-ca-marche'          => ['name' => 'Comment ça marche', 'category' => 'À propos'],
            'devenir-professeur'         => ['name' => 'Devenir professeur', 'category' => 'Recrutement'],
            'contact'                    => ['name' => 'Contact & Support', 'category' => 'Support'],
            'conditions-generales'       => ['name' => 'Conditions générales', 'category' => 'Légal'],
            'mentions-legales'           => ['name' => 'Mentions légales', 'category' => 'Légal'],
            'eleve/reservations'         => ['name' => 'Mes réservations (Élève)', 'category' => 'Élève'],
            'eleve/mes-cours'            => ['name' => 'Mes cours suivis', 'category' => 'Élève'],
            'eleve/favoris'              => ['name' => 'Professeurs favoris', 'category' => 'Élève'],
            'eleve/profil'               => ['name' => 'Mon profil élève', 'category' => 'Élève'],
            'eleve/calendrier'           => ['name' => 'Planning élève', 'category' => 'Élève'],
            'professeur/cours/ajouter'   => ['name' => 'Ajouter un cours', 'category' => 'Professeur'],
            'professeur/profil/modifier' => ['name' => 'Modifier mon profil', 'category' => 'Professeur'],
            'professeur/reservations'    => ['name' => 'Réservations reçues', 'category' => 'Professeur'],
            'professeur/calendrier'      => ['name' => 'Planning professeur', 'category' => 'Professeur'],
            'messages'                   => ['name' => 'Messagerie interne', 'category' => 'Communication'],
            'favoris'                    => ['name' => 'Liste des favoris', 'category' => 'Élève'],
            'forgot-password'            => ['name' => 'Mot de passe oublié', 'category' => 'Authentification'],
        ];

        $pagesLesPlusVues = collect();
        foreach ($topPages as $clean => $total) {
            $info = $pageNamesMap[$clean] ?? null;

            if ($info) {
                $name = $info['name'];
                $category = $info['category'];
            } elseif (str_starts_with($clean, 'messages/')) {
                $name = 'Discussion privée';
                $category = 'Communication';
            } else {
                $name = ucfirst(str_replace(['-', '_', '/'], ' ', $clean ?: 'Accueil'));
                $category = 'Page';
            }

            $pagesLesPlusVues->push((object) [
                'name'      => $name,
                'path'      => '/' . ltrim($clean, '/'),
                'category'  => $category,
                'total'     => (int) $total,
            ]);
        }

        // --- Professeurs les plus visités (uniquement vrais profils publics avec noms et données réelles) ---
        $rawProfsQuery = $pv()
            ->select('url', DB::raw('COUNT(*) as total'))
            ->where('url', 'like', '%professeur/%')
            ->groupBy('url')
            ->get();

        $teacherVisits = [];
        foreach ($rawProfsQuery as $item) {
            $clean = trim($item->url, '/');
            if (preg_match('/^professeur\/([0-9]+)$/', $clean, $m)) {
                $id = (int) $m[1];
                $teacherVisits[$id] = ($teacherVisits[$id] ?? 0) + (int) $item->total;
            }
        }
        arsort($teacherVisits);

        $teacherProfiles = \App\Models\TeacherProfile::with(['user', 'courses'])
            ->whereIn('id', array_keys($teacherVisits))
            ->get()
            ->keyBy('id');

        $profsLesPlusVus = collect();
        foreach ($teacherVisits as $id => $total) {
            $profile = $teacherProfiles->get($id);
            if (!$profile || !$profile->user) {
                continue;
            }
            $profsLesPlusVus->push((object) [
                'id'          => $profile->id,
                'name'        => $profile->user->name,
                'avatar'      => $profile->user->avatar,
                'category'    => $profile->courses->first()?->category ?? 'Professeur certifié',
                'url'         => route('professeur.profil', $profile->id),
                'path'        => '/professeur/' . $profile->id,
                'total'       => (int) $total,
                'is_verified' => (bool) $profile->is_verified,
            ]);
            if ($profsLesPlusVus->count() >= 8) {
                break;
            }
        }

        // --- Inscriptions ---
        $inscriptions = $uq()->count();
        $inscriptionsParJour = $uq()->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )->groupBy('date')->orderBy('date')->get();

        // --- Réservations & revenus ---
        $reservations = $bq()->count();
        $revenus = $bq()->where('status', 'confirmed')->sum('total_price');
        $reservationsParJour = $bq()->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )->groupBy('date')->orderBy('date')->get();

        // --- Messages ---
        $messages = $mq()->count();

        // --- Temps passé & Activité utilisateurs ---
        $totalTempsSecondes = User::sum('time_spent_seconds');
        $heuresTotalesPassees = round($totalTempsSecondes / 3600, 1);
        $utilisateursConnectesRecemment = User::where('last_login_at', '>=', now()->subDays(7))->count();
        $topUtilisateursTemps = User::orderByDesc('time_spent_seconds')->where('time_spent_seconds', '>', 0)->limit(5)->get();

        return view('admin.stats', compact(
            'visiteursUniques', 'pagesVues', 'nouveauxVisiteurs',
            'revisiteurs', 'tauxConversion', 'pagesParSession',
            'devices', 'browsers', 'osList', 'sources',
            'visiteursParJour', 'pagesLesPlusVues', 'profsLesPlusVus',
            'inscriptions', 'inscriptionsParJour',
            'reservations', 'revenus', 'reservationsParJour',
            'messages', 'periode', 'periodes',
            'totalTempsSecondes', 'heuresTotalesPassees',
            'utilisateursConnectesRecemment', 'topUtilisateursTemps'
        ));
    }
}
