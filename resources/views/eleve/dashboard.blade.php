@extends('layouts.dashboard')

@section('title', 'Mon espace')
@section('page-title', 'Mon espace')
@section('page-subtitle', 'Bienvenue, ' . auth()->user()->name . ' !')

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
<p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Aperçu</p>
<div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:1rem;" class="mb-8">

    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-400">Cours réservés</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#FCB31520;">
                <svg class="w-4 h-4" style="color:#FCB315;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">{{ $reservationsCount }}</p>
        <p class="text-xs text-gray-400 mt-1">réservations au total</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-400">Cours terminés</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#10b98120;">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">{{ $coursesTermines }}</p>
        <p class="text-xs text-gray-400 mt-1">cours complétés</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs text-gray-400">Cours confirmés</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#6366f120;">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">{{ $coursesConfirmes }}</p>
        <p class="text-xs text-gray-400 mt-1">à venir</p>
    </div>

</div>

{{-- Recherche rapide --}}
<div class="bg-white rounded-2xl p-6 mb-8" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
    <h2 class="font-semibold text-black mb-4 flex items-center gap-2">
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
        </svg>
        Rechercher un cours
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

{{-- Mes réservations --}}
<div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="font-semibold text-black">Mes réservations</h2>
            <p class="text-xs text-gray-400 mt-0.5">{{ $reservations->count() }} réservation(s) au total</p>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
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
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-xs uppercase tracking-wide">Professeur</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-xs uppercase tracking-wide">Cours</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-xs uppercase tracking-wide">Date</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-xs uppercase tracking-wide">Durée</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-xs uppercase tracking-wide">Montant</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-xs uppercase tracking-wide">Statut</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-xs uppercase tracking-wide">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservations as $booking)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">

                    {{-- Professeur --}}
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <x-avatar :user="$booking->course->teacherProfile->user" size="8" rounded="full"/>
                            <a href="{{ route('professeur.profil', $booking->course->teacherProfile->id) }}"
                               class="font-medium text-black hover:underline">
                                {{ $booking->course->teacherProfile->user->name }}
                            </a>
                        </div>
                    </td>

                    {{-- Cours --}}
                    <td class="py-3 px-4 text-gray-600">{{ $booking->course->title }}</td>

                    {{-- Date --}}
                    <td class="py-3 px-4 text-gray-600">
                        {{ \Carbon\Carbon::parse($booking->scheduled_at)->format('d/m/Y à H:i') }}
                    </td>

                    {{-- Durée --}}
                    <td class="py-3 px-4 text-gray-600">{{ $booking->duration_hours }}h</td>

                    {{-- Montant --}}
                    <td class="py-3 px-4 font-semibold text-black">
                        {{ number_format($booking->total_price, 0, ',', ' ') }} Fcfa
                    </td>

                    {{-- Statut --}}
                    <td class="py-3 px-4">
                        @if($booking->status === 'en_attente')
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 flex items-center gap-1 w-fit">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            En attente
                        </span>
                        @elseif($booking->status === 'confirmé')
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 flex items-center gap-1 w-fit">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Confirmé
                        </span>
                        @elseif($booking->status === 'annulé')
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 flex items-center gap-1 w-fit">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Annulé
                        </span>
                        @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 flex items-center gap-1 w-fit">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Terminé
                        </span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-2">

                            {{-- Annuler si en attente --}}
                            @if($booking->status === 'en_attente')
                            <form method="POST" action="{{ route('eleve.booking.cancel', $booking->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="text-xs px-3 py-1.5 rounded-lg text-white font-medium bg-red-500 flex items-center gap-1 transition hover:opacity-90">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Annuler
                                </button>
                            </form>

                            {{-- Marquer comme terminé si confirmé --}}
                            @elseif($booking->status === 'confirmé')
                            <button onclick="ouvrirModalTerminer({{ $booking->id }}, '{{ addslashes($booking->course->title) }}')"
                                class="text-xs px-3 py-1.5 rounded-lg text-white font-medium flex items-center gap-1 transition hover:opacity-90"
                                style="background:#1A2B3C;">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Terminer
                            </button>

                            {{-- Laisser un avis si terminé et pas encore noté --}}
                            @elseif($booking->status === 'terminé' && !$booking->reviewed)
                            <button onclick="ouvrirModalAvis({{ $booking->id }}, {{ $booking->course->id }}, '{{ addslashes($booking->course->title) }}')"
                                class="text-xs px-3 py-1.5 rounded-lg font-medium flex items-center gap-1 transition hover:opacity-90"
                                style="background:#FCB315; color:#000;">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                Laisser un avis
                            </button>

                            @elseif($booking->status === 'terminé' && $booking->reviewed)
                            <span class="text-xs text-gray-400 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" style="color:#FCB315;" viewBox="0 0 24 24">
                                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                </svg>
                                Avis laissé
                            </span>

                            @else
                            <span class="text-xs text-gray-300">—</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- Modal : Marquer comme terminé --}}
