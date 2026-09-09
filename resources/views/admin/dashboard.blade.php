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
<p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Vue d'ensemble de l'activité</p>
<div class="grid grid-cols-1 gap-4 mb-8 sm:grid-cols-2 xl:grid-cols-5">

    <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-gray-200 transition-all duration-200" style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Utilisateurs</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-amber-50 text-[#FCB315]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4a4 4 0 11-8 0 4 4 0 018 0zm6 4a2 2 0 100-4 2 2 0 000 4zM3 16a2 2 0 100-4 2 2 0 000 4z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-black" style="font-family:'Poppins',sans-serif;">{{ $totalUsers }}</p>
        <p class="text-xs text-gray-400 mt-1">comptes enregistrés</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-gray-200 transition-all duration-200" style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Professeurs</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-slate-100 text-slate-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-black" style="font-family:'Poppins',sans-serif;">{{ $totalProfesseurs }}</p>
        <p class="text-xs text-gray-400 mt-1">enseignants inscrits</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-gray-200 transition-all duration-200" style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Élèves</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-indigo-50 text-indigo-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-black" style="font-family:'Poppins',sans-serif;">{{ $totalEleves }}</p>
        <p class="text-xs text-gray-400 mt-1">apprenants actifs</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-gray-200 transition-all duration-200" style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Cours</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-emerald-50 text-emerald-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-black" style="font-family:'Poppins',sans-serif;">{{ $totalCours }}</p>
        <p class="text-xs text-gray-400 mt-1">cours au catalogue</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-gray-200 transition-all duration-200" style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Réservations</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-amber-50 text-amber-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-black" style="font-family:'Poppins',sans-serif;">{{ $totalReservations }}</p>
        <p class="text-xs text-gray-400 mt-1">séances au total</p>
    </div>

</div>

{{-- Liens rapides --}}
<p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Raccourcis d'administration</p>
<div class="grid grid-cols-1 gap-4 mb-8 md:grid-cols-3">

    <a href="{{ route('admin.users') }}"
       class="bg-white rounded-2xl p-5 flex items-center gap-4 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 border border-gray-100 group"
       style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 shadow-sm transition-transform duration-200 group-hover:scale-105" style="background:#FCB315;">
            <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4a4 4 0 11-8 0 4 4 0 018 0zm6 4a2 2 0 100-4 2 2 0 000 4zM3 16a2 2 0 100-4 2 2 0 000 4z"/>
            </svg>
        </div>
        <div>
            <p class="font-bold text-gray-900 text-sm group-hover:text-black">Gérer les utilisateurs</p>
            <p class="text-xs text-gray-400 mt-0.5">Consulter, certifier ou modérer les comptes</p>
        </div>
    </a>

    <a href="{{ route('admin.stats') }}"
       class="bg-white rounded-2xl p-5 flex items-center gap-4 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 border border-gray-100 group"
       style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 shadow-sm transition-transform duration-200 group-hover:scale-105" style="background:#0B0F19;">
            <svg class="w-5 h-5 text-[#FCB315]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
        </div>
        <div>
            <p class="font-bold text-gray-900 text-sm group-hover:text-black">Statistiques & Trafic</p>
            <p class="text-xs text-gray-400 mt-0.5">Audience, conversions et pages visitées</p>
        </div>
    </a>

    <a href="{{ route('admin.cours') }}"
       class="bg-white rounded-2xl p-5 flex items-center gap-4 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 border border-gray-100 group"
       style="box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 shadow-sm bg-emerald-500 transition-transform duration-200 group-hover:scale-105">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="font-bold text-gray-900 text-sm group-hover:text-black">Modération des cours</p>
            <p class="text-xs text-gray-400 mt-0.5">Approuver ou refuser les nouveaux cours</p>
        </div>
    </a>

</div>

