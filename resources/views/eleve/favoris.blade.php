@extends('layouts.dashboard')

@section('title', 'Mes favoris')
@section('page-title', 'Mes favoris')
@section('page-subtitle', 'Professeurs sauvegardés')

@section('content')

@if($favoris->isEmpty())
<div class="bg-white rounded-2xl p-10 text-center" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
    <p class="text-4xl mb-4">❤️</p>
    <p class="font-semibold text-black mb-2">Aucun favori pour le moment</p>
    <p class="text-sm mb-6" style="color:#2b2b2b;">Likez des profils de professeurs pour les retrouver ici</p>
    <a href="{{ route('cours.index') }}"
       class="inline-block px-6 py-2.5 rounded-xl text-black font-semibold text-sm transition hover:opacity-90"
       style="background:#FCB315;">
        Trouver un professeur
    </a>
</div>
@else
<div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:1.5rem;">
    @foreach($favoris as $profile)
    <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
        <!-- Photo -->
        <div class="relative">
            <a href="{{ route('professeur.profil', $profile->id) }}">
                <div class="w-full h-48 bg-gray-100">
                    @if($profile->user->avatar)
                    <img src="{{ Storage::url($profile->user->avatar) }}"
                         alt="{{ $profile->user->name }}"
                         class="w-full h-full object-cover"/>
                    @else
                    <div class="w-full h-full flex items-center justify-center text-5xl font-bold text-black" style="background:#FCB315;">
                        {{ strtoupper(substr($profile->user->name, 0, 1)) }}
                    </div>
                    @endif
                </div>
            </a>

            @if($profile->is_verified)
            <div class="absolute top-3 right-3 w-7 h-7 rounded-full flex items-center justify-center" style="background:#1877F2;">
                <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414L8.414 15 3.293 9.879a1 1 0 011.414-1.414L8.414 12.172l6.879-6.879a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </div>
            @endif

            <!-- Bouton retirer des favoris -->
            <button onclick="toggleFavori({{ $profile->id }}, this); this.closest('.bg-white').remove();"
                class="absolute bottom-3 right-3 w-8 h-8 rounded-full flex items-center justify-center"
                style="background:rgba(255,255,255,0.9);">
                <svg class="w-4 h-4 favori-icon" fill="#e53e3e" stroke="#e53e3e" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </button>
        </div>

        <!-- Infos -->
        <div class="p-4">
            <a href="{{ route('professeur.profil', $profile->id) }}" class="hover:underline">
                <h3 class="font-semibold text-black">{{ $profile->user->name }}</h3>
            </a>
            <p class="text-xs mb-2" style="color:#2b2b2b;">{{ $profile->courses->first()->category ?? 'Cours divers' }}</p>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-1">
                    <span style="color:#FCB315;">★</span>
                    <span class="text-sm font-medium text-black">{{ $profile->rating }}</span>
                </div>
                <span class="font-bold text-sm text-black">{{ number_format($profile->hourly_rate, 0, ',', ' ') }} FCFA / H</span>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection
