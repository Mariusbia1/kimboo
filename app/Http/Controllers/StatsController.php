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
        $periode = $request->get('periode', '30');
        $periodes = ['3', '7', '30', '90', 'all'];

        if (!in_array($periode, $periodes)) $periode = '30';

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

        // --- Pages les plus vues ---
        $pagesLesPlusVues = $pv()->select('url', DB::raw('COUNT(*) as total'))
            ->groupBy('url')->orderByDesc('total')->limit(8)->get();

        // --- Professeurs les plus visités ---
        $profsLesPlusVus = $pv()->select('url', DB::raw('COUNT(*) as total'))
            ->where('url', 'like', 'professeur/%')
            ->groupBy('url')->orderByDesc('total')->limit(5)->get();

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

        return view('admin.stats', compact(
            'visiteursUniques', 'pagesVues', 'nouveauxVisiteurs',
            'revisiteurs', 'tauxConversion', 'pagesParSession',
            'devices', 'browsers', 'osList', 'sources',
            'visiteursParJour', 'pagesLesPlusVues', 'profsLesPlusVus',
            'inscriptions', 'inscriptionsParJour',
            'reservations', 'revenus', 'reservationsParJour',
            'messages', 'periode', 'periodes'
        ));
    }
}
