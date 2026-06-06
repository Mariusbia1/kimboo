@extends('layouts.app')

@section('title', 'Accueil')

@section('content')

<!-- HERO -->
<section class="w-full px-4 flex items-center justify-center relative"
style="min-height: calc(80vh); background: linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)), url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1600&q=80') center/cover no-repeat;">
    <div class="max-w-4xl mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4" style="font-family:'Plus Jakarta Sans',sans-serif; line-height:1.2;">
    Que voulez-vous <span class="text-white">apprendre</span> aujourd'hui ?
</h1>
<p class="text-lg mb-10" style="color:rgba(255,255,255,0.85);">
    Trouvez un professeur qualifié près de chez vous en quelques secondes.
</p>
        <!-- Barre de recherche -->
        <form action="{{ route('cours.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3 bg-white rounded-xl p-3 max-w-2xl mx-auto" style="box-shadow: 0 8px 30px rgba(0,0,0,0.15);">
            <div class="flex items-center gap-2 flex-1 w-full">
                <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
                <input
                    type="text"
                    name="q"
                    class="w-full outline-none text-base text-gray-700 bg-transparent"
                    id="search-input"
                    placeholder=""
                />
            </div>
            <button type="submit"
                class="w-full sm:w-auto px-6 py-3 rounded-xl text-black font-semibold text-sm transition hover:opacity-90"
                style="background:#FCB315;">
                Rechercher
            </button>
        </form>

        <!-- Suggestions -->
        <div class="flex flex-wrap justify-center gap-2 mt-5 text-[rgb(43,43,43)]">
            @foreach(['Mathématiques', 'Cuisine', 'Anglais', 'Sport', 'Lingala', 'Informatique'] as $suggestion)
            <button onclick="document.getElementById('search-input').value='{{ $suggestion }}'"
                class="text-sm px-4 py-1.5 rounded-lg border border-gray-200 bg-white text-[rgb(43,43,43)] hover:border-yellow-400 hover:text-yellow-500 transition">
                {{ $suggestion }}
            </button>
            @endforeach
        </div>
    </div>

</section>

<!-- STATS -->
<section class="py-16 bg-white border-b border-gray-100">
    <div class="max-w-5xl mx-auto px-4">

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">

            <!-- Stat 1 -->
            <div class="relative text-center group">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4" style="background:#FFF8E7;">
                    <svg class="w-7 h-7" style="color:#FCB315;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4a4 4 0 11-8 0 4 4 0 018 0zm6 4a2 2 0 100-4 2 2 0 000 4zM3 16a2 2 0 100-4 2 2 0 000 4z"/>
                    </svg>
                </div>
                <div class="flex items-center justify-center gap-1 mb-2">
                    <span class="text-3xl font-black" style="color:#FCB315;">+</span>
                    <span class="text-5xl font-black text-[#FCB315] counter" data-target="10" style="font-family:'Plus Jakarta Sans',sans-serif;">0</span>

                </div>
                <p class="font-semibold text-black mb-1">Professeurs</p>
                <p class="text-sm" style="color:#2b2b2b;">Des experts passionnés prêts à vous accompagner</p>
            </div>

            <!-- Séparateur vertical -->
            <div class="relative text-center group" style="border-left: 1px solid #f0f0f0; border-right: 1px solid #f0f0f0;">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4" style="background:#FFF8E7;">
                    <svg class="w-7 h-7" style="color:#FCB315;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="flex items-center justify-center gap-1 mb-2">
                    <span class="text-3xl font-black" style="color:#FCB315;">+</span>
                    <span class="text-5xl font-black text-[#FCB315] counter" data-target="20" style="font-family:'Plus Jakarta Sans',sans-serif;">0</span>

                </div>
                <p class="font-semibold text-black mb-1">Matières</p>
                <p class="text-sm" style="color:#2b2b2b;">Des disciplines variées pour tous vos objectifs</p>
            </div>

            <!-- Stat 3 -->
            <div class="relative text-center group">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4" style="background:#FFF8E7;">
                    <svg class="w-7 h-7" style="color:#FCB315;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <div class="flex items-center justify-center gap-1 mb-2">
                    <span class="text-3xl font-black" style="color:#FCB315;">+</span>
                    <span class="text-5xl font-black text-[#FCB315] counter" data-target="50" style="font-family:'Plus Jakarta Sans',sans-serif;">0</span>

                </div>
                <p class="font-semibold text-black mb-1">Élèves satisfaits</p>
                <p class="text-sm" style="color:#2b2b2b;">Des apprenants qui progressent chaque jour</p>
            </div>

        </div>
    </div>
