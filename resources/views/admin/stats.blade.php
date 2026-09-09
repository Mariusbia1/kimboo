@extends('layouts.dashboard')

@section('title', 'Statistiques')
@section('page-title', 'Statistiques')
@section('page-subtitle', 'Indicateurs clés de performance de Kimboo')

@section('content')

{{-- Filtre période --}}
<div class="flex items-center gap-2 mb-8 flex-wrap">
    <span class="text-sm text-gray-400 mr-1">Période :</span>

    @php
        $currentPeriode = (string) request('periode', $periode ?? '30');
        if (!in_array($currentPeriode, ['3', '7', '30', '90', 'all'], true)) {
            $currentPeriode = '30';
        }
    @endphp

    @foreach(['3' => '3 jours', '7' => '7 jours', '30' => '30 jours', '90' => '90 jours', 'all' => 'Depuis le début'] as $val => $label)
    @php
        $filterValue = (string) $val;
        $isActive = $currentPeriode === $filterValue;
    @endphp
    <a href="{{ route('admin.stats', ['periode' => $filterValue]) }}"
       aria-current="{{ $isActive ? 'page' : 'false' }}"
       class="px-4 py-2 rounded-xl text-sm font-semibold transition border-2"
       style="{{ $isActive
           ? 'background-color:#FCB315 !important; border-color:#FCB315 !important; color:#000 !important; box-shadow:0 8px 18px rgba(252,179,21,0.35);'
           : 'background-color:#fff; border-color:#fff; color:#666; box-shadow:0 2px 8px rgba(0,0,0,0.08);' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

{{-- Section 1 : Trafic --}}
<p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Trafic & Audience</p>
<div class="grid grid-cols-1 gap-4 mb-8 sm:grid-cols-2 xl:grid-cols-4">

    {{-- Visiteurs uniques --}}
    <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-gray-200 transition-all duration-200" style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Visiteurs uniques</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-amber-50 text-[#FCB315]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4a4 4 0 11-8 0 4 4 0 018 0zm6 4a2 2 0 100-4 2 2 0 000 4zM3 16a2 2 0 100-4 2 2 0 000 4z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-black" style="font-family:'Poppins',sans-serif;">{{ $visiteursUniques }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $pagesVues }} pages vues</p>
    </div>

    {{-- Nouveaux vs récurrents --}}
    <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-gray-200 transition-all duration-200" style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Nouveaux visiteurs</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-emerald-50 text-emerald-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-black" style="font-family:'Poppins',sans-serif;">{{ $nouveauxVisiteurs }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $revisiteurs }} récurrents</p>
    </div>

    {{-- Pages par session --}}
    <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-gray-200 transition-all duration-200" style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Pages par session</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-indigo-50 text-indigo-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-black" style="font-family:'Poppins',sans-serif;">{{ $pagesParSession }}</p>
        <p class="text-xs text-gray-400 mt-1">pages / visiteur</p>
    </div>

    {{-- Taux de conversion --}}
    <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-gray-200 transition-all duration-200" style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Taux de conversion</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-amber-50 text-amber-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-black" style="font-family:'Poppins',sans-serif;">{{ $tauxConversion }}%</p>
        <p class="text-xs text-gray-400 mt-1">visiteurs inscrits</p>
    </div>

</div>

