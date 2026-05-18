@extends('layouts.dashboard')

@section('title', 'Mon espace')
@section('page-title', 'Mon espace')
@section('page-subtitle', 'Bienvenue, '.auth()->user()->name . ' !')

@section('content')

@if(session('success'))
<div class="mb-6 px-4 py-3 rounded-xl text-sm font-medium text-green-700 bg-green-100 flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
</div>
@endif

{{-- Stats --}}
<p class="text-sm font-semibold text-gray-400 uppercase tracking-widest mb-3">Aperçu</p>
<div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:1rem;" class="mb-8">

    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm text-gray-400">Réservations</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#FCB31520;">
                <svg class="w-4 h-4" style="color:#FCB315;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">{{ $reservationsCount }}</p>
        <p class="text-sm text-gray-400 mt-1">au total</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm text-gray-400">Cours terminés</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#10b98120;">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">{{ $coursesTermines }}</p>
        <p class="text-sm text-gray-400 mt-1">complétés</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm text-gray-400">Cours confirmés</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#6366f120;">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">{{ $coursesConfirmes }}</p>
        <p class="text-sm text-gray-400 mt-1">à venir</p>
    </div>

</div>

{{-- Recherche rapide --}}
<div class="bg-white rounded-2xl p-6 mb-8" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
    <h2 class="font-semibold text-black mb-4 flex items-center gap-2">
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
        </svg>
        Trouver un cours
    </h2>
    <form action="{{ route('cours.index') }}" method="GET" class="flex gap-3">
        <input type="text" name="q" placeholder="Maths, cuisine, anglais..."
            class="flex-1 border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50"/>
        <button type="submit"
            class="px-6 py-2.5 rounded-xl text-black text-sm font-semibold transition hover:opacity-90"
            style="background:#FCB315;">
            Rechercher
        </button>
    </form>
</div>

{{-- Prochains cours --}}
<div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-semibold text-black flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Prochains cours
        </h2>
        <a href="{{ route('eleve.mes-cours') }}"
           class="text-sm font-medium hover:underline"
           style="color:#FCB315;">
            Voir tous mes cours
        </a>
    </div>

    @if($prochainsCoours->isEmpty())
    <div class="text-center py-10">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3 bg-gray-100">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <p class="font-semibold text-black mb-1">Aucun cours à venir</p>
        <p class="text-gray-400 text-sm mb-4">Vous n'avez pas encore de cours confirmé.</p>
        <a href="{{ route('cours.index') }}"
           class="inline-block px-6 py-2.5 rounded-xl text-black text-sm font-semibold transition hover:opacity-90"
           style="background:#FCB315;">
            Trouver un professeur
        </a>
    </div>
    @else
    <div class="space-y-3">
        @foreach($prochainsCoours as $booking)
        <div class="flex items-center gap-4 p-4 rounded-xl bg-gray-50 border border-gray-100">
            <x-avatar :user="$booking->course->teacherProfile->user" size="12" rounded="full"/>
            <div class="flex-1">
                <p class="font-semibold text-black text-sm">{{ $booking->course->title }}</p>
                <p class="text-sm text-gray-500">avec {{ $booking->course->teacherProfile->user->name }}</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-black text-sm">
                    {{ \Carbon\Carbon::parse($booking->scheduled_at)->format('d/m/Y') }}
                </p>
                <p class="text-sm text-gray-500">
                    {{ \Carbon\Carbon::parse($booking->scheduled_at)->format('H:i') }}
                </p>
            </div>
            <div class="text-right shrink-0">
                <p class="font-bold text-sm" style="color:#FCB315;">
                    {{ number_format($booking->total_price, 0, ',', ' ') }} FCFA
                </p>
                <span class="text-sm px-2 py-0.5 rounded-full bg-green-100 text-green-700">Confirmé</span>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@endsection
