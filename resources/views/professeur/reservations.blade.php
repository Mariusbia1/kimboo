@extends('layouts.dashboard')

@section('title', 'Mes réservations')
@section('page-title', 'Mes réservations')
@section('page-subtitle', 'Gérez les demandes de cours')

@section('content')

@if(session('success'))
<div class="mb-6 px-4 py-3 rounded-xl text-sm font-medium text-green-700 bg-green-100">
    ✓ {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">

    @if($reservations->isEmpty())
    <div class="text-center py-16">
        <p class="text-4xl mb-4">📅</p>
        <p class="text-gray-400 text-sm">Aucune réservation pour le moment.</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Élève</th>
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
                    <td class="py-3 px-4 font-medium text-black">{{ $booking->user->name }}</td>
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
                        <span class="px-2.5 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-700">En attente</span>
                        @elseif($booking->status === 'confirmé')
                        <span class="px-2.5 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">Confirmé</span>
                        @elseif($booking->status === 'annulé')
                        <span class="px-2.5 py-1 rounded-full text-sm font-medium bg-red-100 text-red-700">Annulé</span>
                        @else
                        <span class="px-2.5 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">Terminé</span>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        @if($booking->status === 'en_attente')
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('professeur.booking.confirm', $booking->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-sm px-3 py-1.5 rounded-lg text-white font-medium" style="background:#1A2B3C;">
                                    Confirmer
                                </button>
                            </form>
                            <form method="POST" action="{{ route('professeur.booking.cancel', $booking->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-sm px-3 py-1.5 rounded-lg text-white font-medium bg-red-500">
                                    Annuler
                                </button>
                            </form>
                        </div>
                        @else
                        <span class="text-sm text-gray-400">—</span>
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
