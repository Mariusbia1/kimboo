@extends('layouts.dashboard')

@section('title', 'Mes cours')
@section('page-title', 'Mes cours')
@section('page-subtitle', 'Tous les cours que vous avez suivis ou êtes en train de suivre')

@section('content')

@if(session('success'))
<div class="mb-6 px-4 py-3 rounded-xl text-sm font-medium text-green-700 bg-green-100 flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
</div>
@endif

@if($cours->isEmpty())
<div class="bg-white rounded-2xl p-16 text-center" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
    <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-gray-100">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
    </div>
    <p class="font-semibold text-black text-lg mb-2">Aucun cours pour le moment</p>
    <p class="text-gray-400 text-sm mb-6">Dès que vous aurez trouvé le bon professeur et réservé votre premier cours, il s'affichera ici.</p>
    <a href="{{ route('cours.index') }}"
       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-black font-semibold text-sm transition hover:opacity-90"
       style="background:#FCB315;">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
        </svg>
        Trouver le bon professeur
    </a>
</div>

@else

{{-- Grid de cours --}}
<div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:1.5rem;">
    @foreach($cours as $booking)
    @php
        $prof    = $booking->course->teacherProfile->user;
        $profile = $booking->course->teacherProfile;
    @endphp

    <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">

        {{-- Header card --}}
        <div class="p-5 border-b border-gray-50">
            <div class="flex items-center gap-3 mb-4">
                <x-avatar :user="$prof" size="12" rounded="full"/>
                <div class="flex-1 min-w-0">
                    <a href="{{ route('professeur.profil', $profile->id) }}"
                       class="font-semibold text-black text-sm hover:underline truncate block">
                        {{ $prof->name }}
                    </a>
                    <p class="text-sm text-gray-500 truncate">{{ $booking->course->category }}</p>
                </div>
                @if($profile->is_verified)
                <x-verified-badge :size="22" class="shrink-0" />
                @endif
            </div>

            <p class="font-semibold text-black text-sm mb-1">{{ $booking->course->title }}</p>
            <p class="text-sm text-gray-500">{{ $booking->course->level }} · {{ $booking->course->format }}</p>
        </div>

        {{-- Infos --}}
        <div class="p-5">
            <div class="space-y-2 mb-4">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-400 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Date
                    </span>
                    <span class="font-medium text-black">
                        {{ \Carbon\Carbon::parse($booking->scheduled_at)->format('d/m/Y à H:i') }}
                    </span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-400 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Durée
                    </span>
                    <span class="font-medium text-black">{{ $booking->duration_hours }}h</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-400 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Montant
                    </span>
                    <span class="font-bold" style="color:#FCB315;">
                        {{ number_format($booking->total_price, 0, ',', ' ') }} FCFA
                    </span>
                </div>
            </div>

            {{-- Statut + action --}}
            <div class="flex items-center justify-between">
                @if($booking->status === 'confirmé')
                <span class="px-2.5 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-700">Confirmé</span>
                <button onclick="ouvrirModalTerminer({{ $booking->id }}, '{{ addslashes($booking->course->title) }}')"
                    class="text-sm px-3 py-1.5 rounded-lg text-white font-medium flex items-center gap-1 transition hover:opacity-90"
                    style="background:#1A2B3C;">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Terminer
                </button>

                @elseif($booking->status === 'terminé' && !$booking->reviewed)
                <span class="px-2.5 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">Terminé</span>
                <button onclick="ouvrirModalAvis({{ $booking->id }}, {{ $booking->course->id }}, '{{ addslashes($booking->course->title) }}')"
                    class="text-sm px-3 py-1.5 rounded-lg font-medium flex items-center gap-1 transition hover:opacity-90"
                    style="background:#FCB315; color:#000;">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                    Laisser un avis
                </button>

                @elseif($booking->status === 'terminé' && $booking->reviewed)
                <span class="px-2.5 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">Terminé</span>
                <span class="text-sm text-gray-400 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" style="color:#FCB315;" viewBox="0 0 24 24">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                    Avis laissé
                </span>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Pagination --}}