{{-- Section 2 : Conversion --}}
<p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Conversion & Activité</p>
<div class="grid grid-cols-1 gap-4 mb-8 sm:grid-cols-2 xl:grid-cols-4">

    <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-gray-200 transition-all duration-200" style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Inscriptions</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-slate-100 text-slate-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-black" style="font-family:'Poppins',sans-serif;">{{ $inscriptions }}</p>
        <p class="text-xs text-gray-400 mt-1">nouveaux comptes</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-gray-200 transition-all duration-200" style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Réservations</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-emerald-50 text-emerald-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-black" style="font-family:'Poppins',sans-serif;">{{ $reservations }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ number_format($revenus, 0, ',', ' ') }} FCFA confirmés</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-gray-200 transition-all duration-200" style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Messages échangés</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-indigo-50 text-indigo-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-black" style="font-family:'Poppins',sans-serif;">{{ $messages }}</p>
        <p class="text-xs text-gray-400 mt-1">messages envoyés</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-gray-200 transition-all duration-200" style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Temps sur la plateforme</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-amber-50 text-amber-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-black" style="font-family:'Poppins',sans-serif;">{{ $heuresTotalesPassees }}h</p>
        <p class="text-xs text-gray-400 mt-1">{{ $utilisateursConnectesRecemment }} actifs (7 derniers jours)</p>
    </div>

</div>

{{-- Revenus --}}
<div class="bg-white rounded-3xl p-6 sm:p-7 mb-8 flex flex-col gap-4 border border-gray-100 sm:flex-row sm:items-center sm:justify-between" style="box-shadow:0 4px 20px rgba(0,0,0,0.03);">
    <div class="min-w-0">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Revenus totaux confirmés
            {{ $periode === 'all' ? 'depuis le début' : 'sur '.$periode.' jours' }}
        </p>
        <p class="text-3xl sm:text-4xl font-extrabold text-emerald-600 break-words" style="font-family:'Poppins',sans-serif;">
            {{ number_format($revenus, 0, ',', ' ') }} <span class="text-lg font-bold text-emerald-700/60">FCFA</span>
        </p>
    </div>
    <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-emerald-50 text-emerald-600 border border-emerald-200/50 shrink-0">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
</div>

{{-- Graphiques principaux --}}
<p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Évolution dans le temps</p>
<div class="grid grid-cols-1 gap-6 mb-8 xl:grid-cols-2">

    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-100 min-w-0" style="box-shadow:0 4px 20px rgba(0,0,0,0.03);">
        <h2 class="font-bold text-gray-900 mb-4 text-sm">Visiteurs uniques par jour</h2>
        <canvas id="chartVisiteurs" height="130"></canvas>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-100 min-w-0" style="box-shadow:0 4px 20px rgba(0,0,0,0.03);">
        <h2 class="font-bold text-gray-900 mb-4 text-sm">Inscriptions par jour</h2>
        <canvas id="chartInscriptions" height="130"></canvas>
    </div>

</div>

<div class="grid grid-cols-1 gap-6 mb-8 xl:grid-cols-2">

    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-100 min-w-0" style="box-shadow:0 4px 20px rgba(0,0,0,0.03);">
        <h2 class="font-bold text-gray-900 mb-4 text-sm">Réservations par jour</h2>
        <canvas id="chartReservations" height="130"></canvas>
    </div>

    {{-- Sources de trafic --}}
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-100 min-w-0" style="box-shadow:0 4px 20px rgba(0,0,0,0.03);">
        <h2 class="font-bold text-gray-900 mb-4 text-sm">Sources de trafic</h2>
        @if($sources->isEmpty())
            <p class="text-sm text-gray-400">Pas encore de données.</p>
        @else
        <div class="space-y-3.5">
            @php $totalSources = $sources->sum('total'); @endphp
            @foreach($sources as $source)
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-semibold text-gray-700">{{ $source->source }}</span>
                    <span class="text-xs font-bold text-gray-900">
                        {{ $source->total }} <span class="text-gray-400 font-normal">({{ $totalSources > 0 ? round($source->total / $totalSources * 100) : 0 }}%)</span>
                    </span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-300" style="background:#FCB315; width:{{ $totalSources > 0 ? round($source->total / $totalSources * 100) : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