{{-- Tableau professeurs --}}
<div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-100" style="box-shadow:0 4px 20px rgba(0,0,0,0.03);">
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-lg font-bold text-gray-900">Professeurs inscrits</h2>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/60">
                    {{ $totalProfesseurs }} au total
                </span>
            </div>
            <p class="text-xs text-gray-400 mt-1">Dernières inscriptions et certifications de professeurs</p>
        </div>
        <a href="{{ route('admin.users') }}"
           class="inline-flex items-center gap-2 text-xs font-bold px-4 py-2.5 rounded-xl text-black transition-all duration-200 hover:shadow-md hover:opacity-95 self-start sm:self-auto"
           style="background:#FCB315;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4a4 4 0 11-8 0 4 4 0 018 0zm6 4a2 2 0 100-4 2 2 0 000 4zM3 16a2 2 0 100-4 2 2 0 000 4z"/>
            </svg>
            <span>Gérer tous les utilisateurs</span>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[780px] text-sm">
            <thead>
                <tr class="border-b border-gray-100 text-left text-xs font-bold uppercase tracking-wider text-gray-400">
                    <th class="py-3 px-4">Professeur</th>
                    <th class="py-3 px-4">Ville</th>
                    <th class="py-3 px-4">Note</th>
                    <th class="py-3 px-4">Tarif</th>
                    <th class="py-3 px-4">Statut</th>
                    <th class="py-3 px-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($professeurs as $profile)
                @php
                    $userForModal = clone $profile->user;
                    $userForModal->setRelation('teacherProfile', $profile);
                @endphp
                <tr onclick='openAdminUserModal(@json($userForModal))'
                    class="hover:bg-amber-50/40 transition-colors duration-150 cursor-pointer group">
                    <td class="py-3.5 px-4">
                        <div class="flex items-center gap-3">
                            <x-avatar :user="$profile->user" size="10" rounded="full"/>
                            <div>
                                <p class="font-bold text-gray-900 leading-tight group-hover:text-amber-900 transition-colors flex items-center gap-1.5">
                                    <span>{{ $profile->user->name }}</span>
                                    <svg class="w-3.5 h-3.5 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $profile->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-3.5 px-4">
                        <div class="flex items-center gap-1.5 text-xs text-gray-600 font-medium">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $profile->user->ville ?? '—' }}
                        </div>
                    </td>
                    <td class="py-3.5 px-4">
                        <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-amber-50 text-xs font-bold text-gray-900 border border-amber-200/50">
                            <svg class="w-3.5 h-3.5 fill-[#FCB315] text-[#FCB315]" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                            </svg>
                            <span>{{ $profile->rating }}</span>
                        </div>
                    </td>
                    <td class="py-3.5 px-4 font-bold text-gray-900 text-sm">
                        {{ number_format($profile->hourly_rate, 0, ',', ' ') }} <span class="text-xs font-normal text-gray-400">Fcfa/h</span>
                    </td>
                    <td class="py-3.5 px-4">
                        <div class="flex flex-wrap items-center gap-1.5">
                            @if($profile->is_verified)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                <x-verified-badge :size="13" />
                                <span>Certifié</span>
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>En attente</span>
                            </span>
                            @endif

                            @if($profile->is_featured)
                            <x-featured-badge />
                            @endif
                        </div>
                    </td>
                    <td class="py-3.5 px-4 text-right">
                        <div class="flex items-center justify-end gap-2" onclick="event.stopPropagation();">
                            <button type="button"
                                onclick='openAdminUserModal(@json($userForModal));'
                                class="text-xs font-bold px-2.5 py-1.5 rounded-xl text-black bg-[#FCB315] hover:opacity-90 transition-all inline-flex items-center gap-1 shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>Détails</span>
                            </button>

                            @if(!$profile->is_verified)
                            <form method="POST" action="{{ route('admin.certifier', $profile->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="text-xs px-2.5 py-1.5 rounded-xl text-white font-semibold flex items-center gap-1 transition-all hover:opacity-90 shadow-sm"
                                    style="background:#0B0F19;">
                                    <svg class="w-3.5 h-3.5 text-[#FCB315]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Certifier</span>
                                </button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('admin.decertifier', $profile->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="text-xs px-2.5 py-1.5 rounded-xl text-red-600 bg-red-50 hover:bg-red-100 font-semibold flex items-center gap-1 transition-colors border border-red-200/60">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span>Décertifier</span>
                                </button>
                            </form>
                            @endif

                            @if(!$profile->is_featured)
                            <form method="POST" action="{{ route('admin.feature', $profile->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="text-xs px-2 py-1.5 rounded-xl text-black font-semibold flex items-center gap-1 transition-all hover:opacity-90 shadow-sm"
                                    style="background:#FCB315;" title="Mettre en avant sur la page d'accueil">
                                    <svg class="w-3.5 h-3.5 fill-black" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('admin.unfeature', $profile->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="text-xs px-2 py-1.5 rounded-xl text-gray-700 bg-gray-100 hover:bg-gray-200 font-semibold flex items-center gap-1 transition-colors border border-gray-200/60"
                                    title="Retirer de la page d'accueil">
                                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Détails Utilisateur --}}
<x-admin-user-modal />

@endsection
