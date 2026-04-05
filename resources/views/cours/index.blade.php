@extends('layouts.app')

@section('title', 'Cours' . ($categorie ? ' — ' . $categorie : ''))

@section('content')

<!-- Header -->
<section class="py-12 px-4" style="background:#F7F7F7;">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-black mb-2" style="font-family:'Poppins',sans-serif;">
            {{ $categorie ? 'Cours de ' . $categorie : 'Tous les professeurs' }}
        </h1>
        <p class="text-gray-400 text-sm">{{ $professeurs->total() }} professeur(s) disponible(s)</p>

        <!-- Filtres catégories -->
        <div class="flex flex-wrap gap-2 mt-6">
            <a href="{{ route('cours.index') }}"
               class="text-sm px-4 py-2 rounded-full border transition
               {{ !$categorie ? 'text-white border-transparent' : 'border-gray-200 bg-white text-gray-600 hover:border-yellow-400' }}"
               style="{{ !$categorie ? 'background:#FCB315;' : '' }}">
                Tous
            </a>
            @foreach(['Mathématiques', 'Cuisine', 'Sport', 'Langues', 'Musique', 'Informatique'] as $cat)
            <a href="{{ route('cours.index', ['categorie' => $cat]) }}"
               class="text-sm px-4 py-2 rounded-full border transition
               {{ $categorie === $cat ? 'text-white border-transparent' : 'border-gray-200 bg-white text-gray-600 hover:border-yellow-400' }}"
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
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($professeurs as $profile)
            <div class="bg-white rounded-2xl p-5 relative border border-gray-100 hover:border-yellow-300 transition" style="box-shadow: 0 4px 12px rgba(0,0,0,0.06);">

                @if($profile->is_verified)
                <div class="absolute top-4 right-4 w-7 h-7 rounded-full flex items-center justify-center" style="background:#1A2B3C;" title="Professeur certifié">
                    <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414L8.414 15 3.293 9.879a1 1 0 011.414-1.414L8.414 12.172l6.879-6.879a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                @endif

                <!-- Avatar -->
                <div class="mb-4">
                    <x-avatar :user="$profile->user" size="16" rounded="full"/>
                </div>

                <h3 class="font-semibold text-black text-base">{{ $profile->user->name }}</h3>
                <p class="text-xs text-gray-400 mb-1">{{ $profile->courses->first()->category ?? 'Cours divers' }}</p>

                <div class="flex items-center gap-1 mb-2">
                    <span style="color:#FCB315;">★</span>
                    <span class="text-sm font-medium text-black">{{ $profile->rating }}</span>
                    <span class="text-xs text-gray-400">({{ $profile->reviews_count }} avis)</span>
                </div>

                <p class="text-xs text-gray-500 mb-4 leading-relaxed">{{ Str::limit($profile->bio, 70) }}</p>

                <div class="flex items-center justify-between mb-4">
                    <div>
                        <span class="text-base font-bold text-black">{{ number_format($profile->hourly_rate, 0, ',', ' ') }} Fcfa</span>
                        <span class="text-xs text-gray-400">/h</span>
                    </div>
                    @if($profile->first_course_free)
                    <span class="text-xs font-medium" style="color:#FCB315;">1er cours offert</span>
                    @endif
                </div>

                <a href="{{ route('professeur.profil', $profile->id) }}"
                   class="block w-full text-center py-2.5 rounded-xl text-white text-sm font-semibold transition hover:opacity-90"
                   style="background:#FCB315;">
                    Voir le profil
                </a>
            </div>
            @empty
            <div class="col-span-4 text-center py-20">
                <p class="text-4xl mb-4">🔍</p>
                <p class="text-gray-400">Aucun professeur trouvé pour cette catégorie.</p>
                <a href="{{ route('cours.index') }}" class="inline-block mt-4 px-6 py-2.5 rounded-xl text-white text-sm font-semibold" style="background:#FCB315;">
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