</section>


<!-- CATÉGORIES -->
<section class="py-16 px-4 bg-white">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-4xl font-bold text-black mb-2" style="font-family:'Poppins',sans-serif;">Explorez par catégorie</h2>
        <p class="text-gray-400 text-sm mb-8">Des cours pour tous les goûts et tous les niveaux</p>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">

    <a href="{{ route('cours.index', ['categorie' => 'Mathématiques']) }}"
       class="flex flex-col items-center gap-2 p-4 rounded-2xl border border-gray-100 bg-gray-50 hover:border-yellow-400 hover:bg-yellow-50 transition group">
        <div class="w-10 h-10 flex items-center justify-center rounded-xl" style="background:#FFF8E7;">
            <svg class="w-5 h-5" style="color:#FCB315;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
        </div>
        <span class="text-sm font-medium text-gray-700 group-hover:text-yellow-600">Mathématiques</span>
    </a>

    <a href="{{ route('cours.index', ['categorie' => 'Cuisine']) }}"
       class="flex flex-col items-center gap-2 p-4 rounded-2xl border border-gray-100 bg-gray-50 hover:border-yellow-400 hover:bg-yellow-50 transition group">
        <div class="w-10 h-10 flex items-center justify-center rounded-xl" style="background:#FFF8E7;">
            <svg class="w-5 h-5" style="color:#FCB315;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
            </svg>
        </div>
        <span class="text-sm font-medium text-gray-700 group-hover:text-yellow-600">Cuisine</span>
    </a>

    <a href="{{ route('cours.index', ['categorie' => 'Sport']) }}"
       class="flex flex-col items-center gap-2 p-4 rounded-2xl border border-gray-100 bg-gray-50 hover:border-yellow-400 hover:bg-yellow-50 transition group">
        <div class="w-10 h-10 flex items-center justify-center rounded-xl" style="background:#FFF8E7;">
            <svg class="w-5 h-5" style="color:#FCB315;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <span class="text-sm font-medium text-gray-700 group-hover:text-yellow-600">Sport</span>
    </a>

    <a href="{{ route('cours.index', ['categorie' => 'Langues']) }}"
       class="flex flex-col items-center gap-2 p-4 rounded-2xl border border-gray-100 bg-gray-50 hover:border-yellow-400 hover:bg-yellow-50 transition group">
        <div class="w-10 h-10 flex items-center justify-center rounded-xl" style="background:#FFF8E7;">
            <svg class="w-5 h-5" style="color:#FCB315;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
            </svg>
        </div>
        <span class="text-sm font-medium text-gray-700 group-hover:text-yellow-600">Langues</span>
    </a>

    <a href="{{ route('cours.index', ['categorie' => 'Musique']) }}"
       class="flex flex-col items-center gap-2 p-4 rounded-2xl border border-gray-100 bg-gray-50 hover:border-yellow-400 hover:bg-yellow-50 transition group">
        <div class="w-10 h-10 flex items-center justify-center rounded-xl" style="background:#FFF8E7;">
            <svg class="w-5 h-5" style="color:#FCB315;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
            </svg>
        </div>
        <span class="text-sm font-medium text-gray-700 group-hover:text-yellow-600">Musique</span>
    </a>

    <a href="{{ route('cours.index', ['categorie' => 'Informatique']) }}"
       class="flex flex-col items-center gap-2 p-4 rounded-2xl border border-gray-100 bg-gray-50 hover:border-yellow-400 hover:bg-yellow-50 transition group">
        <div class="w-10 h-10 flex items-center justify-center rounded-xl" style="background:#FFF8E7;">
            <svg class="w-5 h-5" style="color:#FCB315;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <span class="text-sm font-medium text-gray-700 group-hover:text-yellow-600">Informatique</span>
    </a>