<div id="modal-terminer" class="fixed inset-0 z-50 hidden flex items-center justify-center" style="background:rgba(0,0,0,0.5);">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4" style="box-shadow:0 20px 60px rgba(0,0,0,0.3);">
        <h3 class="font-bold text-black text-lg mb-2">Confirmer la fin du cours</h3>
        <p class="text-sm text-gray-500 mb-6">Vous êtes sur le point de marquer le cours <strong id="modal-terminer-titre"></strong> comme terminé. Cette action est définitive.</p>
        <form method="POST" id="form-terminer">
            @csrf @method('PATCH')
            <div class="flex gap-3">
                <button type="submit"
                    class="flex-1 py-2.5 rounded-xl text-white font-semibold text-sm transition hover:opacity-90"
                    style="background:#1A2B3C;">
                    Confirmer
                </button>
                <button type="button" onclick="fermerModalTerminer()"
                    class="flex-1 py-2.5 rounded-xl font-semibold text-sm transition hover:bg-gray-100 text-gray-600 border border-gray-200">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal : Laisser un avis --}}
<div id="modal-avis" class="fixed inset-0 z-50 hidden flex items-center justify-center" style="background:rgba(0,0,0,0.5);">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4" style="box-shadow:0 20px 60px rgba(0,0,0,0.3);">
        <h3 class="font-bold text-black text-lg mb-1">Laisser un avis</h3>
        <p class="text-sm text-gray-500 mb-5">Votre avis sur <strong id="modal-avis-titre"></strong></p>

        <form method="POST" id="form-avis">
            @csrf

            {{-- Note étoiles --}}
            <label class="block text-xs font-medium text-gray-600 mb-2">Note</label>
            <div class="flex gap-2 mb-4" id="stars-container">
                @for($i = 1; $i <= 5; $i++)
                <button type="button" onclick="setRating({{ $i }})"
                    class="star-btn text-3xl transition hover:scale-110"
                    data-value="{{ $i }}"
                    style="color:#E5E7EB;">
                    ★
                </button>
                @endfor
            </div>
            <input type="hidden" name="rating" id="rating-input" value="0"/>

            {{-- Commentaire --}}
            <label class="block text-xs font-medium text-gray-600 mb-2">Commentaire (optionnel)</label>
            <textarea name="comment" rows="3"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition resize-none"
                placeholder="Partagez votre expérience..."></textarea>

            <div class="flex gap-3 mt-4">
                <button type="submit"
                    class="flex-1 py-2.5 rounded-xl text-black font-semibold text-sm transition hover:opacity-90"
                    style="background:#FCB315;">
                    Publier l'avis
                </button>
                <button type="button" onclick="fermerModalAvis()"
                    class="flex-1 py-2.5 rounded-xl font-semibold text-sm transition hover:bg-gray-100 text-gray-600 border border-gray-200">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Modal terminer
function ouvrirModalTerminer(bookingId, titre) {
    document.getElementById('modal-terminer-titre').textContent = titre;
    document.getElementById('form-terminer').action = '/eleve/reservations/' + bookingId + '/terminer';
    document.getElementById('modal-terminer').classList.remove('hidden');
}
function fermerModalTerminer() {
    document.getElementById('modal-terminer').classList.add('hidden');
}

// Modal avis
function ouvrirModalAvis(bookingId, courseId, titre) {
    document.getElementById('modal-avis-titre').textContent = titre;
    document.getElementById('form-avis').action = '/eleve/reservations/' + bookingId + '/avis';
    document.getElementById('modal-avis').classList.remove('hidden');
}
function fermerModalAvis() {
    document.getElementById('modal-avis').classList.add('hidden');
}

// Étoiles
function setRating(value) {
    document.getElementById('rating-input').value = value;
    document.querySelectorAll('.star-btn').forEach(btn => {
        btn.style.color = parseInt(btn.dataset.value) <= value ? '#FCB315' : '#E5E7EB';
    });
}

// Fermer modals en cliquant dehors
document.getElementById('modal-terminer').addEventListener('click', function(e) {
    if (e.target === this) fermerModalTerminer();
});
document.getElementById('modal-avis').addEventListener('click', function(e) {
    if (e.target === this) fermerModalAvis();
});
</script>
@endpush
