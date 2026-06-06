@extends('layouts.dashboard')
@section('title', 'Mes favoris')
@section('page-title', 'Mes favoris')
@section('page-subtitle', 'Professeurs sauvegardés')
@section('content')

@if($favoris->isEmpty())
<div class="bg-white rounded-2xl p-12 text-center" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
    <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:#FFF8E7;">
        <svg class="w-8 h-8" style="color:#FCB315;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
    </div>
    <p class="font-bold text-black text-lg mb-2">Aucun favori pour le moment</p>
    <p class="text-sm mb-6 text-gray-400">Likez des profils de professeurs pour les retrouver ici</p>
    <a href="{{ route('cours.index') }}"
       class="inline-block px-6 py-2.5 rounded-xl text-black font-semibold text-sm transition hover:opacity-90"
       style="background:#FCB315;">
        Trouver un professeur
    </a>
</div>

@else
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($favoris as $profile)
    <div class="bg-white rounded-2xl overflow-hidden transition hover:-translate-y-1 duration-200" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">

        <!-- Photo -->
        <div class="relative">
            <a href="{{ route('professeur.profil', $profile->id) }}">
                <div class="w-full h-48 bg-gray-100">
                    @if($profile->user->avatar)
                    <img src="{{ Storage::url($profile->user->avatar) }}"
                         alt="{{ $profile->user->name }}"
                         class="w-full h-full object-cover hover:scale-105 transition duration-300"/>
                    @else
                    <div class="w-full h-full flex items-center justify-center text-5xl font-bold text-black" style="background:#FCB315;">
                        {{ strtoupper(substr($profile->user->name, 0, 1)) }}
                    </div>
                    @endif
                </div>
            </a>

            <!-- Badge certifié -->
            @if($profile->is_verified)
            <div class="absolute top-3 left-3">
                <x-verified-badge />
            </div>
            @endif

            <!-- Bouton retirer -->
            <button onclick="retirerFavori({{ $profile->id }}, this)"
                class="absolute top-3 right-3 w-8 h-8 rounded-full flex items-center justify-center transition hover:scale-110"
                style="background:rgba(255,255,255,0.95); box-shadow:0 2px 8px rgba(0,0,0,0.15);"
                title="Retirer des favoris">
                <svg class="w-4 h-4" fill="#e53e3e" stroke="#e53e3e" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </button>
        </div>

        <!-- Infos -->
        <div class="p-5">
            <div class="flex items-start justify-between mb-1">
                <a href="{{ route('professeur.profil', $profile->id) }}" class="hover:underline">
                    <h3 class="font-bold text-black">{{ $profile->user->name }}</h3>
                </a>
                @if($profile->first_course_free)
                <span class="text-xs font-semibold shrink-0 ml-2 px-2 py-0.5 rounded-full" style="background:#FFF8E7; color:#FCB315;">
                    1er cours offert
                </span>
                @endif
            </div>

            <p class="text-sm text-gray-400 mb-3">{{ $profile->courses->first()->category ?? 'Cours divers' }}</p>

            <!-- Note + ville -->
            <div class="flex items-center gap-3 mb-4">
                <div class="flex items-center gap-1">
                    <span style="color:#FCB315; font-size:14px;">★</span>
                    <span class="text-sm font-semibold text-black">{{ $profile->rating }}</span>
                    <span class="text-xs text-gray-400">({{ $profile->reviews_count }} avis)</span>
                </div>
                @if($profile->user->ville)
                <span class="text-xs text-gray-400">· {{ $profile->user->ville }}</span>
                @endif
            </div>

            <!-- Prix + CTA -->
            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                <span class="font-bold text-black">{{ number_format($profile->hourly_rate, 0, ',', ' ') }} FCFA/H</span>
                <a href="{{ route('professeur.profil', $profile->id) }}"
                   class="px-4 py-2 rounded-xl text-black text-sm font-semibold transition hover:opacity-90"
                   style="background:#FCB315;">
                    Voir le profil
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

<script>
async function retirerFavori(profileId, btn) {
    try {
        const response = await fetch(`/favoris/${profileId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            }
        });
        const data = await response.json();
        if (!data.liked) {
            const card = btn.closest('.bg-white');
            card.style.transition = 'opacity 0.3s, transform 0.3s';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.95)';
            setTimeout(() => card.remove(), 300);
        }
    } catch(e) {
        console.error(e);
    }
}
</script>

@endsection