</div>
    </div>
</section>

<!-- PROFESSEURS RECOMMANDÉS -->
<section class="py-16 px-4 bg-white">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-4xl font-bold text-black mb-2" style="font-family:'Plus Jakarta Sans',sans-serif;">
            Les meilleurs profs de Côte d'Ivoire sont sur Kimboo
        </h2>
        <p class="text-sm mb-8" style="color:#2b2b2b;">Vérifiés par l'équipe Kimboo</p>

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
    <div class="absolute top-3 right-3">
        <x-verified-badge />
    </div>
    @endif

    <!-- Bouton like bas à droite -->
    @auth
    <button onclick="toggleFavori({{ $profile->id }}, this)"
        class="absolute bottom-3 right-3 w-8 h-8 rounded-full flex items-center justify-center transition hover:scale-110"
        style="background:rgba(255,255,255,0.9);">
        <svg class="w-4 h-4 favori-icon" fill="{{ in_array($profile->id, $favorisIds) ? '#e53e3e' : 'none' }}"
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

                <!-- Prix + 1er cours -->
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-black">{{ number_format($profile->hourly_rate, 0, ',', ' ') }} FCFA / H</span>
                    @if($profile->first_course_free)
                    <span class="text-sm font-medium" style="color:#FCB315;">1er cours offert</span>
                    @endif
                </div>
            </a>
            @empty
            <p class="col-span-4 text-center py-10" style="color:#2b2b2b;">Aucun professeur disponible.</p>
            @endforelse
        </div>
    </div>
