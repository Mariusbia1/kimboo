@extends('layouts.dashboard')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Bienvenue, ' . auth()->user()->name)


@section('content')
<div class="max-w-6xl px-4 py-10 mx-auto">

    <!-- Bonjour -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-black" style="font-family:'Poppins',sans-serif;">
                Bonjour, {{ $user->name }} 👋
            </h1>
            <p class="mt-1 text-sm text-gray-500">Gérez votre profil et vos cours</p>
        </div>
        @if($profile && $profile->is_verified)
        <div class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white rounded-full" style="background:#1A2B3C;">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414L8.414 15 3.293 9.879a1 1 0 011.414-1.414L8.414 12.172l6.879-6.879a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            Profil certifié
        </div>
        @else
        <div class="px-4 py-2 text-xs font-semibold text-gray-500 bg-gray-100 rounded-full">
            En attente de certification
        </div>
        @endif
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 gap-4 mb-8 sm:grid-cols-4">
        <div class="p-5 text-center bg-white rounded-2xl" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
            <p class="text-3xl font-bold" style="color:#FCB315; font-family:'Poppins',sans-serif;">{{ $courses->count() }}</p>
            <p class="mt-1 text-sm text-gray-500">Cours publiés</p>
        </div>
        <div class="bg-white rounded-2xl p-5 text-center" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
            <p class="text-3xl font-bold" style="color:#FCB315; font-family:'Poppins',sans-serif;">{{ $reservationsCount }}</p>
            <p class="text-sm text-gray-500 mt-1">Réservations</p>
        </div>
        <div class="p-5 text-center bg-white rounded-2xl" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
            <p class="text-3xl font-bold" style="color:#FCB315; font-family:'Poppins',sans-serif;">{{ $profile ? $profile->rating : 0 }}</p>
            <p class="mt-1 text-sm text-gray-500">Note moyenne</p>
        </div>
        <div class="p-5 text-center bg-white rounded-2xl" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
            <p class="text-3xl font-bold" style="color:#FCB315; font-family:'Poppins',sans-serif;">{{ $profile ? $profile->reviews_count : 0 }}</p>
            <p class="mt-1 text-sm text-gray-500">Avis reçus</p>
        </div>
    </div>

    <!-- Mes cours -->
    <div class="p-6 bg-white rounded-2xl" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-black">Mes cours</h2>
            <a href="{{ route('professeur.create-cours') }}" class="text-sm font-semibold text-white px-4 py-2 rounded-xl" style="background:#FCB315;">
                + Ajouter un cours
            </a>
        </div>

        @if($courses->isEmpty())
        <div class="py-10 text-center text-gray-400">
            <p class="mb-3 text-4xl">🎓</p>
            <p class="text-sm">Vous n'avez pas encore publié de cours.</p>
        </div>
        @else
        <div class="space-y-3">
            @foreach($courses as $course)
            <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl bg-gray-50">
                <div>
                    <p class="text-sm font-medium text-black">{{ $course->title }}</p>
                    <p class="text-xs text-gray-400">{{ $course->category }} · {{ $course->level }} · {{ $course->format }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-black">{{ number_format($course->price_per_hour, 0, ',', ' ') }} Fcfa/h</p>
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $course->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $course->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
