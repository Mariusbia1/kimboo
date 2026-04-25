@extends('layouts.app')

@section('title', 'Cours' . ($categorie ? ' — ' . $categorie : ''))

@section('content')

<!-- Header -->
<section class="py-12 px-4" style="background:#F7F7F7;">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-black mb-2" style="font-family:'Plus Jakarta Sans',sans-serif;">
            {{ $categorie ? 'Cours de ' . $categorie : 'Tous les professeurs' }}
        </h1>
        <p class="text-sm mb-6" style="color:#2b2b2b;">{{ $professeurs->total() }} professeur(s) disponible(s)</p>


        <!-- Barre de recherche -->
        <form action="{{ route('cours.index') }}" method="GET" class="flex gap-3 mb-6 max-w-2xl">
            <div class="flex items-center gap-2 flex-1 bg-white rounded-lg px-4 py-2.5 border-2 border-gray-100 focus-within:border-yellow-400 transition">
                <svg class="w-4 h-4 shrink-0" style="color:#2b2b2b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
                <input type="text" name="q" value="{{ $q }}"
                    placeholder="Rechercher un prof, une matière..."
                    class="w-full outline-none text-sm bg-transparent"
                    style="color:#2b2b2b;"/>
                @if($categorie)
                <input type="hidden" name="categorie" value="{{ $categorie }}"/>
                @endif
            </div>
            <button type="submit"
                class="px-6 py-2.5 rounded-lg text-black font-semibold text-sm transition hover:opacity-90 shrink-0"
                style="background:#FCB315;">
                Rechercher
            </button>
            @if($q || $categorie)
            <a href="{{ route('cours.index') }}"
               class="px-4 flex items-center py-2.5 rounded-lg text-sm font-medium border-2 border-gray-200 hover:border-gray-300 transition shrink-0"
               style="color:#2b2b2b;">
                Reinitialiser
            </a>
            @endif
        </form>
        <!-- Filtres catégories -->
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('cours.index') }}"
               class="text-sm px-4 py-2 rounded-lg border transition font-medium
               {{ !$categorie ? 'text-black border-transparent' : 'border-gray-200 bg-white hover:border-yellow-400' }}"
               style="{{ !$categorie ? 'background:#FCB315;' : '' }}">
                Tous
            </a>
            @foreach(['Mathématiques', 'Cuisine', 'Sport', 'Langues', 'Musique', 'Informatique'] as $cat)
            <a href="{{ route('cours.index', ['categorie' => $cat]) }}"
               class="text-sm px-4 py-2 rounded-lg border transition font-medium
               {{ $categorie === $cat ? 'text-black border-transparent' : 'border-gray-200 bg-white hover:border-yellow-400' }}"
               style="{{ $categorie === $cat ? 'background:#FCB315;' : '' }}">
                {{ $cat }}
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Liste professeurs -->
<section class="py-12 px-4 bg-white">
    <div class="max-w-6xl mx-auto">
        @php
            $favorisIds = auth()->check()
                ? \App\Models\Favorite::where('user_id', auth()->id())->pluck('teacher_profile_id')->toArray()
                : [];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($professeurs as $profile)
            <a href="{{ route('professeur.profil', $profile->id) }}" class="block group">
                <div class="relative mb-3">
                    <!-- Photo cliquable -->
                    <div class="w-full aspect-square rounded-xl overflow-hidden bg-gray-100">
                        @if($profile->user->avatar)
                        <img src="{{ Storage::url($profile->user->avatar) }}"
                             alt="{{ $profile->user->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300"/>
                        @else
                        <div class="w-full h-full flex items-center justify-center text-5xl font-bold text-black" style="background:#FCB315;">
                            {{ strtoupper(substr($profile->user->name, 0, 1)) }}
                        </div>
                        @endif
                    </div>

                    <!-- Badge certifié haut à droite -->
                    @if($profile->is_verified)
                    <div class="absolute top-3 right-3 w-7 h-7 rounded-full flex items-center justify-center" style="background:#1877F2;">
                        <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414L8.414 15 3.293 9.879a1 1 0 011.414-1.414L8.414 12.172l6.879-6.879a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    @endif

                    <!-- Bouton like bas à droite -->
                    @auth
                    <button onclick="event.preventDefault(); toggleFavori({{ $profile->id }}, this)"
                        class="absolute bottom-3 right-3 w-8 h-8 rounded-full flex items-center justify-center transition hover:scale-110"
                        style="background:rgba(255,255,255,0.9);">
                        <svg class="w-4 h-4 favori-icon"
                             fill="{{ in_array($profile->id, $favorisIds) ? '#e53e3e' : 'none' }}"
                             stroke="{{ in_array($profile->id, $favorisIds) ? '#e53e3e' : '#666' }}"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </button>
                    @endauth
                </div>

                <!-- Infos -->
                <h3 class="font-semibold text-black text-base group-hover:underline">{{ $profile->user->name }}</h3>
                <p class="text-sm mb-1" style="color:#2b2b2b;">{{ $profile->courses->first()->category ?? 'Cours divers' }}</p>

                <!-- Note -->
                <div class="flex items-center gap-1 mb-2">
                    <span style="color:#FCB315;">★</span>
                    <span class="text-sm font-medium text-black">{{ $profile->rating }}</span>
                    <span class="text-sm" style="color:#2b2b2b;">({{ $profile->reviews_count }} avis)</span>
                </div>

                <!-- Bio -->
                <p class="text-sm leading-relaxed mb-2" style="color:#2b2b2b;">{{ Str::limit($profile->bio, 60) }}</p>

                <!-- Prix -->
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-black">{{ number_format($profile->hourly_rate, 0, ',', ' ') }} FCFA / H</span>
                    @if($profile->first_course_free)
                    <span class="text-sm font-medium" style="color:#FCB315;">1er cours offert</span>
                    @endif
                </div>
            </a>
            @empty
            <div class="col-span-4 text-center py-20">
                <p class="text-4xl mb-4">🔍</p>
                <p style="color:#2b2b2b;">Aucun professeur trouvé.</p>
                <a href="{{ route('cours.index') }}" class="inline-block mt-4 px-6 py-2.5 rounded-xl text-black text-sm font-semibold" style="background:#FCB315;">
                    Voir tous les professeurs
                </a>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($professeurs->hasPages())
        <div class="mt-10 flex justify-center">
            {{ $professeurs->links() }}
        </div>
        @endif
    </div>
</section>

@endsection
