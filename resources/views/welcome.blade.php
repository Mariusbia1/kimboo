@extends('layouts.app')

@section('title', 'Accueil')

@section('content')

<!-- HERO -->
<section class="w-full px-4 flex items-center justify-center relative"
style="min-height: calc(80vh); background: linear-gradient(rgba(0,0,0,0.50), rgba(0,0,0,0.50)), url('{{ asset('images/hero-home.webp') }}') center/cover no-repeat;">
    <div class="max-w-4xl mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4" style="font-family:'Plus Jakarta Sans',sans-serif; line-height:1.2;">
            {{ \App\Models\SiteSetting::get('hero_title', 'Trouvez le professeur idéal en Côte d’Ivoire') }}
        </h1>
        <p class="text-lg mb-10" style="color:rgba(255,255,255,0.85);">
            {{ \App\Models\SiteSetting::get('hero_subtitle', 'Des cours particuliers à domicile ou en ligne avec les meilleurs enseignants sélectionnés pour votre réussite.') }}
        </p>
        <!-- Barre de recherche bien arrondie sans bordure interne -->
        <form action="{{ route('cours.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2 bg-white rounded-3xl sm:rounded-full p-2 pl-4 sm:pl-6 max-w-2xl mx-auto shadow-2xl transition hover:shadow-[0_12px_40px_rgba(0,0,0,0.22)] border border-white/60 focus-within:ring-2 focus-within:ring-[#FCB315]/50">
            <div class="flex items-center gap-3 flex-1 w-full py-1 sm:py-0">
                <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
                <input
                    type="text"
                    name="q"
                    class="w-full text-sm sm:text-base text-gray-800 bg-transparent placeholder-gray-400 font-medium border-0 border-none ring-0 focus:ring-0 focus:border-0 shadow-none outline-none focus:outline-none"
                    style="border:none !important; outline:none !important; box-shadow:none !important;"
                    id="search-input"
                    placeholder="Que souhaitez-vous apprendre aujourd'hui ?"
                />
            </div>
            <button type="submit"
                class="w-full sm:w-auto px-8 py-3.5 rounded-full text-black font-bold text-sm transition hover:opacity-95 shadow-md flex items-center justify-center hover:scale-[1.02] active:scale-[0.98]"
                style="background:#FCB315;">
                {{ \App\Models\SiteSetting::get('hero_cta_text', 'Rechercher') }}
            </button>
        </form>

        <!-- Suggestions -->
        <div class="flex flex-wrap justify-center gap-2 mt-5 text-[rgb(43,43,43)]">
            @foreach(['Mathématiques', 'Cuisine', 'Anglais', 'Sport', 'Lingala', 'Informatique'] as $suggestion)
            <a href="{{ route('cours.index', ['q' => $suggestion]) }}"
               class="text-xs sm:text-sm px-4 py-1.5 sm:px-5 sm:py-2 rounded-full border border-gray-200 bg-white/95 text-gray-800 hover:border-yellow-400 hover:text-yellow-700 transition shadow-xs font-semibold inline-block backdrop-blur-xs">
                {{ $suggestion }}
            </a>
            @endforeach
        </div>
    </div>

</section>

