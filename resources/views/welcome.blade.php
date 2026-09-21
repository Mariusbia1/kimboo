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
        <div class="bg-[#0062FF] p-8 sm:p-12 lg:p-16 flex flex-col justify-center text-black">
            <h2 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-black tracking-tight leading-[1.1] mb-6" style="font-family:'Plus Jakarta Sans',sans-serif;">
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
                        <div class="mb-4">
                            <x-star-rating :rating="$t['note']" size="4" />
                        </div>

                        <!-- Texte -->
                        <p class="text-sm leading-relaxed mb-6" style="color:#2b2b2b;">
                            "{{ $t['texte'] }}"
                        </p>

                        <!-- Auteur -->
                        <div class="flex items-center gap-3">
                            <x-avatar :name="$t['nom']" size="11" />
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
