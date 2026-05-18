@extends('layouts.dashboard')

@section('title', 'Mes réservations')
@section('page-title', 'Mes réservations')
@section('page-subtitle', 'Suivez l\'état de vos réservations')

@section('content')

@if(session('success'))
<div class="mb-6 px-4 py-3 rounded-xl text-sm font-medium text-green-700 bg-green-100 flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="font-semibold text-black">Toutes mes réservations</h2>
            <p class="text-sm text-gray-400 mt-0.5">{{ $reservations->total() }} réservation(s) au total</p>
        </div>
        <a href="{{ route('cours.index') }}"
           class="text-sm font-semibold px-4 py-2 rounded-xl text-black transition hover:opacity-90 flex items-center gap-2"
           style="background:#FCB315;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Trouver un prof
        </a>
    </div>

    @if($reservations->isEmpty())
    <div class="text-center py-16">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-gray-100">
            <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <p class="font-semibold text-black mb-1">Aucune réservation</p>
        <p class="text-gray-400 text-sm mb-4">Vous n'avez pas encore réservé de cours.</p>
        <a href="{{ route('cours.index') }}"
           class="inline-block px-6 py-2.5 rounded-xl text-black text-sm font-semibold transition hover:opacity-90"
           style="background:#FCB315;">
            Trouver un professeur
        </a>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-sm uppercase tracking-wide">Professeur</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-sm uppercase tracking-wide">Cours</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-sm uppercase tracking-wide">Date</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-sm uppercase tracking-wide">Durée</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-sm uppercase tracking-wide">Montant</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-sm uppercase tracking-wide">Statut</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-sm uppercase tracking-wide">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservations as $booking)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <x-avatar :user="$booking->course->teacherProfile->user" size="8" rounded="full"/>
                            <a href="{{ route('professeur.profil', $booking->course->teacherProfile->id) }}"
                               class="font-medium text-black hover:underline text-sm">
                                {{ $booking->course->teacherProfile->user->name }}
                            </a>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-gray-600 text-sm">{{ $booking->course->title }}</td>
                    <td class="py-3 px-4 text-gray-600 text-sm">
                        {{ \Carbon\Carbon::parse($booking->scheduled_at)->format('d/m/Y à H:i') }}
                    </td>
                    <td class="py-3 px-4 text-gray-600 text-sm">{{ $booking->duration_hours }}h</td>
                    <td class="py-3 px-4 font-semibold text-black text-sm">
                        {{ number_format($booking->total_price, 0, ',', ' ') }} FCFA
                    </td>
                    <td class="py-3 px-4">
                        @if($booking->status === 'en_attente')
                        <span class="px-2.5 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-700 flex items-center gap-1 w-fit">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            En attente
                        </span>
                        @elseif($booking->status === 'confirmé')
                        <span class="px-2.5 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-700 flex items-center gap-1 w-fit">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Confirmé
                        </span>
                        @elseif($booking->status === 'annulé')
                        <span class="px-2.5 py-1 rounded-full text-sm font-medium bg-red-100 text-red-700 flex items-center gap-1 w-fit">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Annulé
                        </span>
                        @else
                        <span class="px-2.5 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700 flex items-center gap-1 w-fit">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Terminé
                        </span>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        @if($booking->status === 'en_attente')
                        <form method="POST" action="{{ route('eleve.booking.cancel', $booking->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                class="text-sm px-3 py-1.5 rounded-lg text-white font-medium bg-red-500 flex items-center gap-1 transition hover:opacity-90">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Annuler
                            </button>
                        </form>
                        @elseif($booking->status === 'confirmé')
                        <button onclick="ouvrirModalTerminer({{ $booking->id }}, '{{ addslashes($booking->course->title) }}')"
                            class="text-sm px-3 py-1.5 rounded-lg text-white font-medium flex items-center gap-1 transition hover:opacity-90"
                            style="background:#1A2B3C;">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Terminer
                        </button>
                        @else
                        <span class="text-sm text-gray-300">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($reservations->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $reservations->links() }}
    </div>
    @endif
    @endif
</div>

{{-- Modal terminer --}}
<div id="modal-terminer" class="fixed inset-0 z-50 hidden flex items-center justify-center" style="background:rgba(0,0,0,0.5);">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4" style="box-shadow:0 20px 60px rgba(0,0,0,0.3);">
        <h3 class="font-bold text-black text-lg mb-2">Confirmer la fin du cours</h3>
        <p class="text-sm text-gray-500 mb-6">Vous êtes sur le point de marquer le cours <strong id="modal-terminer-titre"></strong> comme terminé.</p>
        <form method="POST" id="form-terminer">
            @csrf @method('PATCH')
            <div class="flex gap-3">
                <button type="submit" class="flex-1 py-2.5 rounded-xl text-white font-semibold text-sm transition hover:opacity-90" style="background:#1A2B3C;">
                    Confirmer
                </button>
                <button type="button" onclick="fermerModalTerminer()" class="flex-1 py-2.5 rounded-xl font-semibold text-sm transition hover:bg-gray-100 text-gray-600 border border-gray-200">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function ouvrirModalTerminer(bookingId, titre) {
    document.getElementById('modal-terminer-titre').textContent = titre;
    document.getElementById('form-terminer').action = '/eleve/reservations/' + bookingId + '/terminer';
    document.getElementById('modal-terminer').classList.remove('hidden');
}
function fermerModalTerminer() {
    document.getElementById('modal-terminer').classList.add('hidden');
}
document.getElementById('modal-terminer').addEventListener('click', function(e) {
    if (e.target === this) fermerModalTerminer();
});
</script>
@endpush
