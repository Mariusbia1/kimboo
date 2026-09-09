@extends('layouts.dashboard')

@section('title', 'Tableau de bord enseignant')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Bienvenue, ' . auth()->user()->name)

@section('content')

@if(session('success'))
<div class="mb-6 px-4 py-3 rounded-2xl text-sm font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200/70 flex items-center gap-2.5">
    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>{{ session('success') }}</span>
</div>
@endif

{{-- Bannière d'accueil enseignant --}}
<div class="relative overflow-hidden rounded-3xl p-6 sm:p-8 mb-8 text-white border border-gray-800"
     style="background: radial-gradient(135.43% 120.55% at 100% 0%, #1A2B3C 0%, #0B0F19 100%); box-shadow:0 8px 30px rgba(0,0,0,0.12);">
    <div class="relative z-10 max-w-xl">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold text-[#FCB315] bg-[#FCB315]/10 border border-[#FCB315]/25 mb-3">
            <span class="w-1.5 h-1.5 rounded-full bg-[#FCB315] animate-pulse"></span>
            <span>Espace Enseignant</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
            Bonjour, {{ auth()->user()->name }} !
        </h1>
        <p class="text-gray-300 text-xs sm:text-sm mt-2 leading-relaxed">
            Consultez vos statistiques, gérez vos cours au catalogue et planifiez vos prochaines séances avec vos élèves.
        </p>
        <div class="mt-6 flex flex-wrap items-center gap-3">
            <a href="{{ route('professeur.create-cours') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-xs text-black transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5"
               style="background:#FCB315;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Publier un nouveau cours</span>
            </a>
            <a href="{{ route('professeur.reservations') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-xs text-white bg-white/10 hover:bg-white/15 border border-white/10 transition-colors">
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Gérer les réservations</span>
            </a>
        </div>
    </div>
    {{-- Glow décoratif --}}
    <div class="absolute -right-16 -bottom-16 w-64 h-64 rounded-full blur-3xl opacity-25 pointer-events-none" style="background:#FCB315;"></div>
</div>

{{-- Stats --}}
<p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Aperçu des performances</p>
<div class="grid grid-cols-1 gap-4 mb-8 sm:grid-cols-2 xl:grid-cols-5">

    <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-gray-200 transition-all duration-200"
         style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Cours publiés</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-amber-50 text-[#FCB315]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-black" style="font-family:'Poppins',sans-serif;">{{ $courses->count() }}</p>
        <p class="text-xs text-gray-400 mt-1">cours créés au total</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-gray-200 transition-all duration-200"
         style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Réservations</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-indigo-50 text-indigo-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-black" style="font-family:'Poppins',sans-serif;">{{ $reservationsCount }}</p>
        <p class="text-xs text-gray-400 mt-1">séance(s) réservée(s)</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-gray-200 transition-all duration-200"
         style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Élèves</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-emerald-50 text-emerald-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4a4 4 0 11-8 0 4 4 0 018 0zm6 4a2 2 0 100-4 2 2 0 000 4zM3 16a2 2 0 100-4 2 2 0 000 4z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-black" style="font-family:'Poppins',sans-serif;">{{ $nombreEleves }}</p>
        <p class="text-xs text-gray-400 mt-1">élève(s) différent(s)</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-gray-200 transition-all duration-200"
         style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Cours donnés</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-slate-100 text-slate-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-black" style="font-family:'Poppins',sans-serif;">{{ $totalCoursDonnes }}</p>
        <p class="text-xs text-gray-400 mt-1">séance(s) terminée(s)</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-gray-200 transition-all duration-200"
         style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Cagnotte ce mois</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-emerald-50 text-emerald-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </span>
        </div>
        <p class="text-2xl sm:text-3xl font-extrabold text-emerald-600" style="font-family:'Poppins',sans-serif;">
            {{ number_format($cagnotteMensuelle, 0, ',', ' ') }}
        </p>
        <p class="text-xs text-gray-400 mt-1">FCFA cumulés ce mois</p>
    </div>

</div>

{{-- Calendrier + Cours --}}
<div class="grid grid-cols-1 gap-6 mb-8 xl:grid-cols-2">

    {{-- Calendrier --}}
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-100" style="box-shadow:0 4px 20px rgba(0,0,0,0.03);">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#FCB315]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Calendrier des séances</span>
                </h2>
                <p class="text-xs text-gray-400 mt-0.5">Cette semaine et la suivante</p>
            </div>
            <a href="{{ route('professeur.reservations') }}" class="text-xs font-bold text-gray-600 hover:text-black hover:underline">
                Toutes les séances
            </a>
        </div>

        @if($reservationsSemaine->isEmpty())
        <div class="text-center py-12 px-4">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-3 bg-gray-50 border border-gray-100">
                <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="font-bold text-gray-900 text-sm mb-1">Aucun cours prévu</p>
            <p class="text-xs text-gray-400 max-w-xs mx-auto">Vous n'avez pas de réservations confirmées pour les deux prochaines semaines.</p>
        </div>
        @else
        <div class="space-y-3">
            @foreach($reservationsSemaine as $booking)
            <div class="flex flex-col gap-3 p-3.5 rounded-2xl bg-gray-50/70 border border-gray-100 hover:bg-gray-50 transition sm:flex-row sm:items-center sm:gap-4">
                <x-avatar :user="$booking->user" size="10" rounded="full"/>
                <div class="flex-1">
                    <p class="font-bold text-gray-900 text-sm">{{ $booking->user->name }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $booking->course->title }}</p>
                </div>
                <div class="text-left sm:text-right">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-white border border-gray-100 text-xs font-semibold text-gray-700 shadow-xs">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ \Carbon\Carbon::parse($booking->scheduled_at)->format('d/m') }}</span>
                        <span class="text-gray-300">·</span>
                        <span class="text-gray-500">{{ \Carbon\Carbon::parse($booking->scheduled_at)->format('H:i') }}</span>
                    </div>
                </div>
                <div>
                    @if($booking->status === 'confirmé')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Confirmé
                    </span>
                    @elseif($booking->status === 'en_attente')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        En attente
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                        {{ ucfirst($booking->status) }}
                    </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Mes cours --}}
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-100" style="box-shadow:0 4px 20px rgba(0,0,0,0.03);">
        <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#FCB315]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span>Mes cours au catalogue</span>
                </h2>
                <p class="text-xs text-gray-400 mt-0.5">Vos offres de cours visibles par les apprenants</p>
            </div>
            <a href="{{ route('professeur.create-cours') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-black transition-all duration-200 hover:shadow-md hover:opacity-95 self-start sm:self-auto"
               style="background:#FCB315;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Ajouter un cours</span>
            </a>
        </div>

        @if($courses->isEmpty())
        <div class="text-center py-12 px-4">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-3 bg-gray-50 border border-gray-100">
                <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <p class="font-bold text-gray-900 text-sm mb-1">Aucun cours publié</p>
            <p class="text-xs text-gray-400 mb-4 max-w-xs mx-auto">Créez votre premier cours pour commencer à recevoir des élèves.</p>
            <a href="{{ route('professeur.create-cours') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-black text-xs font-bold"
               style="background:#FCB315;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Créer mon premier cours</span>
            </a>
        </div>
        @else
        <div class="space-y-3">
            @foreach($courses as $course)
            <div class="flex flex-col gap-3 p-4 rounded-2xl border border-gray-100 bg-gray-50/70 hover:bg-gray-50 transition-all sm:flex-row sm:items-center sm:justify-between">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <p class="font-bold text-gray-900 text-sm">{{ $course->title }}</p>
                        @if($course->is_group)
                        <span class="text-[11px] px-2 py-0.5 rounded-full font-bold bg-amber-50 text-amber-800 border border-amber-200/60">
                            Groupe · {{ $course->max_students }} max
                        </span>
                        @endif
                        @if($course->status === 'pending')
                        <span class="text-[11px] px-2 py-0.5 rounded-full font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">En attente</span>
                        @elseif($course->status === 'approved')
                        <span class="text-[11px] px-2 py-0.5 rounded-full font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">Approuvé</span>
                        @elseif($course->status === 'rejected')
                        <span class="text-[11px] px-2 py-0.5 rounded-full font-semibold bg-red-50 text-red-700 border border-red-200/60">Refusé</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500">{{ $course->category }} · {{ $course->level }} · {{ $course->formatted_format }}</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <div class="text-left sm:text-right">
                        <p class="font-bold text-sm text-gray-900">{{ number_format($course->price_per_hour, 0, ',', ' ') }} <span class="text-xs font-normal text-gray-400">FCFA/h</span></p>
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold {{ $course->is_active ? 'text-emerald-700' : 'text-gray-400' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $course->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                            {{ $course->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>

                    <div class="flex items-center gap-1.5 ml-2">
                        <a href="{{ route('professeur.edit-cours', $course->id) }}"
                           title="Modifier le cours"
                           class="p-2 rounded-xl border border-gray-200 text-gray-600 hover:text-black hover:border-yellow-400 hover:bg-yellow-50 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('professeur.delete-cours', $course->id) }}"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    title="Supprimer le cours"
                                    class="p-2 rounded-xl border border-gray-200 text-gray-400 hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

{{-- Avis reçus --}}
<p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Évaluations des apprenants</p>
<div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-100" style="box-shadow:0 4px 20px rgba(0,0,0,0.03);">

    @if($avisParCours->isEmpty())
    <div class="text-center py-12 px-4">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-3 bg-gray-50 border border-gray-100">
            <svg class="w-7 h-7 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
            </svg>
        </div>
        <p class="font-bold text-gray-900 text-sm mb-1">Aucun avis pour le moment</p>
        <p class="text-xs text-gray-400 max-w-sm mx-auto">Les avis et notes laissés par vos élèves s'afficheront ici au fur et à mesure des séances terminées.</p>
    </div>
    @else

    {{-- Tabs par cours --}}
    <div class="flex gap-2 mb-6 flex-wrap">
        @foreach($avisParCours as $courseId => $avis)
        @php $cours = $avis->first()->course; @endphp
        <button onclick="showAvis('cours-{{ $courseId }}')"
            id="tab-avis-{{ $courseId }}"
            class="avis-tab px-4 py-2 rounded-xl text-xs font-bold transition-all"
            style="{{ $loop->first ? 'background:#FCB315; color:#000;' : 'background:#f3f4f6; color:#666;' }}">
            {{ $cours->title }}
            <span class="ml-1 opacity-70">({{ $avis->count() }})</span>
        </button>
        @endforeach
    </div>

    {{-- Contenu avis par cours --}}
    @foreach($avisParCours as $courseId => $avis)
    @php
        $cours        = $avis->first()->course;
        $moyenneAvis  = round($avis->avg('rating'), 1);
        $totalAvis    = $avis->count();
    @endphp
    <div id="cours-{{ $courseId }}" class="avis-content {{ $loop->first ? '' : 'hidden' }}">

        {{-- En-tête cours --}}
        <div class="flex flex-col gap-3 mb-5 p-4 rounded-2xl bg-gray-50/80 border border-gray-100 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="font-bold text-gray-900 text-sm">{{ $cours->title }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $cours->category }} · {{ $totalAvis }} avis récolté(s)</p>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex gap-0.5">
                    @for($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4" fill="{{ $i <= round($moyenneAvis) ? '#FCB315' : '#E5E7EB' }}" viewBox="0 0 24 24">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                    @endfor
                </div>
                <span class="font-extrabold text-gray-900 text-sm">{{ $moyenneAvis }}</span>
                <span class="text-xs text-gray-400">/ 5</span>
            </div>
        </div>

        {{-- Liste avis --}}
        <div id="avis-list-{{ $courseId }}" class="space-y-3">
            @foreach($avis as $index => $review)
            <div class="avis-item-{{ $courseId }} {{ $index >= 3 ? 'hidden' : '' }}">
                <div class="flex flex-col gap-3 p-4 rounded-2xl border border-gray-100 bg-white hover:bg-gray-50/50 transition sm:flex-row sm:items-start sm:gap-4">
                    <x-avatar :user="$review->user" size="10" rounded="full"/>
                    <div class="flex-1">
                        <div class="flex flex-col gap-1 mb-1 sm:flex-row sm:items-center sm:justify-between">
                            <p class="font-bold text-gray-900 text-sm">{{ $review->user->name }}</p>
                            <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 mb-2">
                            <div class="flex gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                <svg class="w-3.5 h-3.5" fill="{{ $i <= $review->rating ? '#FCB315' : '#E5E7EB' }}" viewBox="0 0 24 24">
                                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                </svg>
                                @endfor
                            </div>
                            <span class="text-xs font-semibold text-gray-700">{{ $review->rating }}/5</span>
                        </div>
                        @if($review->comment)
                        <p class="text-xs text-gray-600 leading-relaxed">{{ $review->comment }}</p>
                        @else
                        <p class="text-xs text-gray-400 italic">Aucun commentaire écrit.</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination avis --}}
        @if($totalAvis > 3)
        <div class="flex flex-col gap-3 mt-5 pt-4 border-t border-gray-100 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-xs text-gray-400">
                <span id="avis-showing-{{ $courseId }}">1–3</span> sur {{ $totalAvis }} avis
            </p>
            <div class="flex items-center gap-2">
                <button onclick="prevAvis('{{ $courseId }}', {{ $totalAvis }})"
                    id="prev-{{ $courseId }}"
                    disabled
                    class="w-8 h-8 rounded-xl flex items-center justify-center transition bg-gray-100 hover:bg-gray-200 disabled:opacity-30">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <span class="text-xs font-semibold text-gray-600" id="page-info-{{ $courseId }}">
                    Page 1 / {{ ceil($totalAvis / 3) }}
                </span>
                <button onclick="nextAvis('{{ $courseId }}', {{ $totalAvis }})"
                    id="next-{{ $courseId }}"
                    class="w-8 h-8 rounded-xl flex items-center justify-center transition bg-gray-100 hover:bg-gray-200 disabled:opacity-30">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
        @endif

    </div>
    @endforeach

    @endif
</div>

@endsection

@push('scripts')
<script>
// Tabs avis
function showAvis(id) {
    document.querySelectorAll('.avis-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.avis-tab').forEach(btn => {
        btn.style.background = '#f3f4f6';
        btn.style.color = '#666';
    });
    document.getElementById(id).classList.remove('hidden');
    const tabId = 'tab-avis-' + id.replace('cours-', '');
    document.getElementById(tabId).style.background = '#FCB315';
    document.getElementById(tabId).style.color = '#000';
}

// Pagination avis
const avisPages = {};
const PER_PAGE  = 3;

function goToAvisPage(courseId, page, total) {
    const totalPages = Math.ceil(total / PER_PAGE);
    avisPages[courseId] = Math.max(1, Math.min(page, totalPages));
    const current = avisPages[courseId];
    const start   = (current - 1) * PER_PAGE;
    const end     = start + PER_PAGE;

    document.querySelectorAll('.avis-item-' + courseId).forEach((item, index) => {
        item.classList.toggle('hidden', index < start || index >= end);
    });

    const showingStart = start + 1;
    const showingEnd   = Math.min(end, total);

    const showingEl = document.getElementById('avis-showing-' + courseId);
    const pageInfoEl = document.getElementById('page-info-' + courseId);
    const prevBtn   = document.getElementById('prev-' + courseId);
    const nextBtn   = document.getElementById('next-' + courseId);

    if (showingEl)  showingEl.textContent  = showingStart + '–' + showingEnd;
    if (pageInfoEl) pageInfoEl.textContent = 'Page ' + current + ' / ' + totalPages;
    if (prevBtn)    prevBtn.disabled = current <= 1;
    if (nextBtn)    nextBtn.disabled = current >= totalPages;
}

function nextAvis(courseId, total) {
    if (!avisPages[courseId]) avisPages[courseId] = 1;
    goToAvisPage(courseId, avisPages[courseId] + 1, total);
}

function prevAvis(courseId, total) {
    if (!avisPages[courseId]) avisPages[courseId] = 1;
    goToAvisPage(courseId, avisPages[courseId] - 1, total);
}
</script>
@endpush
