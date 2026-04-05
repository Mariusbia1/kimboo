@extends('layouts.dashboard')

@section('title', 'Administration')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Gérez la plateforme Kimboo')

@section('content')

@if(session('success'))
<div class="mb-6 px-4 py-3 rounded-xl text-sm font-medium text-green-700 bg-green-100">
    ✓ {{ session('success') }}
</div>
@endif

<!-- Stats globales -->
<div style="display:grid; grid-template-columns: repeat(5, 1fr); gap:1rem;" class="mb-8">
    <div class="bg-white rounded-2xl p-5 text-center" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <p class="text-3xl font-bold" style="color:#FCB315; font-family:'Poppins',sans-serif;">{{ $totalUsers }}</p>
        <p class="text-sm text-gray-500 mt-1">Utilisateurs</p>
    </div>
    <div class="bg-white rounded-2xl p-5 text-center" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <p class="text-3xl font-bold" style="color:#FCB315; font-family:'Poppins',sans-serif;">{{ $totalProfesseurs }}</p>
        <p class="text-sm text-gray-500 mt-1">Professeurs</p>
    </div>
    <div class="bg-white rounded-2xl p-5 text-center" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <p class="text-3xl font-bold" style="color:#FCB315; font-family:'Poppins',sans-serif;">{{ $totalEleves }}</p>
        <p class="text-sm text-gray-500 mt-1">Élèves</p>
    </div>
    <div class="bg-white rounded-2xl p-5 text-center" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <p class="text-3xl font-bold" style="color:#FCB315; font-family:'Poppins',sans-serif;">{{ $totalCours }}</p>
        <p class="text-sm text-gray-500 mt-1">Cours</p>
    </div>
    <div class="bg-white rounded-2xl p-5 text-center" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <p class="text-3xl font-bold" style="color:#FCB315; font-family:'Poppins',sans-serif;">{{ $totalReservations }}</p>
        <p class="text-sm text-gray-500 mt-1">Réservations</p>
    </div>
</div>

<!-- Professeurs -->
<div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-semibold text-black">Professeurs inscrits</h2>
        <a href="{{ route('admin.users') }}"
           class="text-sm font-semibold px-4 py-2 rounded-xl text-black transition hover:opacity-90"
           style="background:#FCB315;">
            Voir tous les utilisateurs
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Professeur</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Ville</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Note</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Tarif</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Statut</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($professeurs as $profile)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-black shrink-0" style="background:#FCB315;">
                                {{ strtoupper(substr($profile->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-black">{{ $profile->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $profile->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-gray-600">{{ $profile->user->ville }}</td>
                    <td class="py-3 px-4">
                        <span style="color:#FCB315;">★</span>
                        {{ $profile->rating }}
                    </td>
                    <td class="py-3 px-4 font-medium text-black">
                        {{ number_format($profile->hourly_rate, 0, ',', ' ') }} Fcfa/h
                    </td>
                    <td class="py-3 px-4">
                        @if($profile->is_verified)
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Certifié</span>
                        @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">En attente</span>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        @if(!$profile->is_verified)
                        <form method="POST" action="{{ route('admin.certifier', $profile->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                class="text-xs px-3 py-1.5 rounded-lg text-white font-medium"
                                style="background:#1A2B3C;">
                                Certifier
                            </button>
                        </form>
                        @else
                        <form method="POST" action="{{ route('admin.decertifier', $profile->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                class="text-xs px-3 py-1.5 rounded-lg text-white font-medium bg-red-500">
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