</section>
<!-- COMMENT ÇA MARCHE -->
<section class="py-24 px-4 bg-white" id="comment-ca-marche">
    <div class="max-w-6xl mx-auto">

        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 rounded-full text-sm font-bold mb-4" style="background:#FFF8E7; color:#FCB315;">
                SIMPLE & RAPIDE
            </span>
            <h2 class="text-5xl font-bold text-black mb-4" style="font-family:'Plus Jakarta Sans',sans-serif;">
                Comment marche Kimboo ?
            </h2>
            <p class="text-gray-400 max-w-md mx-auto">Trouvez votre professeur idéal et commencez à apprendre en moins de 5 minutes</p>
        </div>

        <!-- Steps -->
        <div class="relative">
            <!-- Ligne de connexion -->
            <div class="hidden md:block absolute top-12 left-0 right-0 h-0.5 mx-32" style="background: linear-gradient(90deg, #FCB315 0%, #FCB315 100%); opacity:0.2;"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

                <!-- Étape 1 -->
                <div class="relative group">
                    <div class="bg-white rounded-3xl p-8 transition-all duration-300 group-hover:-translate-y-2" style="box-shadow: 0 8px 40px rgba(252, 179, 21, 0.42); border: 2px solid #FFF8E7;">
                        <!-- Numéro -->
                        <div class="flex items-center justify-between mb-6">
                            <span class="text-7xl font-black" style="color:#F0D080; font-family:'Plus Jakarta Sans',sans-serif; line-height:1;">01</span>
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background:#FCB315;">
                                <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-black mb-3" style="font-family:'Plus Jakarta Sans',sans-serif;">
                            Trouver votre mentor
                        </h3>
                        <p class="text-[rgb(43,43,43)] text-sm leading-relaxed">
                            Entrez en contact avec un mentor dont les objectifs et le style d'apprentissage s'adaptent à vos attentes.
                        </p>
                    </div>
                </div>

                <!-- Étape 2 -->
                <div class="relative group md:mt-8">
                    <div class="rounded-3xl p-8 transition-all duration-300 group-hover:-translate-y-2" style="background:#FCB315; box-shadow: 0 8px 40px rgba(252,179,21,0.35);">
                        <div class="flex items-center justify-between mb-6">
                            <span class="text-7xl font-black" style="color:rgba(0,0,0,0.08); font-family:'Plus Jakarta Sans',sans-serif; line-height:1;">02</span>
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-black">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-black mb-3" style="font-family:'Plus Jakarta Sans',sans-serif;">
                            Commencer votre apprentissage
                        </h3>
                        <p class="text-black/70 text-sm leading-relaxed">
                            Réservez votre premier cours, rencontrez votre mentor et discutez de vos objectifs.
                        </p>
                    </div>
                </div>

                <!-- Étape 3 -->
                <div class="relative group">
                    <div class="bg-white rounded-3xl p-8 transition-all duration-300 group-hover:-translate-y-2" style="box-shadow: 0 8px 40px rgba(252, 179, 21, 0.42); border: 2px solid #FFF8E7;">
                        <div class="flex items-center justify-between mb-6">
                            <span class="text-7xl font-black" style="color:#F0D080; font-family:'Plus Jakarta Sans',sans-serif; line-height:1;">03</span>
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background:#FCB315;">
                                <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-black mb-3" style="font-family:'Plus Jakarta Sans',sans-serif;">
                            Progresser
                        </h3>
                        <p class="text-[rgb(43,43,43)] text-sm leading-relaxed">
                            Faites de réels progrès. Apprenez à votre rythme et développez votre aisance semaine après semaine.
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Bouton -->
        <div class="text-center mt-14">
            <a href="#"
               onclick="document.getElementById('search-input').scrollIntoView({behavior:'smooth'}); document.getElementById('search-input').focus(); return false;"
               class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl text-black font-bold text-base transition hover:opacity-90"
               style="background:#FCB315;">
                Trouver mon prof !
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- DEVENIR MENTOR -->
<section class="py-24 px-4" style="background:#0a0a0a;">
    <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">

            <!-- Texte -->
            <div>
                <span class="inline-block px-4 py-1.5 rounded-full text-sm font-bold mb-6" style="background:rgba(252,179,21,0.15); color:#FCB315;">
                    POUR LES PROFESSEURS
                </span>
                <h2 class="text-4xl font-bold text-white mb-6" style="font-family:'Plus Jakarta Sans',sans-serif; line-height:1.2;">
                    Devenez mentor<br>sur <span style="color:#FCB315;">Kimboo</span>
                </h2>
                <p class="text-sm leading-relaxed mb-8" style="color:rgba(255,255,255,0.6);">
                    Gagnez de l'argent en partageant votre savoir. Inscrivez-vous pour donner des cours particuliers en ligne ou en présentiel et recevez vos paiements en toute sécurité.
                </p>

                <!-- Avantages -->
                <div class="space-y-4 mb-10">

    <div class="flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(252,179,21,0.1);">
            <svg class="w-5 h-5" style="color:#FCB315;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <span class="text-sm" style="color:rgba(255,255,255,0.8);">Créez votre profil en quelques minutes</span>
    </div>

    <div class="flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(252,179,21,0.1);">
            <svg class="w-5 h-5" style="color:#FCB315;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <span class="text-sm" style="color:rgba(255,255,255,0.8);">Fixez vos propres tarifs et disponibilités</span>
    </div>

    <div class="flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(252,179,21,0.1);">
            <svg class="w-5 h-5" style="color:#FCB315;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
        </div>
        <span class="text-sm" style="color:rgba(255,255,255,0.8);">Recevez vos paiements en toute sécurité</span>
    </div>

    <div class="flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(252,179,21,0.1);">
            <svg class="w-5 h-5" style="color:#FCB315;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
        </div>
        <span class="text-sm" style="color:rgba(255,255,255,0.8);">Développez votre clientèle facilement</span>
    </div>

