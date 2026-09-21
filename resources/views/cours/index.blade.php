@extends('layouts.app')

@section('title', 'Cours' . ($categorie ? ' — ' . $categorie : ''))

@section('content')

<!-- Header -->
<section class="py-10 px-4" style="background:#F7F7F7;">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-black mb-2" style="font-family:'Plus Jakarta Sans',sans-serif;">
            {{ $categorie ? 'Cours de ' . $categorie : 'Tous les professeurs' }}
        </h1>
        <p class="text-sm mb-6" style="color:#2b2b2b;">{{ $professeurs->total() }} professeur(s) disponible(s)</p>

        <!-- Formulaire de recherche et filtres personnalisés bien arrondis -->
        <form action="{{ route('cours.index') }}" method="GET" id="search-filter-form" class="bg-white rounded-3xl p-5 sm:p-6 border border-gray-100 shadow-sm mb-6">
            <div class="flex flex-col md:flex-row gap-3 mb-5">
                <div class="flex items-center gap-3 flex-1 bg-gray-50/90 rounded-full px-6 py-3.5 border border-gray-200/90 focus-within:border-[#FCB315] focus-within:bg-white focus-within:ring-2 focus-within:ring-[#FCB315]/20 transition shadow-inner">
                    <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                    <input type="text" name="q" value="{{ $q }}"
                        placeholder="Rechercher un prof, une matière, une ville, un niveau..."
                        class="w-full outline-none text-sm bg-transparent text-gray-800 placeholder-gray-400 font-medium"/>
                    @if($categorie)
                    <input type="hidden" name="categorie" value="{{ $categorie }}"/>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="px-8 py-3.5 rounded-full text-black font-bold text-sm transition hover:opacity-90 shrink-0 shadow-sm flex items-center justify-center gap-2 hover:scale-[1.02] active:scale-[0.98]"
                        style="background:#FCB315;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                        </svg>
                        <span>Rechercher</span>
                    </button>
                    @if($q || $categorie || $typeCours || ($distance && (int)$distance < 50) || ($tarif && (float)$tarif < 70000))
                    <a href="{{ route('cours.index') }}"
                       class="px-5 py-3.5 rounded-full text-sm font-semibold border border-gray-300 hover:bg-gray-100 transition shrink-0 text-gray-700">
                        Réinitialiser
                    </a>
                    @endif
                </div>
            </div>

            <!-- Critères personnalisés (Type de cours, Distance, Tarif) -->
            <div class="pt-4 border-t border-gray-100 grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Type de cours -->
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-2">Type de cours</label>
                    <div class="flex flex-wrap gap-2">
                        <label class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full border text-xs font-semibold cursor-pointer transition {{ !$typeCours ? 'bg-[#FFF8E7] border-[#FCB315] text-black shadow-2xs font-bold' : 'bg-gray-50 border-gray-200 text-gray-700 hover:border-gray-300' }}">
                            <input type="radio" name="type_cours" value="" {{ !$typeCours ? 'checked' : '' }} onchange="this.form.submit()" class="hidden">
                            <span>Tous</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full border text-xs font-semibold cursor-pointer transition {{ $typeCours === 'autour_de_moi' ? 'bg-[#FFF8E7] border-[#FCB315] text-black shadow-2xs font-bold' : 'bg-gray-50 border-gray-200 text-gray-700 hover:border-gray-300' }}">
                            <input type="radio" name="type_cours" value="autour_de_moi" {{ $typeCours === 'autour_de_moi' ? 'checked' : '' }} onchange="this.form.submit()" class="hidden">
                            <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Autour de moi</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full border text-xs font-semibold cursor-pointer transition {{ $typeCours === 'face_a_face' ? 'bg-[#FFF8E7] border-[#FCB315] text-black shadow-2xs font-bold' : 'bg-gray-50 border-gray-200 text-gray-700 hover:border-gray-300' }}">
                            <input type="radio" name="type_cours" value="face_a_face" {{ $typeCours === 'face_a_face' ? 'checked' : '' }} onchange="this.form.submit()" class="hidden">
                            <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4a4 4 0 11-8 0 4 4 0 018 0zM23 16a2 2 0 100-4 2 2 0 000 4zM3 16a2 2 0 100-4 2 2 0 000 4z"/></svg>
                            <span>Face à face</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full border text-xs font-semibold cursor-pointer transition {{ $typeCours === 'en_ligne' ? 'bg-[#FFF8E7] border-[#FCB315] text-black shadow-2xs font-bold' : 'bg-gray-50 border-gray-200 text-gray-700 hover:border-gray-300' }}">
                            <input type="radio" name="type_cours" value="en_ligne" {{ $typeCours === 'en_ligne' ? 'checked' : '' }} onchange="this.form.submit()" class="hidden">
                            <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>En ligne</span>
                        </label>
                    </div>
                </div>

                <!-- Distance -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Distance max</label>
                        <span class="text-xs font-bold text-black" id="dist-display">
                            {{ ($distance && (int)$distance < 50) ? $distance . ' km' : '50 km (Toutes)' }}
                        </span>
                    </div>
                    <input type="range" name="distance" min="1" max="50" value="{{ $distance ?? 50 }}"
                        oninput="document.getElementById('dist-display').innerText = this.value >= 50 ? '50 km (Toutes)' : this.value + ' km'"
                        onchange="this.form.submit()"
                        class="w-full accent-[#FCB315] cursor-pointer">
                    <div class="flex justify-between text-[11px] text-gray-400 mt-1 font-medium">
                        <span>1 km</span>
                        <span>50 km (Toutes)</span>
                    </div>
                </div>

                <!-- Tarif -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Tarif max horaire</label>
                        <span class="text-xs font-bold text-black" id="tarif-display">
                            {{ ($tarif && (float)$tarif < 70000) ? number_format($tarif, 0, ',', ' ') . ' FCFA max' : '70 000+ FCFA (Tous)' }}
                        </span>
                    </div>
                    <input type="range" name="tarif" min="2000" max="70000" step="1000" value="{{ $tarif ?? 70000 }}"
                        oninput="document.getElementById('tarif-display').innerText = this.value >= 70000 ? '70 000+ FCFA (Tous)' : Number(this.value).toLocaleString('fr-FR') + ' FCFA max'"
                        onchange="this.form.submit()"
                        class="w-full accent-[#FCB315] cursor-pointer">
                    <div class="flex justify-between text-[11px] text-gray-400 mt-1 font-medium">
                        <span>2 000 FCFA</span>
                        <span>70 000+ FCFA</span>
                    </div>
                </div>

            </div>
        </form>

        <!-- Filtres actifs (Pills avec suppression rapide) -->
        @php
            $hasActiveFilters = $q || $categorie || $typeCours || ($distance && (int)$distance < 50) || ($tarif && (float)$tarif < 70000);
        @endphp
        @if($hasActiveFilters)
        <div class="flex flex-wrap items-center gap-2 mb-4 bg-amber-50/50 p-3 rounded-2xl border border-amber-200/60">
            <span class="text-xs font-bold text-gray-600 mr-1 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-[#FCB315]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <span>Filtres actifs :</span>
            </span>

            @if($q)
            <a href="{{ route('cours.index', array_filter(['categorie' => $categorie, 'type_cours' => $typeCours, 'distance' => ($distance < 50 ? $distance : null), 'tarif' => ($tarif < 70000 ? $tarif : null)])) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white border border-gray-200 text-gray-800 hover:border-red-300 hover:text-red-600 transition shadow-2xs group">
                <span>Recherche: <strong>{{ $q }}</strong></span>
                <svg class="w-3 h-3 text-gray-400 group-hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
            @endif

            @if($categorie)
            <a href="{{ route('cours.index', array_filter(['q' => $q, 'type_cours' => $typeCours, 'distance' => ($distance < 50 ? $distance : null), 'tarif' => ($tarif < 70000 ? $tarif : null)])) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white border border-gray-200 text-gray-800 hover:border-red-300 hover:text-red-600 transition shadow-2xs group">
                <span>Matière: <strong>{{ $categorie }}</strong></span>
                <svg class="w-3 h-3 text-gray-400 group-hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
            @endif

            @if($typeCours)
            <a href="{{ route('cours.index', array_filter(['q' => $q, 'categorie' => $categorie, 'distance' => ($distance < 50 ? $distance : null), 'tarif' => ($tarif < 70000 ? $tarif : null)])) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white border border-gray-200 text-gray-800 hover:border-red-300 hover:text-red-600 transition shadow-2xs group">
                <span>Type: <strong>{{ $typeCours === 'en_ligne' ? 'En ligne' : ($typeCours === 'face_a_face' ? 'Face à face' : 'Autour de moi') }}</strong></span>
                <svg class="w-3 h-3 text-gray-400 group-hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
            @endif

            @if($distance && (int)$distance < 50)
            <a href="{{ route('cours.index', array_filter(['q' => $q, 'categorie' => $categorie, 'type_cours' => $typeCours, 'tarif' => ($tarif < 70000 ? $tarif : null)])) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white border border-gray-200 text-gray-800 hover:border-red-300 hover:text-red-600 transition shadow-2xs group">
                <span>Distance max: <strong>{{ $distance }} km</strong></span>
                <svg class="w-3 h-3 text-gray-400 group-hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
            @endif

            @if($tarif && (float)$tarif < 70000)
            <a href="{{ route('cours.index', array_filter(['q' => $q, 'categorie' => $categorie, 'type_cours' => $typeCours, 'distance' => ($distance < 50 ? $distance : null)])) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white border border-gray-200 text-gray-800 hover:border-red-300 hover:text-red-600 transition shadow-2xs group">
                <span>Tarif max: <strong>{{ number_format($tarif, 0, ',', ' ') }} FCFA</strong></span>
                <svg class="w-3 h-3 text-gray-400 group-hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
            @endif

            <a href="{{ route('cours.index') }}" class="text-xs font-bold text-amber-900 underline hover:text-black ml-auto">
                Tout effacer
            </a>
        </div>
        @endif

        <!-- Filtres catégories -->
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('cours.index', array_filter(['q' => $q, 'type_cours' => $typeCours, 'distance' => ($distance < 50 ? $distance : null), 'tarif' => ($tarif < 70000 ? $tarif : null)])) }}"
               class="text-sm px-5 py-2 rounded-full border transition font-medium
               {{ !$categorie ? 'text-black border-transparent shadow-xs' : 'border-gray-200 bg-white hover:border-yellow-400' }}"
               style="{{ !$categorie ? 'background:#FCB315;' : '' }}">Tous</a>
            @foreach($categories as $cat)
            <a href="{{ route('cours.index', array_filter(['categorie' => $cat, 'q' => $q, 'type_cours' => $typeCours, 'distance' => ($distance < 50 ? $distance : null), 'tarif' => ($tarif < 70000 ? $tarif : null)])) }}"
               class="text-sm px-5 py-2 rounded-full border transition font-medium
               {{ $categorie === $cat ? 'text-black border-transparent shadow-xs' : 'border-gray-200 bg-white hover:border-yellow-400' }}"
               style="{{ $categorie === $cat ? 'background:#FCB315;' : '' }}">{{ $cat }}</a>
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

        <!-- Grille de cartes professeurs compactes et professionnelles -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($professeurs as $profile)
            <div class="bg-white rounded-3xl p-4 border border-gray-100 hover:border-amber-200/90 hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 flex flex-col justify-between group">
                <div>
                    <!-- Photo cliquable compacte et bien proportionnée -->
                    <div class="relative mb-3.5">
                        <a href="{{ route('professeur.profil', $profile->id) }}" class="block w-full aspect-[4/3] rounded-2xl overflow-hidden bg-gray-100 shadow-xs relative">
                            <x-avatar :user="$profile->user" full="true" rounded="2xl" />
                        </a>

                        <!-- Badge en vedette haut à gauche -->
                        @if($profile->is_featured)
                        <div class="absolute top-2.5 left-2.5 z-10">
                            <x-featured-badge />
                        </div>
                        @endif

                        <!-- Badge certifié haut à droite -->
                        @if($profile->is_verified)
                        <div class="absolute top-2.5 right-2.5 z-10">
                            <x-verified-badge />
                        </div>
                        @endif

                        <!-- Bouton like bas à droite -->
                        @auth
                        <button onclick="event.preventDefault(); toggleFavori({{ $profile->id }}, this)"
                            class="absolute bottom-2.5 right-2.5 w-8 h-8 rounded-full flex items-center justify-center transition hover:scale-110 shadow-sm z-10"
                            style="background:rgba(255,255,255,0.95);">
                            <svg class="w-3.5 h-3.5 favori-icon"
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
                    @php
                        $displayCourse = ($categorie ? $profile->courses->firstWhere('category', $categorie) : null) ?? $profile->courses->first();
                    @endphp
                    <div class="flex items-center justify-between gap-2 mb-1.5">
                        <a href="{{ route('professeur.profil', $profile->id) }}" class="font-bold text-gray-900 text-base group-hover:text-amber-700 transition truncate">
                            {{ $profile->user->name }}
                        </a>
                        <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-900 border border-amber-200/60 shrink-0">
                            {{ $displayCourse?->category ?? 'Général' }}
                        </span>
                    </div>

                    @if($displayCourse && $displayCourse->title)
                    <p class="text-xs font-semibold text-gray-700 line-clamp-1 mb-1.5">{{ $displayCourse->title }}</p>
                    @endif

                    <!-- Note & avis -->
                    <div class="flex items-center gap-1.5 mb-2">
                        <svg class="w-4 h-4 text-[#FCB315] fill-current shrink-0" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="text-xs font-extrabold text-gray-900">{{ number_format($profile->rating, 1) }}</span>
                        <span class="text-[11px] text-gray-400 font-medium">({{ $profile->reviews_count }} avis)</span>
                    </div>

                    <!-- Bio concise -->
                    <p class="text-xs text-gray-500 leading-relaxed mb-3 line-clamp-2">
                        {{ $profile->bio ? Str::limit($profile->bio, 85) : 'Professeur passionné et certifié sur Kimboo.' }}
                    </p>
                </div>

                <!-- Prix & 1er cours -->
                <div class="pt-3 border-t border-gray-100/90 flex items-center justify-between">
                    <div class="flex items-baseline gap-1">
                        <span class="text-sm font-black text-gray-900">{{ number_format($profile->hourly_rate, 0, ',', ' ') }}</span>
                        <span class="text-[11px] font-semibold text-gray-400">FCFA/h</span>
                    </div>
                    @if($profile->first_course_free)
                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-md bg-[#FFF8E7] text-[#9A6A00] border border-[#FCB315]/40">
                        1er cours offert
                    </span>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-1 sm:col-span-2 lg:col-span-3 text-center py-16">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gray-100 flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                </div>
                <p class="text-base font-semibold text-gray-800">Aucun professeur trouvé</p>
                <p class="text-sm text-gray-500 mt-1">Essayez d'ajuster vos filtres de recherche</p>
                <a href="{{ route('cours.index') }}" class="inline-block mt-4 px-6 py-2.5 rounded-full text-black text-sm font-semibold transition hover:opacity-90" style="background:#FCB315;">
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