{{-- Comportement : KPIs d'engagement + Devices, Browsers, OS --}}
<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <div>
            <p class="text-sm font-semibold text-gray-400 uppercase tracking-widest">Comportement & Engagement des visiteurs</p>
            <p class="text-xs text-gray-500 mt-0.5">Analyse de la fidélité, des appareils et de l'expérience de navigation</p>
        </div>
    </div>

    {{-- Cartes de synthèse comportementale --}}
    <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-3">
        <div class="bg-white rounded-2xl p-5 border border-gray-100" style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Fidélité des visiteurs</span>
                <span class="w-7 h-7 rounded-xl flex items-center justify-center bg-amber-50 text-amber-600">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </span>
            </div>
            @php $pctFidelite = $visiteursUniques > 0 ? round(($revisiteurs / $visiteursUniques) * 100, 1) : 0; @endphp
            <p class="text-2xl font-extrabold text-black" style="font-family:'Poppins',sans-serif;">{{ $pctFidelite }}%</p>
            <div class="mt-2 text-xs text-gray-500 flex items-center justify-between">
                <span>{{ $revisiteurs }} revisiteurs</span>
                <span class="text-gray-400">/ {{ $nouveauxVisiteurs }} nouveaux</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-2 overflow-hidden">
                <div class="h-full rounded-full" style="background:#FCB315; width:{{ min(100, $pctFidelite) }}%;"></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100" style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pages par session</span>
                <span class="w-7 h-7 rounded-xl flex items-center justify-center bg-blue-50 text-blue-600">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
            </div>
            <p class="text-2xl font-extrabold text-black" style="font-family:'Poppins',sans-serif;">{{ $pagesParSession }}</p>
            <p class="mt-2 text-xs text-gray-500">{{ $pagesVues }} pages au total pour {{ $visiteursUniques }} visiteurs</p>
            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-2 overflow-hidden">
                <div class="h-full rounded-full bg-blue-500" style="width:{{ min(100, round(($pagesParSession / 8) * 100)) }}%;"></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100" style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Taux de conversion</span>
                <span class="w-7 h-7 rounded-xl flex items-center justify-center bg-emerald-50 text-emerald-600">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </span>
            </div>
            <p class="text-2xl font-extrabold text-emerald-600" style="font-family:'Poppins',sans-serif;">{{ $tauxConversion }}%</p>
            <p class="mt-2 text-xs text-gray-500">{{ $inscriptions }} inscrits sur {{ $visiteursUniques }} visiteurs uniques</p>
            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-2 overflow-hidden">
                <div class="h-full rounded-full bg-emerald-500" style="width:{{ min(100, $tauxConversion * 3) }}%;"></div>
            </div>
        </div>
    </div>

    {{-- Grille Appareils, Navigateurs, OS --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Devices --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 min-w-0 border border-gray-100" style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-black text-sm">Appareils</h2>
                <span class="text-xs text-gray-400">Mobile / Ordinateur</span>
            </div>
            <div class="relative py-2">
                <canvas id="chartDevices" height="170"></canvas>
            </div>
        </div>

        {{-- Browsers --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 min-w-0 border border-gray-100" style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-black text-sm">Navigateurs</h2>
                <span class="text-xs text-gray-400">Top plateformes</span>
            </div>
            @if($browsers->isEmpty())
                <p class="text-sm text-gray-400 py-6 text-center">Pas encore de données.</p>
            @else
            <div class="space-y-3.5">
                @php $totalBrowsers = $browsers->sum('total'); @endphp
                @foreach($browsers as $b)
                @php
                    $bName = $b->browser ?? 'Autre';
                    $bColor = match(strtolower($bName)) {
                        'chrome' => '#FCB315',
                        'safari' => '#0A84FF',
                        'edge' => '#0078D4',
                        'firefox' => '#FF7139',
                        'opera' => '#FF1B2D',
                        default => '#6B7280',
                    };
                @endphp
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-semibold text-gray-800 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full shrink-0" style="background:{{ $bColor }};"></span>
                            {{ $bName }}
                        </span>
                        <span class="text-xs font-bold text-gray-900">
                            {{ $b->total }} <span class="text-gray-400 font-normal">({{ $totalBrowsers > 0 ? round($b->total / $totalBrowsers * 100) : 0 }}%)</span>
                        </span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-300" style="background:{{ $bColor }}; width:{{ $totalBrowsers > 0 ? round($b->total / $totalBrowsers * 100) : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- OS --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6 min-w-0 border border-gray-100" style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-black text-sm">Systèmes d'exploitation</h2>
                <span class="text-xs text-gray-400">OS clients</span>
            </div>
            @if($osList->isEmpty())
                <p class="text-sm text-gray-400 py-6 text-center">Pas encore de données.</p>
            @else
            <div class="space-y-3.5">
                @php $totalOs = $osList->sum('total'); @endphp
                @foreach($osList as $o)
                @php
                    $osName = $o->os ?? 'Autre';
                    $osColor = match(strtolower($osName)) {
                        'android' => '#3DDC84',
                        'ios' => '#111827',
                        'macos' => '#64748B',
                        'windows' => '#00ADEF',
                        'linux' => '#F59E0B',
                        default => '#94A3B8',
                    };
                @endphp
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-semibold text-gray-800 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full shrink-0" style="background:{{ $osColor }};"></span>
                            {{ $osName }}
                        </span>
                        <span class="text-xs font-bold text-gray-900">
                            {{ $o->total }} <span class="text-gray-400 font-normal">({{ $totalOs > 0 ? round($o->total / $totalOs * 100) : 0 }}%)</span>
                        </span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-300" style="background:{{ $osColor }}; width:{{ $totalOs > 0 ? round($o->total / $totalOs * 100) : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>
</div>

{{-- Pages les plus vues --}}
<p class="text-sm font-semibold text-gray-400 uppercase tracking-widest mb-3">Pages & Contenu</p>
<div class="grid grid-cols-1 gap-6 xl:grid-cols-2">

    <div class="bg-white rounded-2xl p-5 sm:p-6 min-w-0 border border-gray-100" style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="font-bold text-black text-sm">Pages les plus visitées</h2>
                <p class="text-xs text-gray-400 mt-0.5">Volume de consultation par page du site</p>
            </div>
            <span class="text-xs text-gray-400 font-medium">{{ $pagesLesPlusVues->count() }} pages actives</span>
        </div>
        @if($pagesLesPlusVues->isEmpty())
            <p class="text-sm text-gray-400 py-4 text-center">Aucune page visitée sur cette période.</p>
        @else
        <div class="space-y-3">
            @php $maxPage = $pagesLesPlusVues->first()->total; @endphp
            @foreach($pagesLesPlusVues as $page)
            @php
                $badgeClass = match($page->category) {
                    'Général' => 'bg-amber-50 text-amber-800 border-amber-200',
                    'Catalogue' => 'bg-blue-50 text-blue-700 border-blue-200',
                    'Authentification' => 'bg-purple-50 text-purple-700 border-purple-200',
                    'Élève' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'Professeur' => 'bg-orange-50 text-orange-700 border-orange-200',
                    'Légal' => 'bg-gray-100 text-gray-700 border-gray-200',
                    'À propos' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                    default => 'bg-gray-50 text-gray-600 border-gray-200',
                };
            @endphp
            <div class="p-2.5 rounded-xl hover:bg-gray-50/80 transition-colors">
                <div class="flex items-center justify-between gap-3 mb-1.5">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full border {{ $badgeClass }} shrink-0">{{ $page->category }}</span>
                        <span class="font-bold text-gray-900 text-sm truncate">{{ $page->name }}</span>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-sm font-extrabold text-black">{{ number_format($page->total, 0, ',', ' ') }}</span>
                        <span class="text-xs text-gray-400 ml-0.5">visites</span>
                    </div>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-300" style="background:#FCB315; width:{{ $maxPage > 0 ? round($page->total / $maxPage * 100) : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <div class="bg-white rounded-2xl p-5 sm:p-6 min-w-0 border border-gray-100" style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="font-bold text-black text-sm">Profils professeurs les plus visités</h2>
                <p class="text-xs text-gray-400 mt-0.5">Enseignants attirant le plus de consultations d'élèves</p>
            </div>
            <span class="text-xs text-gray-400 font-medium">{{ $profsLesPlusVus->count() }} profils</span>
        </div>
        @if($profsLesPlusVus->isEmpty())
            <p class="text-sm text-gray-400 py-4 text-center">Aucune consultation de profil enseignant sur cette période.</p>
        @else
        <div class="space-y-3">
            @php $maxProf = $profsLesPlusVus->first()->total; @endphp
            @foreach($profsLesPlusVus as $prof)
            <div class="p-2.5 rounded-xl hover:bg-gray-50/80 transition-colors">
                <div class="flex items-center justify-between gap-3 mb-1.5">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <!-- Photo prof -->
                        <x-avatar :src="$prof->avatar ? Storage::url($prof->avatar) : null" :name="$prof->name" size="9" rounded="xl" />
                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5">
                                <a href="{{ $prof->url }}" target="_blank" class="font-bold text-gray-900 text-sm hover:text-[#FCB315] hover:underline truncate">
                                    {{ $prof->name }}
                                </a>
                                @if($prof->is_verified)
                                <svg class="w-3.5 h-3.5 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 truncate">
                                <span>{{ $prof->category }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-sm font-extrabold text-black">{{ number_format($prof->total, 0, ',', ' ') }}</span>
                        <span class="text-xs text-gray-400 ml-0.5">vues</span>
                    </div>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-300" style="background:#FCB315; width:{{ $maxProf > 0 ? round($prof->total / $maxProf * 100) : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

{{-- Section 4 : Traçabilité utilisateurs & Temps passé --}}
<p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-8 mb-3">Traçabilité & Engagement utilisateurs</p>
<div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-100" style="box-shadow:0 4px 20px rgba(0,0,0,0.03);">
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-base font-bold text-gray-900">Utilisateurs les plus engagés (temps cumulé)</h2>
            <p class="text-xs text-gray-400 mt-1">Suivi de la durée passée sur la plateforme et des dernières connexions</p>
        </div>
        <a href="{{ route('admin.users') }}"
           class="inline-flex items-center gap-1.5 text-xs font-bold px-4 py-2 rounded-xl text-black transition-all duration-200 hover:shadow-md hover:opacity-95 self-start sm:self-auto"
           style="background:#FCB315;">
            <span>Voir tous les utilisateurs</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    @if($topUtilisateursTemps->isEmpty())
        <p class="text-sm text-gray-400">Aucune donnée d'activité pour le moment.</p>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[600px]">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-2.5 px-3 text-gray-400 font-medium text-xs uppercase">Utilisateur</th>
                    <th class="text-left py-2.5 px-3 text-gray-400 font-medium text-xs uppercase">Rôle</th>
                    <th class="text-left py-2.5 px-3 text-gray-400 font-medium text-xs uppercase">Dernière connexion</th>
                    <th class="text-left py-2.5 px-3 text-gray-400 font-medium text-xs uppercase">Temps total passé</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topUtilisateursTemps as $u)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                    <td class="py-2.5 px-3">
                        <div class="flex items-center gap-2">
                            <x-avatar :user="$u" size="7" rounded="full"/>
                            <div>
                                <p class="font-medium text-black text-sm">{{ $u->name }}</p>
                                <p class="text-xs text-gray-400">{{ $u->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-2.5 px-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $u->role === 'professeur' ? 'bg-blue-100 text-blue-700' : ($u->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ ucfirst($u->role) }}
                        </span>
                    </td>
                    <td class="py-2.5 px-3 text-gray-600 text-xs">
                        {{ $u->last_login_at ? $u->last_login_at->diffForHumans() : 'Jamais' }}
                    </td>
                    <td class="py-2.5 px-3 font-semibold text-black text-xs">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200">
                            <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $u->formatted_time_spent }}</span>
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
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
