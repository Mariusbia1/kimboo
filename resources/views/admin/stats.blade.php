@extends('layouts.dashboard')

@section('title', 'Statistiques')
@section('page-title', 'Statistiques')
@section('page-subtitle', 'Indicateurs clés de performance de Kimboo')

@section('content')

{{-- Filtre période --}}
<div class="flex items-center gap-2 mb-8 flex-wrap">
    <span class="text-sm text-gray-400 mr-1">Période :</span>

    @foreach(['3' => '3 jours', '7' => '7 jours', '30' => '30 jours', '90' => '90 jours', 'all' => 'Depuis le début'] as $val => $label)
    <a href="{{ route('admin.stats', ['periode' => $val]) }}"
       class="px-4 py-2 rounded-xl text-sm font-semibold transition"
       style="{{ $periode === $val
           ? 'background:#FCB315; color:#000;'
           : 'background:#fff; color:#666; box-shadow:0 2px 8px rgba(0,0,0,0.08);' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

{{-- Section 1 : Trafic --}}
<p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Trafic</p>
<div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:1rem;" class="mb-8">

    {{-- Visiteurs uniques --}}
    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-400">Visiteurs uniques</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#FCB31520;">
                <svg class="w-4 h-4" style="color:#FCB315;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4a4 4 0 11-8 0 4 4 0 018 0zm6 4a2 2 0 100-4 2 2 0 000 4zM3 16a2 2 0 100-4 2 2 0 000 4z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">{{ $visiteursUniques }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $pagesVues }} pages vues</p>
    </div>

    {{-- Nouveaux vs récurrents --}}
    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-400">Nouveaux visiteurs</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#10b98120;">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">{{ $nouveauxVisiteurs }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $revisiteurs }} récurrents</p>
    </div>

    {{-- Pages par session --}}
    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-400">Pages par session</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#6366f120;">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">{{ $pagesParSession }}</p>
        <p class="text-xs text-gray-400 mt-1">pages / visiteur</p>
    </div>

    {{-- Taux de conversion --}}
    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-400">Taux de conversion</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#f59e0b20;">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">{{ $tauxConversion }}%</p>
        <p class="text-xs text-gray-400 mt-1">visiteurs inscrits</p>
    </div>

</div>

{{-- Section 2 : Conversion --}}
<p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Conversion & Activité</p>
<div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:1rem;" class="mb-8">

    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-400">Inscriptions</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#1A2B3C20;">
                <svg class="w-4 h-4" style="color:#1A2B3C;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">{{ $inscriptions }}</p>
        <p class="text-xs text-gray-400 mt-1">nouveaux comptes</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-400">Réservations</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#10b98120;">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">{{ $reservations }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ number_format($revenus, 0, ',', ' ') }} FCFA confirmés</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-400">Messages échangés</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#6366f120;">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">{{ $messages }}</p>
        <p class="text-xs text-gray-400 mt-1">messages envoyés</p>
    </div>

</div>

{{-- Revenus --}}
<div class="bg-white rounded-2xl p-6 mb-8 flex items-center justify-between" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
    <div>
        <p class="text-xs text-gray-400 mb-1">Revenus totaux confirmés
            {{ $periode === 'all' ? 'depuis le début' : 'sur '.$periode.' jours' }}
        </p>
        <p class="text-4xl font-bold text-green-500" style="font-family:'Poppins',sans-serif;">
            {{ number_format($revenus, 0, ',', ' ') }} FCFA
        </p>
    </div>
    <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-green-50">
        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
</div>

{{-- Graphiques principaux --}}
<p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Évolution dans le temps</p>
<div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem;" class="mb-8">

    <div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
        <h2 class="font-semibold text-black mb-4 text-sm">Visiteurs uniques par jour</h2>
        <canvas id="chartVisiteurs" height="130"></canvas>
    </div>

    <div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
        <h2 class="font-semibold text-black mb-4 text-sm">Inscriptions par jour</h2>
        <canvas id="chartInscriptions" height="130"></canvas>
    </div>

</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem;" class="mb-8">

    <div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
        <h2 class="font-semibold text-black mb-4 text-sm">Réservations par jour</h2>
        <canvas id="chartReservations" height="130"></canvas>
    </div>

    {{-- Sources de trafic --}}
    <div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
        <h2 class="font-semibold text-black mb-4 text-sm">Sources de trafic</h2>
        @if($sources->isEmpty())
            <p class="text-sm text-gray-400">Pas encore de données.</p>
        @else
        <div class="space-y-3">
            @php $totalSources = $sources->sum('total'); @endphp
            @foreach($sources as $source)
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs text-gray-600">{{ $source->source }}</span>
                    <span class="text-xs font-bold" style="color:#FCB315;">
                        {{ $source->total }} ({{ $totalSources > 0 ? round($source->total / $totalSources * 100) : 0 }}%)
                    </span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full" style="background:#FCB315; width:{{ $totalSources > 0 ? round($source->total / $totalSources * 100) : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

