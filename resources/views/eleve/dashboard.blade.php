@extends('layouts.dashboard')

@section('title', 'Mon espace apprenant')
@section('page-title', 'Mon espace')
@section('page-subtitle', 'Bienvenue, '.auth()->user()->name . ' !')

@section('content')

@if(session('success'))
<div class="mb-6 px-4 py-3 rounded-2xl text-sm font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200/70 flex items-center gap-2.5">
    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>{{ session('success') }}</span>
</div>
@endif

{{-- Bannière d'accueil premium --}}
<div class="relative overflow-hidden rounded-3xl p-6 sm:p-8 mb-8 text-white border border-gray-800"
     style="background: radial-gradient(135.43% 120.55% at 100% 0%, #1A2B3C 0%, #0B0F19 100%); box-shadow:0 8px 30px rgba(0,0,0,0.12);">
    <div class="relative z-10 max-w-xl">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold text-[#FCB315] bg-[#FCB315]/10 border border-[#FCB315]/25 mb-3">
            <span class="w-1.5 h-1.5 rounded-full bg-[#FCB315] animate-pulse"></span>
            <span>Espace Apprenant</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
            Bonjour, {{ auth()->user()->name }} !
        </h1>
        <p class="text-gray-300 text-xs sm:text-sm mt-2 leading-relaxed">
            Consultez vos prochaines séances de cours, suivez votre progression et réservez de nouveaux cours particuliers avec des professeurs certifiés.
        </p>
        <div class="mt-6 flex flex-wrap items-center gap-3">
            <a href="{{ route('cours.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-xs text-black transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5"
               style="background:#FCB315;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
                <span>Trouver un professeur</span>
            </a>
            <a href="{{ route('eleve.mes-cours') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-xs text-white bg-white/10 hover:bg-white/15 border border-white/10 transition-colors">
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span>Voir mes cours</span>
            </a>
        </div>
    </div>
    {{-- Glow décoratif --}}
    <div class="absolute -right-16 -bottom-16 w-64 h-64 rounded-full blur-3xl opacity-25 pointer-events-none" style="background:#FCB315;"></div>
</div>

{{-- Stats --}}
<p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Aperçu de vos séances</p>
<div class="grid grid-cols-1 gap-4 mb-8 sm:grid-cols-2 lg:grid-cols-3">

    <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-gray-200 transition-all duration-200"
         style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Réservations</span>
            <span class="w-9 h-9 rounded-xl flex items-center justify-center bg-amber-50 text-[#FCB315]">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-black" style="font-family:'Poppins',sans-serif;">{{ $reservationsCount }}</p>
        <p class="text-xs text-gray-400 mt-1">séance(s) réservée(s) au total</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-gray-200 transition-all duration-200"
         style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Cours terminés</span>
            <span class="w-9 h-9 rounded-xl flex items-center justify-center bg-emerald-50 text-emerald-600">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-emerald-600" style="font-family:'Poppins',sans-serif;">{{ $coursesTermines }}</p>
        <p class="text-xs text-gray-400 mt-1">séance(s) effectuée(s) avec succès</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-gray-200 transition-all duration-200"
         style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Cours confirmés</span>
            <span class="w-9 h-9 rounded-xl flex items-center justify-center bg-indigo-50 text-indigo-600">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-black" style="font-family:'Poppins',sans-serif;">{{ $coursesConfirmes }}</p>
        <p class="text-xs text-gray-400 mt-1">séance(s) confirmée(s) à venir</p>
    </div>

</div>

{{-- Recherche rapide --}}
<div class="bg-white rounded-3xl p-6 sm:p-7 mb-8 border border-gray-100" style="box-shadow:0 4px 20px rgba(0,0,0,0.03);">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#FCB315]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
                Trouver un cours ou une matière
            </h2>
            <p class="text-xs text-gray-400 mt-0.5">Recherchez parmi nos enseignants qualifiés à domicile ou en ligne</p>
        </div>
    </div>
    <form action="{{ route('cours.index') }}" method="GET" class="flex flex-col gap-3 sm:flex-row">
        <div class="relative flex-1">
            <svg class="w-4 h-4 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
            <input type="text" name="q" placeholder="Maths, Anglais, Informatique, Physique, Musique..."
                   class="w-full pl-11 pr-4 py-3 text-sm bg-gray-50/80 border border-gray-200/80 rounded-2xl outline-none focus:border-[#FCB315] focus:bg-white transition-all"/>
        </div>
        <button type="submit"
                class="px-6 py-3 rounded-2xl text-black text-xs font-bold transition-all duration-200 hover:shadow-md hover:opacity-95 flex items-center justify-center gap-2 shrink-0"
                style="background:#FCB315;">
            <span>Rechercher</span>
        </button>
    </form>
</div>

{{-- Prochains cours --}}
<div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-100" style="box-shadow:0 4px 20px rgba(0,0,0,0.03);">
    <div class="flex flex-col gap-2 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Prochains cours à venir
            </h2>
            <p class="text-xs text-gray-400 mt-0.5">Vos séances programmées avec vos professeurs</p>
        </div>
        <a href="{{ route('eleve.mes-cours') }}"
           class="text-xs font-bold hover:underline transition inline-flex items-center gap-1"
           style="color:#1A2B3C;">
            <span>Voir tous mes cours</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    @if($prochainsCoours->isEmpty())
    <div class="text-center py-12 px-4">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-3 bg-gray-50 border border-gray-100">
            <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <p class="font-bold text-gray-900 text-sm mb-1">Aucun cours à venir</p>
        <p class="text-gray-400 text-xs mb-5 max-w-sm mx-auto">Vous n'avez pas encore de séance confirmée. Choisissez un cours et prenez rendez-vous.</p>
        <a href="{{ route('cours.index') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-black text-xs font-bold transition-all duration-200 hover:shadow-md hover:opacity-95"
           style="background:#FCB315;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
            <span>Trouver un cours maintenant</span>
        </a>
    </div>
    @else
    <div class="space-y-3">
        @foreach($prochainsCoours as $booking)
        <div class="flex flex-col gap-4 p-4 rounded-2xl bg-gray-50/70 border border-gray-100 hover:bg-gray-50 transition-all duration-150 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3.5">
                <x-avatar :user="$booking->course->teacherProfile->user" size="11" rounded="full"/>
                <div>
                    <p class="font-bold text-gray-900 text-sm">{{ $booking->course->title }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Avec <span class="font-semibold text-gray-700">{{ $booking->course->teacherProfile->user->name }}</span></p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-4 sm:gap-6">
                <div class="flex items-center gap-2 text-xs font-semibold text-gray-700 bg-white px-3 py-1.5 rounded-xl border border-gray-100 shadow-xs">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ \Carbon\Carbon::parse($booking->scheduled_at)->format('d/m/Y') }}</span>
                    <span class="text-gray-300">·</span>
                    <span class="text-gray-500">{{ \Carbon\Carbon::parse($booking->scheduled_at)->format('H:i') }}</span>
                </div>

                <div class="text-left sm:text-right">
                    <p class="font-extrabold text-sm text-gray-900">
                        {{ number_format($booking->total_price, 0, ',', ' ') }} <span class="text-xs font-normal text-gray-400">FCFA</span>
                    </p>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Confirmé
                    </span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@endsection