<!-- PROFESSEURS RECOMMANDÉS -->
<section class="py-16 px-4 bg-white">
    <div class="max-w-5xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-3">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-black mb-1.5" style="font-family:'Plus Jakarta Sans',sans-serif;">
                    {{ \App\Models\SiteSetting::get('home_teachers_title', 'Les meilleurs profs de Côte d\'Ivoire sont sur Kimboo') }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-500">
                    {{ \App\Models\SiteSetting::get('home_teachers_subtitle', 'Vérifiés et certifiés par l\'équipe Kimboo') }}
                </p>
            </div>
            <a href="{{ route('cours.index') }}" class="text-xs sm:text-sm font-bold text-amber-800 hover:text-black flex items-center gap-1.5 transition shrink-0">
                <span>Voir tous les professeurs</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($professeurs as $profile)
            @php
                $firstCourse = $profile->courses->first();
                $categoryName = $firstCourse?->category ?? 'Général';
                $bioSnippet = $profile->bio ? Str::limit($profile->bio, 80) : 'Professeur passionné et certifié sur Kimboo.';
            @endphp
            <div class="flex flex-col justify-between group">
                <div>
                    <!-- Photo cliquable compacte avec Nom & Lieu superposés -->
                    <div class="relative mb-2.5">
                        <a href="{{ route('professeur.profil', $profile->id) }}" class="block w-full aspect-square rounded-2xl overflow-hidden bg-gray-100 relative group-hover:opacity-95 transition shadow-2xs">
                            <x-avatar :user="$profile->user" full="true" rounded="2xl" />
                            
                            <!-- Dégradé léger et discret uniquement en bas pour préserver la clarté de la photo -->
                            <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/55 via-black/15 to-transparent pointer-events-none"></div>

                            <!-- Nom et Lieu superposés en bas à gauche de la photo -->
                            <div class="absolute bottom-2.5 left-3 right-3 text-white pointer-events-none z-10">
                                <h3 class="font-bold text-sm sm:text-base leading-tight text-white drop-shadow-sm truncate">
                                    {{ $profile->user->name }}
                                </h3>
                                <p class="text-[11px] text-white/90 font-medium drop-shadow-2xs truncate mt-0.5">
                                    {{ $profile->lieu_cours_summary }}
                                </p>
                            </div>
                        </a>

                        <!-- Badge en vedette haut à gauche -->
                        @if($profile->is_featured)
                        <div class="absolute top-2.5 left-2.5 z-10">
                            <x-featured-badge />
                        </div>
                        @endif

                        <!-- Badge certifié haut à droite & Bouton like -->
                        <div class="absolute top-2.5 right-2.5 flex items-center gap-1.5 z-10">
                            @if($profile->is_verified)
                            <x-verified-badge size="22" />
                            @endif

                            @auth
                            <button onclick="toggleFavori({{ $profile->id }}, this)"
                                class="w-7 h-7 rounded-full flex items-center justify-center transition hover:scale-110 shadow-xs"
                                style="background:rgba(255,255,255,0.92);">
                                <svg class="w-3.5 h-3.5 favori-icon" fill="{{ in_array($profile->id, $favorisIds) ? '#e53e3e' : 'none' }}"
                                     stroke="{{ in_array($profile->id, $favorisIds) ? '#e53e3e' : '#666' }}"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                            @endauth
                        </div>
                    </div>

                    <!-- Ligne 1 : Note/Avis & Catégorie -->
                    <div class="flex items-center justify-between gap-2 mb-1">
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-[#FCB315] fill-current shrink-0" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="text-xs font-bold text-gray-900">{{ number_format($profile->rating, 1) }}</span>
                            <span class="text-[11px] text-gray-400 font-medium">({{ $profile->reviews_count }} avis)</span>
                        </div>
                        <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-900 border border-amber-200/60 shrink-0">
                            {{ $categoryName }}
                        </span>
                    </div>

                    <!-- Ligne 2 : Bio concise -->
                    <p class="text-xs text-gray-500 leading-relaxed mb-2 line-clamp-2">
                        {{ $bioSnippet }}
                    </p>
                </div>

                <!-- Ligne 3 : Prix & 1er cours offert -->
                <div class="flex items-center justify-between gap-1 pt-0.5">
                    <div class="flex items-baseline gap-1">
                        <span class="text-sm font-extrabold text-gray-900">{{ number_format($profile->hourly_rate, 0, ',', ' ') }}</span>
                        <span class="text-[11px] font-semibold text-gray-400">FCFA/h</span>
                    </div>
                    @if($profile->first_course_free)
                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-md bg-[#FFF8E7] text-[#9A6A00] border border-[#FCB315]/40 shrink-0">
                        1er cours offert
                    </span>
                    @endif
                </div>
            </div>
            @empty
            <p class="col-span-1 sm:col-span-2 lg:col-span-3 text-center py-10" style="color:#2b2b2b;">Aucun professeur disponible.</p>
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
            <a href="{{ route('cours.index') }}"
               class="inline-flex items-center gap-3 px-8 py-4 rounded-full text-black font-bold text-base transition hover:opacity-90 shadow-md"
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
<section class="py-16 sm:py-20 px-4 bg-white">
    <div class="max-w-6xl mx-auto rounded-3xl md:rounded-[36px] overflow-hidden grid grid-cols-1 lg:grid-cols-2 shadow-2xl">

        <!-- Image Mentor à gauche pleine hauteur -->
        <div class="relative w-full h-[380px] sm:h-[460px] lg:h-auto min-h-full bg-gray-100">
            <img src="{{ asset('images/mentor-kimboo.jpg') }}"
                 alt="Devenez mentor sur Kimboo"
                 class="w-full h-full object-cover object-top absolute inset-0"
                 loading="lazy" />
        </div>

        <!-- Bloc de droite Bleu avec les textes Kimboo -->
        <div class="bg-[#0062FF] p-8 sm:p-12 lg:p-14 flex flex-col justify-center text-black">
            <h2 class="text-4xl sm:text-5xl lg:text-[50px] font-extrabold text-black tracking-tight leading-[1.12] mb-6" style="font-family:'Plus Jakarta Sans',sans-serif;">
                Devenez mentor<br>sur Kimboo
            </h2>

            <p class="text-sm sm:text-base font-medium text-black/90 leading-relaxed mb-8 max-w-lg">
                Gagnez de l'argent en partageant votre savoir. Inscrivez-vous pour donner des cours particuliers en ligne ou en présentiel et recevez vos paiements en toute sécurité.
            </p>

            <!-- Liste des avantages à puces simples -->
            <ul class="space-y-3 mb-10 text-sm sm:text-base font-semibold text-black">
                <li class="flex items-center gap-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-black shrink-0"></span>
                    <span>Créez votre profil en quelques minutes</span>
                </li>
                <li class="flex items-center gap-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-black shrink-0"></span>
                    <span>Fixez vos propres tarifs et disponibilités</span>
                </li>
                <li class="flex items-center gap-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-black shrink-0"></span>
                    <span>Recevez vos paiements en toute sécurité</span>
                </li>
                <li class="flex items-center gap-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-black shrink-0"></span>
                    <span>Développez votre clientèle facilement</span>
                </li>
            </ul>

            <!-- Bouton d'action et lien sous-jacent -->
            <div class="flex flex-col items-start gap-4">
                <a href="{{ route('register') }}?role=professeur"
                   class="w-full sm:w-auto px-8 py-4 rounded-xl bg-black text-white font-bold text-sm sm:text-base flex items-center justify-center gap-3 hover:bg-gray-900 transition shadow-lg hover:scale-[1.01] active:scale-[0.99]">
                    <span>Donner des cours</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>

                <a href="#comment-ca-marche"
                   class="text-xs sm:text-sm font-bold text-black underline underline-offset-4 hover:opacity-75 transition">
                    Comment marche Kimboo ?
                </a>
            </div>
        </div>

    </div>
</section>


<!-- HISTOIRES DE RÉUSSITE & EXPÉRIENCES ÉLÈVES (RUBAN FLOTTANT) -->
<section class="py-20 lg:py-28 px-4 overflow-hidden relative" style="background:#FBFBFA;">
    <div class="max-w-6xl mx-auto mb-14 text-center">
        <!-- Badge de section -->
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white border border-gray-200/90 shadow-2xs mb-4">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-xs font-bold uppercase tracking-wider text-gray-700">Expériences & Résultats Vérifiés</span>
        </div>

        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-black mb-4 tracking-tight" style="font-family:'Plus Jakarta Sans',sans-serif;">
            Ils ont transformé leurs ambitions en réussites
        </h2>
        <p class="text-sm sm:text-base text-gray-600 max-w-2xl mx-auto leading-relaxed">
            Soutien scolaire, examens internationaux ou reconversions professionnelles : découvrez comment nos élèves et enseignants progressent ensemble.
        </p>

        <!-- Indicateurs de confiance -->
        <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-4 mt-7">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-white border border-gray-100 shadow-sm text-xs sm:text-sm font-semibold text-gray-800">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span><strong>98%</strong> de satisfaction élève</span>
            </div>
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-white border border-gray-100 shadow-sm text-xs sm:text-sm font-semibold text-gray-800">
                <svg class="w-4 h-4 text-[#FCB315]" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span><strong>4.9 / 5</strong> note globale</span>
            </div>
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-white border border-gray-100 shadow-sm text-xs sm:text-sm font-semibold text-gray-800">
                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span><strong>+15 000 h</strong> de cours dispensées</span>
            </div>
        </div>
    </div>

    @php
    $stories = [
        [
            'nom' => 'Kouassi Adjoua',
            'role' => 'Élève en Terminale C',
            'ville' => 'Cocody, Abidjan',
            'titre' => 'De 8/20 en Maths au Bac C avec Mention Bien',
            'texte' => 'En début d’année, je perdais pied en mathématiques. Mon professeur sur Kimboo m’a redonné confiance avec une méthode progressive. Je suis passée de 8/20 à 16.5/20 au Bac.',
            'resultat' => '8/20 → 16.5/20 au Bac',
            'matiere' => 'Mathématiques',
            'mentor' => 'Patrick C.',
            'matiere_prof' => 'Maths',
            'badge_bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
        ],
        [
            'nom' => 'Sébastien Kouamé',
            'role' => 'Reconversion Professionnelle',
            'ville' => 'Marcory, Abidjan',
            'titre' => 'D’assistant comptable à Administrateur Systèmes',
            'texte' => 'Je voulais basculer dans l’informatique sans quitter mon emploi. Mon mentor m’a formé le soir sur Linux et les réseaux. Deux mois plus tard, j’ai signé mon premier CDI.',
            'resultat' => 'Embauché en CDI',
            'matiere' => 'Informatique',
            'mentor' => 'Massirima P.',
            'matiere_prof' => 'Réseaux & IT',
            'badge_bg' => 'bg-blue-50 text-blue-800 border-blue-200',
        ],
        [
            'nom' => 'Fatou Diallo',
            'role' => 'Étudiante en Master',
            'ville' => 'Plateau, Abidjan',
            'titre' => 'Admise en Master à l’international avec un TOEFL de 105',
            'texte' => 'J’avais six semaines pour certifier mon anglais pour ma bourse d’études. Grâce aux simulations orales intensives de ma professeure, j’ai obtenu 105/120 dès la première tentative.',
            'resultat' => 'Score TOEFL 105/120',
            'matiere' => 'Anglais',
            'mentor' => 'Aïcha T.',
            'matiere_prof' => 'Anglais',
            'badge_bg' => 'bg-rose-50 text-rose-800 border-rose-200',
        ],
        [
            'nom' => 'Dr. Yao Konan',
            'role' => 'Professeur certifié',
            'ville' => 'Yopougon, Abidjan',
            'titre' => 'Plus de 25 élèves réguliers et des revenus sécurisés',
            'texte' => 'Kimboo a totalement sécurisé et simplifié mon activité d’enseignant. Fini les retards de paiement. Je me concentre à 100% sur la pédagogie et la progression de mes élèves.',
            'resultat' => '+25 élèves réguliers',
            'matiere' => 'Physique-Chimie',
            'mentor' => 'Mentor Kimboo',
            'matiere_prof' => 'Sciences',
            'badge_bg' => 'bg-purple-50 text-purple-800 border-purple-200',
        ],
        [
            'nom' => 'Mme Touré Ahou',
            'role' => 'Parent d’élève',
            'ville' => 'Angré, Cocody',
            'titre' => 'Mon fils a retrouvé le goût d’apprendre et le sourire',
            'texte' => 'En classe de 4e, mon fils décrochait en français. Le professeur particulier Kimboo a su débloquer ses blocages avec bienveillance. Aujourd’hui, il est dans le top 5 de sa classe.',
            'resultat' => 'Moyenne : 9.5 → 15.2/20',
            'matiere' => 'Français',
            'mentor' => 'Gervais N.',
            'matiere_prof' => 'Français',
            'badge_bg' => 'bg-amber-50 text-amber-800 border-amber-200',
        ],
        [
            'nom' => 'Armand Bléssou',
            'role' => 'Entrepreneur & Traiteur',
            'ville' => 'Treichville, Abidjan',
            'titre' => 'Du cours particulier au lancement de mon service traiteur',
            'texte' => 'Les cours particuliers m’ont permis de maîtriser les cuissons et le dressage gastronomique. J’ai ouvert ma propre activité traiteur avec succès.',
            'resultat' => 'Service traiteur créé',
            'matiere' => 'Cuisine & Traiteur',
            'mentor' => 'Jean-Paul B.',
            'matiere_prof' => 'Gastronomie',
            'badge_bg' => 'bg-orange-50 text-orange-800 border-orange-200',
        ],
    ];
    @endphp

    <!-- Ruban flottant défilant sur une seule ligne -->
    <div class="relative w-full overflow-hidden py-4">
        <!-- Dégradés latéraux pour effet flottant infini -->
        <div class="pointer-events-none absolute left-0 top-0 bottom-0 w-16 sm:w-36 bg-gradient-to-r from-[#FBFBFA] to-transparent z-10"></div>
        <div class="pointer-events-none absolute right-0 top-0 bottom-0 w-16 sm:w-36 bg-gradient-to-l from-[#FBFBFA] to-transparent z-10"></div>

        <div class="kimboo-marquee-track flex gap-6 sm:gap-7 w-max">
            <!-- Première boucle -->
            @foreach($stories as $s)
            <div class="rounded-3xl p-7 sm:p-8 bg-white border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_45px_rgb(0,0,0,0.08)] hover:-translate-y-2 hover:border-[#FCB315]/40 transition-all duration-300 flex flex-col justify-between shrink-0 relative overflow-hidden"
                 style="width: 400px; max-width: 85vw;">
                <!-- Filigrane discret -->
                <div class="absolute top-4 right-5 text-gray-100/90 select-none pointer-events-none font-serif text-6xl leading-none">
                    “
                </div>

                <div>
                    <!-- En-tête de la carte : Matière & Badge Résultat -->
                    <div class="flex items-center justify-between gap-2 mb-4 relative z-1">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider bg-gray-50 px-2.5 py-1 rounded-md border border-gray-100">
                            {{ $s['matiere'] }}
                        </span>
                        <div class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-full border {{ $s['badge_bg'] }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            <span>{{ $s['resultat'] }}</span>
                        </div>
                    </div>

                    <!-- Titre du témoignage -->
                    <h3 class="font-extrabold text-black text-base sm:text-lg leading-snug mb-3 relative z-1" style="font-family:'Plus Jakarta Sans',sans-serif;">
                        « {{ $s['titre'] }} »
                    </h3>

                    <!-- Citation / Texte -->
                    <p class="text-xs sm:text-sm text-gray-600 leading-relaxed mb-6 relative z-1">
                        {{ $s['texte'] }}
                    </p>
                </div>

                <!-- Footer de la carte -->
                <div class="pt-4 border-t border-gray-100/90 relative z-1">
                    <div class="flex items-center justify-between mb-3.5">
                        <x-star-rating :rating="5" size="3.5" />
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-gray-500 bg-gray-50 px-2.5 py-0.5 rounded-md border border-gray-100">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Accompagné par {{ $s['mentor'] }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 min-w-0">
                            <x-avatar :name="$s['nom']" size="10" />
                            <div class="min-w-0">
                                <p class="font-bold text-black text-sm truncate">{{ $s['nom'] }}</p>
                                <p class="text-xs text-gray-500 font-medium truncate">{{ $s['role'] }} · {{ $s['ville'] }}</p>
                            </div>
                        </div>

                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 border border-emerald-100 shrink-0 ml-2">
                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Vérifié
                        </span>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Seconde boucle identique pour le défilement infini sans interruption -->
            @foreach($stories as $s)
            <div class="rounded-3xl p-7 sm:p-8 bg-white border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_45px_rgb(0,0,0,0.08)] hover:-translate-y-2 hover:border-[#FCB315]/40 transition-all duration-300 flex flex-col justify-between shrink-0 relative overflow-hidden"
                 style="width: 400px; max-width: 85vw;">
                <!-- Filigrane discret -->
                <div class="absolute top-4 right-5 text-gray-100/90 select-none pointer-events-none font-serif text-6xl leading-none">
                    “
                </div>

                <div>
                    <!-- En-tête de la carte : Matière & Badge Résultat -->
                    <div class="flex items-center justify-between gap-2 mb-4 relative z-1">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider bg-gray-50 px-2.5 py-1 rounded-md border border-gray-100">
                            {{ $s['matiere'] }}
                        </span>
                        <div class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-full border {{ $s['badge_bg'] }}">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            <span>{{ $s['resultat'] }}</span>
                        </div>
                    </div>

                    <!-- Titre du témoignage -->
                    <h3 class="font-extrabold text-black text-base sm:text-lg leading-snug mb-3 relative z-1" style="font-family:'Plus Jakarta Sans',sans-serif;">
                        « {{ $s['titre'] }} »
                    </h3>

                    <!-- Citation / Texte -->
                    <p class="text-xs sm:text-sm text-gray-600 leading-relaxed mb-6 relative z-1">
                        {{ $s['texte'] }}
                    </p>
                </div>

                <!-- Footer de la carte -->
                <div class="pt-4 border-t border-gray-100/90 relative z-1">
                    <div class="flex items-center justify-between mb-3.5">
                        <x-star-rating :rating="5" size="3.5" />
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-gray-500 bg-gray-50 px-2.5 py-0.5 rounded-md border border-gray-100">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Accompagné par {{ $s['mentor'] }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 min-w-0">
                            <x-avatar :name="$s['nom']" size="10" />
                            <div class="min-w-0">
                                <p class="font-bold text-black text-sm truncate">{{ $s['nom'] }}</p>
                                <p class="text-xs text-gray-500 font-medium truncate">{{ $s['role'] }} · {{ $s['ville'] }}</p>
                            </div>
                        </div>

                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 border border-emerald-100 shrink-0 ml-2">
                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Vérifié
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<style>
@keyframes kimbooMarquee {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.kimboo-marquee-track {
    animation: kimbooMarquee 45s linear infinite;
}
.kimboo-marquee-track:hover {
    animation-play-state: paused;
}
</style>

@endsection