{{-- Comportement : Devices, Browsers, OS --}}
<p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Comportement des visiteurs</p>
<div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:1.5rem;" class="mb-8">

    {{-- Devices --}}
    <div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
        <h2 class="font-semibold text-black mb-4 text-sm">Appareils</h2>
        <canvas id="chartDevices" height="160"></canvas>
    </div>

    {{-- Browsers --}}
    <div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
        <h2 class="font-semibold text-black mb-4 text-sm">Navigateurs</h2>
        @if($browsers->isEmpty())
            <p class="text-sm text-gray-400">Pas encore de données.</p>
        @else
        <div class="space-y-3">
            @php $totalBrowsers = $browsers->sum('total'); @endphp
            @foreach($browsers as $b)
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs text-gray-600">{{ $b->browser ?? 'Autre' }}</span>
                    <span class="text-xs font-bold text-gray-700">{{ $b->total }}</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full" style="background:#1A2B3C; width:{{ $totalBrowsers > 0 ? round($b->total / $totalBrowsers * 100) : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- OS --}}
    <div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
        <h2 class="font-semibold text-black mb-4 text-sm">Systèmes d'exploitation</h2>
        @if($osList->isEmpty())
            <p class="text-sm text-gray-400">Pas encore de données.</p>
        @else
        <div class="space-y-3">
            @php $totalOs = $osList->sum('total'); @endphp
            @foreach($osList as $o)
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs text-gray-600">{{ $o->os ?? 'Autre' }}</span>
                    <span class="text-xs font-bold text-gray-700">{{ $o->total }}</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full" style="background:#10b981; width:{{ $totalOs > 0 ? round($o->total / $totalOs * 100) : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

{{-- Pages les plus vues --}}
<p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Pages & Contenu</p>
<div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem;">

    <div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
        <h2 class="font-semibold text-black mb-4 text-sm">Pages les plus visitées</h2>
        @if($pagesLesPlusVues->isEmpty())
            <p class="text-sm text-gray-400">Pas encore de données.</p>
        @else
        <div class="space-y-3">
            @php $maxPage = $pagesLesPlusVues->first()->total; @endphp
            @foreach($pagesLesPlusVues as $page)
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs text-gray-600 truncate max-w-xs">/{{ $page->url }}</span>
                    <span class="text-xs font-bold ml-2" style="color:#FCB315;">{{ $page->total }}</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full" style="background:#FCB315; width:{{ $maxPage > 0 ? round($page->total / $maxPage * 100) : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
        <h2 class="font-semibold text-black mb-4 text-sm">Profils professeurs les plus visités</h2>
        @if($profsLesPlusVus->isEmpty())
            <p class="text-sm text-gray-400">Pas encore de données.</p>
        @else
        <div class="space-y-3">
            @php $maxProf = $profsLesPlusVus->first()->total; @endphp
            @foreach($profsLesPlusVus as $page)
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs text-gray-600 truncate max-w-xs">/{{ $page->url }}</span>
                    <span class="text-xs font-bold ml-2" style="color:#FCB315;">{{ $page->total }}</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full" style="background:#FCB315; width:{{ $maxProf > 0 ? round($page->total / $maxProf * 100) : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const visiteursData    = @json($visiteursParJour);
const inscriptionsData = @json($inscriptionsParJour);
const reservationsData = @json($reservationsParJour);
const devicesData      = @json($devices);

function buildLineChart(canvasId, data, label, color) {
    new Chart(document.getElementById(canvasId), {
        type: 'line',
        data: {
            labels: data.map(d => d.date),
            datasets: [{
                label,
                data: data.map(d => d.total),
                borderColor: color,
                backgroundColor: color + '22',
                borderWidth: 2,
                pointRadius: 3,
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                y: { beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });
}

// Graphique donut devices
const deviceLabels = devicesData.map(d => d.device_type ?? 'Autre');
const deviceValues = devicesData.map(d => d.total);

if (deviceValues.length > 0) {
    new Chart(document.getElementById('chartDevices'), {
        type: 'doughnut',
        data: {
            labels: deviceLabels,
            datasets: [{
                data: deviceValues,
                backgroundColor: ['#FCB315', '#1A2B3C', '#10b981'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 11 } } }
            }
        }
    });
}

buildLineChart('chartVisiteurs',    visiteursData,    'Visiteurs',    '#FCB315');
buildLineChart('chartInscriptions', inscriptionsData, 'Inscriptions', '#1A2B3C');
buildLineChart('chartReservations', reservationsData, 'Réservations', '#10b981');
</script>
@endpush