</div>

                <a href="{{ route('register') }}?role=professeur"
                   class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl text-black font-bold text-sm transition hover:opacity-90"
                   style="background:#FCB315;">
                    Donner des cours
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

            <!-- Card prof réel -->
            @if($meilleurProf)
            <div class="flex items-center justify-center">
                <div class="bg-white rounded-3xl p-8 text-center relative overflow-hidden" style="width:300px; box-shadow: 0 30px 80px rgba(0,0,0,0.5);">

                    <!-- Décoration -->
                    <div class="absolute top-0 right-0 w-32 h-32 rounded-full opacity-5" style="background:#FCB315; transform:translate(30%, -30%);"></div>

                    <!-- Photo -->
                    <div class="mx-auto mb-4" style="width:100px; height:100px;">
                        @if($meilleurProf->user->avatar)
                        <img src="{{ Storage::url($meilleurProf->user->avatar) }}"
                             alt="{{ $meilleurProf->user->name }}"
                             class="w-full h-full object-cover rounded-2xl"/>
                        @else
                        <div class="w-full h-full rounded-2xl flex items-center justify-center text-4xl font-bold text-black" style="background:#FCB315;">
                            {{ strtoupper(substr($meilleurProf->user->name, 0, 1)) }}
                        </div>
                        @endif
                    </div>

                    <!-- Badge certifié -->
                    @if($meilleurProf->is_verified)
                    <div class="absolute top-3 right-3">
                        <x-verified-badge />
                    </div>
                    @endif

                    <!-- Nom -->
                    <h3 class="text-xl font-bold text-black mb-1" style="font-family:'Plus Jakarta Sans',sans-serif;">
                        {{ $meilleurProf->user->name }}
                    </h3>
                    <p class="text-sm mb-4" style="color:#2b2b2b;">
                        {{ $meilleurProf->courses->first()->category ?? 'Professeur' }}
                    </p>

                    <!-- Note -->
                    <div class="flex items-center justify-center gap-2 mb-4">
                        <div class="flex gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                            <span style="color: {{ $i <= round($meilleurProf->rating) ? '#FCB315' : '#E5E7EB' }}; font-size:18px;">★</span>
                            @endfor
                        </div>
                        <span class="font-bold text-black">{{ $meilleurProf->rating }}</span>
                    </div>

                    <!-- Stats prof -->
                    <div class="grid grid-cols-2 gap-3 mb-5">
                        <div class="rounded-xl p-3" style="background:#F9FAFB;">
                            <p class="text-lg font-black text-black" style="font-family:'Plus Jakarta Sans',sans-serif;">{{ $meilleurProf->reviews_count }}</p>
                            <p class="text-sm" style="color:#2b2b2b;">Avis</p>
                        </div>
                        <div class="rounded-xl p-3" style="background:#F9FAFB;">
                            <p class="text-lg font-black text-black" style="font-family:'Plus Jakarta Sans',sans-serif;">{{ $meilleurProf->experience_years ?? 'N/A' }}</p>
                            <p class="text-sm" style="color:#2b2b2b;">Expérience</p>
                        </div>
                    </div>

                    <!-- Tarif -->
                    <div class="rounded-xl p-3" style="background:#FFF8E7;">
                        <p class="text-lg font-black" style="color:#FCB315; font-family:'Plus Jakarta Sans',sans-serif;">
                            {{ number_format($meilleurProf->hourly_rate, 0, ',', ' ') }} FCFA / H
                        </p>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</section>


