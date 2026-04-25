@extends('layouts.dashboard')

@section('title', 'Administration')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Vue d\'ensemble de la plateforme Kimboo')

@section('content')

@if(session('success'))
<div class="mb-6 px-4 py-3 rounded-xl text-sm font-medium text-green-700 bg-green-100 flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
</div>
@endif

{{-- Stats globales --}}
<p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Vue globale</p>
<div style="display:grid; grid-template-columns: repeat(5, 1fr); gap:1rem;" class="mb-8">

    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-400">Utilisateurs</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#FCB31520;">
                <svg class="w-4 h-4" style="color:#FCB315;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4a4 4 0 11-8 0 4 4 0 018 0zm6 4a2 2 0 100-4 2 2 0 000 4zM3 16a2 2 0 100-4 2 2 0 000 4z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">{{ $totalUsers }}</p>
        <p class="text-xs text-gray-400 mt-1">comptes inscrits</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-400">Professeurs</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#1A2B3C20;">
                <svg class="w-4 h-4" style="color:#1A2B3C;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">{{ $totalProfesseurs }}</p>
        <p class="text-xs text-gray-400 mt-1">enseignants actifs</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-400">Élèves</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#6366f120;">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">{{ $totalEleves }}</p>
        <p class="text-xs text-gray-400 mt-1">apprenants inscrits</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-400">Cours</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#10b98120;">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">{{ $totalCours }}</p>
        <p class="text-xs text-gray-400 mt-1">cours disponibles</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-400">Réservations</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#f59e0b20;">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">{{ $totalReservations }}</p>
        <p class="text-xs text-gray-400 mt-1">réservations totales</p>
    </div>

</div>

{{-- Liens rapides --}}
<p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Actions rapides</p>
<div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:1rem;" class="mb-8">

    <a href="{{ route('admin.users') }}"
       class="bg-white rounded-2xl p-5 flex items-center gap-4 hover:shadow-lg transition"
       style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:#FCB315;">
            <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4a4 4 0 11-8 0 4 4 0 018 0zm6 4a2 2 0 100-4 2 2 0 000 4zM3 16a2 2 0 100-4 2 2 0 000 4z"/>
            </svg>
        </div>
        <div>
            <p class="font-semibold text-black text-sm">Gérer les utilisateurs</p>
            <p class="text-xs text-gray-400">Voir, supprimer des comptes</p>
        </div>
    </a>

    <a href="{{ route('admin.stats') }}"
       class="bg-white rounded-2xl p-5 flex items-center gap-4 hover:shadow-lg transition"
       style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:#1A2B3C;">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
        </div>
        <div>
            <p class="font-semibold text-black text-sm">Statistiques</p>
            <p class="text-xs text-gray-400">Trafic, conversions, KPIs</p>
        </div>
    </a>

    <div class="bg-white rounded-2xl p-5 flex items-center gap-4"
         style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 bg-green-500">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="font-semibold text-black text-sm">Certifications</p>
            <p class="text-xs text-gray-400">Gérer depuis le tableau ci-dessous</p>
        </div>
    </div>

</div>

{{-- Tableau professeurs --}}
<div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="font-semibold text-black">Professeurs inscrits</h2>
            <p class="text-xs text-gray-400 mt-0.5">{{ $totalProfesseurs }} professeurs au total</p>
        </div>
        <a href="{{ route('admin.users') }}"
           class="text-sm font-semibold px-4 py-2 rounded-xl text-black transition hover:opacity-90 flex items-center gap-2"
           style="background:#FCB315;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4a4 4 0 11-8 0 4 4 0 018 0zm6 4a2 2 0 100-4 2 2 0 000 4zM3 16a2 2 0 100-4 2 2 0 000 4z"/>
            </svg>
            Tous les utilisateurs
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-xs uppercase tracking-wide">Professeur</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-xs uppercase tracking-wide">Ville</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-xs uppercase tracking-wide">Note</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-xs uppercase tracking-wide">Tarif</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-xs uppercase tracking-wide">Statut</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-xs uppercase tracking-wide">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($professeurs as $profile)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <x-avatar :user="$profile->user" size="9" rounded="full"/>
                            <div>
                                <p class="font-medium text-black">{{ $profile->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $profile->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-1 text-gray-600">
                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $profile->user->ville ?? '—' }}
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" style="color:#FCB315;" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                            </svg>
                            <span class="font-medium text-black">{{ $profile->rating }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-4 font-semibold text-black">
                        {{ number_format($profile->hourly_rate, 0, ',', ' ') }} Fcfa/h
                    </td>
                    <td class="py-3 px-4">
                        @if($profile->is_verified)
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 flex items-center gap-1 w-fit">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Certifié
                        </span>
                        @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 flex items-center gap-1 w-fit">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            En attente
                        </span>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        @if(!$profile->is_verified)
                        <form method="POST" action="{{ route('admin.certifier', $profile->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                class="text-xs px-3 py-1.5 rounded-lg text-white font-medium flex items-center gap-1.5 transition hover:opacity-90"
                                style="background:#1A2B3C;">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Certifier
                            </button>
                        </form>
                        @else
                        <form method="POST" action="{{ route('admin.decertifier', $profile->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                class="text-xs px-3 py-1.5 rounded-lg text-white font-medium flex items-center gap-1.5 transition hover:opacity-90 bg-red-500">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Retirer
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