@if($cours->hasPages())
<div class="mt-8 flex justify-center">
    {{ $cours->links() }}
</div>
@endif

@endif

{{-- Modal terminer --}}
<div id="modal-terminer" class="fixed inset-0 z-50 hidden flex items-center justify-center" style="background:rgba(0,0,0,0.5);">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4" style="box-shadow:0 20px 60px rgba(0,0,0,0.3);">
        <h3 class="font-bold text-black text-lg mb-2">Confirmer la fin du cours</h3>
        <p class="text-sm text-gray-500 mb-6">Marquer le cours <strong id="modal-terminer-titre"></strong> comme terminé ?</p>
        <form method="POST" id="form-terminer">
            @csrf @method('PATCH')
            <div class="flex gap-3">
                <button type="submit" class="flex-1 py-2.5 rounded-xl text-white font-semibold text-sm transition hover:opacity-90" style="background:#1A2B3C;">Confirmer</button>
                <button type="button" onclick="fermerModalTerminer()" class="flex-1 py-2.5 rounded-xl font-semibold text-sm transition hover:bg-gray-100 text-gray-600 border border-gray-200">Annuler</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal avis --}}
<div id="modal-avis" class="fixed inset-0 z-50 hidden flex items-center justify-center" style="background:rgba(0,0,0,0.5);">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4" style="box-shadow:0 20px 60px rgba(0,0,0,0.3);">
        <h3 class="font-bold text-black text-lg mb-1">Laisser un avis</h3>
        <p class="text-sm text-gray-500 mb-5">Votre avis sur <strong id="modal-avis-titre"></strong></p>
        <form method="POST" id="form-avis">
            @csrf
            <label class="block text-sm font-medium text-gray-600 mb-2">Note</label>
            <div class="flex gap-2 mb-4">
                @for($i = 1; $i <= 5; $i++)
                <button type="button" onclick="setRating({{ $i }})" class="star-btn text-3xl transition hover:scale-110" data-value="{{ $i }}" style="color:#E5E7EB;">★</button>
                @endfor
            </div>
            <input type="hidden" name="rating" id="rating-input" value="0"/>
            <label class="block text-sm font-medium text-gray-600 mb-2">Commentaire (optionnel)</label>
            <textarea name="comment" rows="3"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition resize-none"
                placeholder="Partagez votre expérience..."></textarea>
            <div class="flex gap-3 mt-4">
                <button type="submit" class="flex-1 py-2.5 rounded-xl text-black font-semibold text-sm transition hover:opacity-90" style="background:#FCB315;">Publier</button>
                <button type="button" onclick="fermerModalAvis()" class="flex-1 py-2.5 rounded-xl font-semibold text-sm transition hover:bg-gray-100 text-gray-600 border border-gray-200">Annuler</button>
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
function ouvrirModalAvis(bookingId, courseId, titre) {
    document.getElementById('modal-avis-titre').textContent = titre;
    document.getElementById('form-avis').action = '/eleve/reservations/' + bookingId + '/avis';
    document.getElementById('modal-avis').classList.remove('hidden');
}
function fermerModalAvis() {
    document.getElementById('modal-avis').classList.add('hidden');
}
function setRating(value) {
    document.getElementById('rating-input').value = value;
    document.querySelectorAll('.star-btn').forEach(btn => {
        btn.style.color = parseInt(btn.dataset.value) <= value ? '#FCB315' : '#E5E7EB';
    });
}
document.getElementById('modal-terminer').addEventListener('click', function(e) { if (e.target === this) fermerModalTerminer(); });
document.getElementById('modal-avis').addEventListener('click', function(e) { if (e.target === this) fermerModalAvis(); });
</script>
@endpush