<!-- TÉMOIGNAGES -->
<section class="py-20 px-4 bg-white overflow-hidden">
    <div class="max-w-6xl mx-auto">

        <div class="text-center mb-14">
            <span class="inline-block px-4 py-1.5 rounded-full text-sm font-bold mb-4" style="background:#FFF8E7; color:#FCB315;">
                TÉMOIGNAGES
            </span>
            <h2 class="text-5xl font-bold text-black mb-3" style="font-family:'Plus Jakarta Sans',sans-serif;">
                Ils nous font confiance
            </h2>
            <p class="text-sm" style="color:#2b2b2b;">Ce que disent nos élèves et professeurs</p>
        </div>

        <!-- Slider -->
        <div class="relative">
            <div class="overflow-hidden" id="temoignages-wrapper">
                <div class="flex gap-6 transition-transform duration-500 ease-in-out" id="temoignages-track" style="width: max-content;">

                    @foreach([
                        [
                            'nom' => 'Kouassi Adjoua',
                            'role' => 'Élève — Mathématiques',
                            'avatar' => 'K',
                            'note' => 5,
                            'texte' => 'Grâce à Kimboo j\'ai trouvé un excellent prof de maths en moins de 5 minutes. Mon niveau a vraiment progressé et j\'ai eu mon bac avec mention !',
                            'ville' => 'Abidjan',
                        ],
                        [
                            'nom' => 'Sébastien Kouamé',
                            'role' => 'Professeur — Informatique',
                            'avatar' => 'S',
                            'note' => 5,
                            'texte' => 'Kimboo m\'a permis de développer mon activité de cours. Aujourd\'hui j\'ai plus de 15 élèves réguliers et je gère tout depuis mon tableau de bord.',
                            'ville' => 'Abidjan',
                        ],
                        [
                            'nom' => 'Fatou Diallo',
                            'role' => 'Élève — Anglais',
                            'avatar' => 'F',
                            'note' => 5,
                            'texte' => 'J\'avais besoin d\'améliorer mon anglais pour un entretien. Sophie m\'a aidée en seulement 3 semaines. Je recommande vraiment cette plateforme !',
                            'ville' => 'Cocody',
                        ],
                        [
                            'nom' => 'Armand Bléssou',
                            'role' => 'Élève — Cuisine',
                            'avatar' => 'A',
                            'note' => 5,
                            'texte' => 'Les cours de cuisine ivoirienne avec Jean-Paul sont incroyables. J\'apprends les vraies recettes traditionnelles dans une ambiance super sympa.',
                            'ville' => 'Yopougon',
                        ],
                        [
                            'nom' => 'Marie-Claire Touré',
                            'role' => 'Professeure — Langues',
                            'avatar' => 'M',
                            'note' => 5,
                            'texte' => 'Kimboo m\'a donné une visibilité que je n\'avais pas avant. La plateforme est simple à utiliser et les paiements arrivent toujours à temps.',
                            'ville' => 'Marcory',
                        ],
                        [
                            'nom' => 'Thierry N\'Goran',
                            'role' => 'Élève — Sport',
                            'avatar' => 'T',
                            'note' => 5,
                            'texte' => 'Mon coach sportif sur Kimboo m\'a aidé à perdre 8kg en 2 mois. Les séances sont personnalisées et très efficaces. Merci Kimboo !',
                            'ville' => 'Plateau',
                        ],
                    ] as $t)
                    <div class="rounded-3xl p-7 shrink-0" style="width:340px; background:#F9FAFB; border: 2px solid #F0F0F0;">

                        <!-- Étoiles -->
                        <div class="flex gap-0.5 mb-4">
                            @for($i = 1; $i <= $t['note']; $i++)
                            <span style="color:#FCB315; font-size:18px;">★</span>
                            @endfor
                        </div>

                        <!-- Texte -->
                        <p class="text-sm leading-relaxed mb-6" style="color:#2b2b2b;">
                            "{{ $t['texte'] }}"
                        </p>

                        <!-- Auteur -->
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-full flex items-center justify-center text-base font-bold text-black shrink-0" style="background:#FCB315;">
                                {{ $t['avatar'] }}
                            </div>
                            <div>
                                <p class="font-bold text-black text-sm">{{ $t['nom'] }}</p>
                                <p class="text-sm" style="color:#2b2b2b;">{{ $t['role'] }} · {{ $t['ville'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>

            <!-- Boutons navigation -->
            <div class="flex items-center justify-center gap-4 mt-10">
                <button onclick="slidePrev()"
                    class="w-12 h-12 rounded-full border-2 flex items-center justify-center transition hover:border-yellow-400 hover:text-yellow-500"
                    style="border-color:#E5E5E5;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <!-- Dots -->
                <div class="flex gap-2" id="temoignages-dots">
                    @for($i = 0; $i < 6; $i++)
                    <button onclick="goToSlide({{ $i }})"
                        class="w-2 h-2 rounded-full transition-all duration-300 dot"
                        style="background: {{ $i === 0 ? '#FCB315' : '#E5E5E5' }}; width: {{ $i === 0 ? '24px' : '8px' }};">
                    </button>
                    @endfor
                </div>

                <button onclick="slideNext()"
                    class="w-12 h-12 rounded-full border-2 flex items-center justify-center transition hover:border-yellow-400 hover:text-yellow-500"
                    style="border-color:#E5E5E5;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</section>

@endsection
