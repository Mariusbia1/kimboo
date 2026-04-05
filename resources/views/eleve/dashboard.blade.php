@extends('layouts.dashboard')

@section('title', 'Mon espace')
@section('page-title', 'Mon espace')
@section('page-subtitle', 'Que souhaitez-vous apprendre aujourd\'hui ?')

@section('content')

@if(session('success'))
<div class="mb-6 px-4 py-3 rounded-xl text-sm font-medium text-green-700 bg-green-100">
    ✓ {{ session('success') }}
</div>
@endif

<!-- Stats -->
<div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:1rem;" class="mb-8">
    <div class="bg-white rounded-2xl p-5 text-center" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <p class="text-3xl font-bold" style="color:#FCB315; font-family:'Poppins',sans-serif;">{{ $reservationsCount }}</p>
        <p class="text-sm text-gray-500 mt-1">Cours réservés</p>
    </div>
    <div class="bg-white rounded-2xl p-5 text-center" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <p class="text-3xl font-bold" style="color:#FCB315; font-family:'Poppins',sans-serif;">{{ $coursesTermines }}</p>
        <p class="text-sm text-gray-500 mt-1">Cours terminés</p>
    </div>
    <div class="bg-white rounded-2xl p-5 text-center" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <p class="text-3xl font-bold" style="color:#FCB315; font-family:'Poppins',sans-serif;">{{ $coursesConfirmes }}</p>
        <p class="text-sm text-gray-500 mt-1">Cours confirmés</p>
    </div>
</div>

<!-- Recherche rapide -->
<div class="bg-white rounded-2xl p-6 mb-8" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
    <h2 class="font-semibold text-black mb-4">Rechercher un cours</h2>
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

<!-- Mes réservations -->
<div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-semibold text-black">Mes réservations</h2>
        <a href="{{ route('cours.index') }}"
           class="text-sm font-semibold px-4 py-2 rounded-xl text-black transition hover:opacity-90"
           style="background:#FCB315;">
            + Trouver un prof
        </a>
    </div>

    @if($reservations->isEmpty())
    <div class="text-center py-16">
        <p class="text-4xl mb-4">📚</p>
        <p class="text-gray-400 text-sm">Vous n'avez pas encore réservé de cours.</p>
        <a href="{{ route('cours.index') }}"
           class="inline-block mt-4 px-6 py-2.5 rounded-xl text-black text-sm font-semibold transition hover:opacity-90"
           style="background:#FCB315;">
            Trouver un professeur
        </a>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Professeur</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Cours</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Date</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Durée</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Montant</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Statut</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservations as $booking)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-black shrink-0" style="background:#FCB315;">
                                {{ strtoupper(substr($booking->course->teacherProfile->user->name, 0, 1)) }}
                            </div>
                            <span class="font-medium text-black">{{ $booking->course->teacherProfile->user->name }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-gray-600">{{ $booking->course->title }}</td>
                    <td class="py-3 px-4 text-gray-600">
                        {{ \Carbon\Carbon::parse($booking->scheduled_at)->format('d/m/Y à H:i') }}
                    </td>
                    <td class="py-3 px-4 text-gray-600">{{ $booking->duration_hours }}h</td>
                    <td class="py-3 px-4 font-semibold text-black">
                        {{ number_format($booking->total_price, 0, ',', ' ') }} Fcfa
                    </td>
                    <td class="py-3 px-4">
                        @if($booking->status === 'en_attente')
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">En attente</span>
                        @elseif($booking->status === 'confirmé')
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Confirmé</span>
                        @elseif($booking->status === 'annulé')
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">Annulé</span>
                        @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Terminé</span>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        @if($booking->status === 'en_attente')
                        <form method="POST" action="{{ route('eleve.booking.cancel', $booking->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs px-3 py-1.5 rounded-lg text-white font-medium bg-red-500">
                                Annuler
                            </button>
                        </form>
                        @else
                        <span class="text-xs text-gray-400">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection
