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

<!-- LE PROGRÈS COMMENCE AVEC LE BON MENTOR -->
<section class="py-20 lg:py-28 border-y border-gray-200/60" style="background:#F6F6F4;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="text-center mb-14 sm:mb-20">
            <h2 class="text-3xl sm:text-5xl lg:text-6xl font-black text-black tracking-tight mb-4" style="font-family:'Plus Jakarta Sans',sans-serif; line-height: 1.12;">
                Le progrès commence avec<br class="hidden sm:inline"> le bon mentor
            </h2>
            <p class="text-base sm:text-lg text-gray-700 font-medium">
                Des professeurs sélectionnés, un apprentissage qui vous ressemble
            </p>
        </div>

        <!-- Contenu 2 colonnes : Photo HD Pleine Largeur + Statistique & Impact -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-14 items-center w-full">
            <!-- Photo à gauche (7 colonnes sur desktop) -->
            <div class="lg:col-span-7 relative overflow-hidden rounded-[28px] shadow-sm border border-black/5 bg-white aspect-4/3 sm:aspect-16/10 lg:aspect-auto lg:h-[460px]">
                <img src="{{ asset('images/student-learning.jpg') }}" 
                     alt="Apprenant étudiant avec son professeur Kimboo" 
                     class="w-full h-full object-cover object-center transform hover:scale-[1.02] transition-transform duration-700"
                     loading="lazy">
            </div>

            <!-- Statistique & Message fort à droite (5 colonnes sur desktop) -->
            <div class="lg:col-span-5 flex flex-col justify-center space-y-6 lg:pl-2">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white border border-gray-200/90 shadow-2xs w-fit">
                    <span class="w-2 h-2 rounded-full bg-[#FCB315]"></span>
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-800">Impact & Accompagnement</span>
                </div>

                <h3 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-black leading-snug tracking-tight" style="font-family:'Plus Jakarta Sans',sans-serif;">
                    8 apprenants sur 10 interrogés considèrent l’accompagnement d’un professeur essentiel à leur progression.
                </h3>

                <p class="text-xs sm:text-sm text-gray-500 font-medium">
                    Enquête Kimboo auprès de 20 apprenants, septembre 2026.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- COMMENT ÇA MARCHE -->
<section class="py-20 lg:py-28 bg-white" id="comment-ca-marche">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-14 sm:mb-20">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-4 border border-[#FCB315]/40" style="background:#FFF8E7; color:#9A6A00;">
                <span class="w-2 h-2 rounded-full bg-[#FCB315]"></span>
                SIMPLE & RAPIDE
            </span>
            <h2 class="text-3xl sm:text-5xl lg:text-6xl font-black text-black tracking-tight mb-4" style="font-family:'Plus Jakarta Sans',sans-serif; line-height: 1.15;">
                Comment marche Kimboo ?
            </h2>
            <p class="text-gray-600 text-base sm:text-lg max-w-xl mx-auto font-medium">
                Trouvez votre professeur idéal et commencez à apprendre en toute simplicité
            </p>
        </div>

        <!-- 3 Étapes Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-8 items-stretch">

            <!-- Étape 1 : Trouver votre mentor -->
            <div class="bg-[#FBFBFA] rounded-[32px] p-6 sm:p-8 border border-gray-200/70 shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between group overflow-hidden">
                <div>
                    <!-- Header avec numéro et icône -->
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-xs font-black px-3 py-1 rounded-full bg-white text-gray-800 border border-gray-200/80 shadow-2xs font-mono">
                            01
                        </span>
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center bg-[#FFF8E7] text-[#9A6A00] border border-[#FCB315]/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                            </svg>
                        </div>
                    </div>

                    <h3 class="text-xl sm:text-2xl font-black text-black mb-3 tracking-tight" style="font-family:'Plus Jakarta Sans',sans-serif;">
                        Trouver votre mentor
                    </h3>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6 font-medium">
                        Entrez en contact avec un mentor dont les objectifs et le style d'apprentissage s'adaptent à vos attentes.
                    </p>
                </div>

                <!-- Illustration visuelle directe (sans double cadre interne) -->
                <div class="relative w-full h-[250px] flex items-center justify-center pt-2">
                    <!-- Carte arrière (inclinée) -->
                    <div class="absolute w-[185px] bg-white rounded-2xl p-2.5 shadow-md border border-gray-100/90 transform -rotate-6 -translate-x-6 translate-y-3 opacity-70 scale-95 group-hover:-rotate-10 group-hover:-translate-x-8 transition-transform duration-500 pointer-events-none">
                        <div class="w-full h-24 rounded-xl overflow-hidden bg-gray-100 mb-2">
                            <img src="{{ asset('images/mentor-kimboo-2.jpg') }}" alt="Professeur Kimboo" class="w-full h-full object-cover object-top">
                        </div>
                        <div class="text-xs font-bold text-gray-900 truncate">Amina Traoré</div>
                        <div class="text-[10px] text-gray-500 truncate">Anglais & Communication</div>
                        <div class="mt-1 flex items-center justify-between text-[10px]">
                            <span class="text-amber-500 font-bold">5.0 (19)</span>
                            <span class="font-extrabold text-gray-900">5 000 F/h</span>
                        </div>
                    </div>

                    <!-- Carte avant principale -->
                    <div class="relative z-10 w-[205px] bg-white rounded-2xl p-3 shadow-xl border border-gray-200/90 transform rotate-2 translate-x-3 group-hover:rotate-0 group-hover:translate-x-0 group-hover:scale-[1.03] transition-transform duration-500">
                        <div class="w-full h-28 rounded-xl overflow-hidden bg-gray-100 mb-2.5 shadow-2xs">
                            <img src="{{ asset('images/mentor-kimboo.jpg') }}" alt="Professeur Koffi Kouamé" class="w-full h-full object-cover object-top">
                        </div>
                        <div class="text-xs font-black text-gray-900 truncate">Koffi Kouamé</div>
                        <div class="text-[11px] text-gray-500 font-medium truncate">Mathématiques & Physique</div>
                        <div class="mt-2 pt-2 border-t border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-1">
                                <svg class="w-3 h-3 text-[#FCB315] fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-[11px] font-bold text-gray-800">5.0</span>
                                <span class="text-[10px] text-gray-400">(28)</span>
                            </div>
                            <span class="text-xs font-black text-black">5 000 F<span class="text-[9px] text-gray-400 font-normal">/h</span></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Étape 2 : Commencer votre apprentissage -->
            <div class="bg-[#FBFBFA] rounded-[32px] p-6 sm:p-8 border border-gray-200/70 shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between group overflow-hidden">
                <div>
                    <!-- Header avec numéro et icône -->
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-xs font-black px-3 py-1 rounded-full bg-white text-gray-800 border border-gray-200/80 shadow-2xs font-mono">
                            02
                        </span>
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center bg-[#FFF8E7] text-[#9A6A00] border border-[#FCB315]/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>

                    <h3 class="text-xl sm:text-2xl font-black text-black mb-3 tracking-tight" style="font-family:'Plus Jakarta Sans',sans-serif;">
                        Commencer votre apprentissage
                    </h3>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6 font-medium">
                        Réservez votre premier cours, rencontrez votre mentor et discutez de vos objectifs en direct.
                    </p>
                </div>

                <!-- Illustration visuelle directe (sans double cadre interne) -->
                <div class="relative w-full h-[250px] flex items-center justify-center pt-2">
                    <!-- Ticket de séance épuré -->
                    <div class="w-[225px] bg-white rounded-2xl p-4 shadow-xl border border-gray-200/90 transform group-hover:scale-[1.03] transition-transform duration-500">
                        <div class="text-xs font-black text-gray-900 mb-1">
                            Samedi · 10h00 - 11h30
                        </div>
                        <div class="text-[11px] text-gray-500 font-medium mb-3 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            À domicile ou En ligne
                        </div>

                        <div class="pt-3 border-t border-gray-100 flex items-center gap-2 mb-3.5">
                            <div class="w-7 h-7 rounded-full overflow-hidden bg-gray-200 shrink-0">
                                <img src="{{ asset('images/mentor-kimboo.jpg') }}" alt="Professeur" class="w-full h-full object-cover">
                            </div>
                            <div class="truncate text-[11px] font-bold text-gray-800">Avec Koffi Kouamé</div>
                        </div>

                        <div class="w-full py-2 rounded-xl text-center text-xs font-bold text-black shadow-xs" style="background:#FCB315;">
                            Rejoindre la séance
                        </div>
                    </div>
                </div>
            </div>

            <!-- Étape 3 : Progresser -->
            <div class="bg-[#FBFBFA] rounded-[32px] p-6 sm:p-8 border border-gray-200/70 shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between group overflow-hidden">
                <div>
                    <!-- Header avec numéro et icône -->
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-xs font-black px-3 py-1 rounded-full bg-white text-gray-800 border border-gray-200/80 shadow-2xs font-mono">
                            03
                        </span>
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center bg-[#FFF8E7] text-[#9A6A00] border border-[#FCB315]/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                    </div>

                    <h3 class="text-xl sm:text-2xl font-black text-black mb-3 tracking-tight" style="font-family:'Plus Jakarta Sans',sans-serif;">
                        Progresser
                    </h3>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6 font-medium">
                        Faites de réels progrès. Apprenez à votre rythme et développez votre aisance semaine après semaine.
                    </p>
                </div>

                <!-- Illustration visuelle directe (sans double cadre interne) -->
                <div class="relative w-full h-[250px] flex items-center justify-center pt-2">
                    <!-- Carte de suivi épurée -->
                    <div class="w-[225px] bg-white rounded-2xl p-4 shadow-xl border border-gray-200/90 transform group-hover:scale-[1.03] transition-transform duration-500">
                        <div class="text-xs font-black text-gray-900 mb-2">
                            Résultats constatés
                        </div>

                        <div class="space-y-1.5 mb-3.5">
                            <div class="flex justify-between text-[11px] font-semibold text-gray-600">
                                <span>Progression globale</span>
                                <span class="text-gray-900 font-extrabold">92%</span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-1000" style="width: 92%; background: linear-gradient(90deg, #FCB315 0%, #10B981 100%);"></div>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-100">
                            <div class="flex items-center gap-1 mb-1.5">
                                @for($i = 0; $i < 5; $i++)
                                <svg class="w-3 h-3 text-[#FCB315] fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                @endfor
                            </div>
                            <p class="text-[11px] text-gray-600 font-medium italic leading-tight">
                                « J'ai repris confiance et amélioré mes notes dès le 1er mois ! »
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Bouton CTA -->
        <div class="text-center mt-14 sm:mt-16">
            <a href="{{ route('cours.index') }}"
               class="inline-flex items-center gap-3 px-8 py-4 rounded-full text-black font-extrabold text-base transition hover:opacity-95 shadow-md hover:scale-[1.02] active:scale-[0.98]"
               style="background:#FCB315;">
                Trouver mon professeur
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- BANNIÈRE GARANTIE & SATISFACTION (CHARTE KIMBOO) -->
<section class="py-16 sm:py-20 lg:py-24 text-center text-black" style="background: #FCB315;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl sm:text-5xl lg:text-6xl xl:text-7xl font-black text-black tracking-tight uppercase leading-[1.08]" style="font-family:'Plus Jakarta Sans',sans-serif;">
            DES LEÇONS QUE VOUS ALLEZ ADORER.<br class="hidden sm:inline"> GARANTIES.
        </h2>
        <p class="text-sm sm:text-base lg:text-lg font-semibold text-black/85 mt-4 sm:mt-6 max-w-2xl mx-auto leading-relaxed">
            Votre professeur ne vous convient pas ? Essayez en un autre gratuitement !
        </p>
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


<!-- HISTOIRES HUMAINES & TÉMOIGNAGES (RUBAN FLOTTANT ÉPURÉ) -->
<section class="py-20 lg:py-24 px-4 overflow-hidden relative" style="background:#FAF9F6;">
    <div class="max-w-4xl mx-auto mb-14 text-center">
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-black mb-4 tracking-tight" style="font-family:'Plus Jakarta Sans',sans-serif;">
            Des histoires de confiance et de progrès
        </h2>
        <p class="text-sm sm:text-base text-gray-600 max-w-xl mx-auto leading-relaxed">
            Les mots sincères de nos élèves, parents et professeurs qui avancent ensemble partout en Côte d'Ivoire.
        </p>
    </div>

    @php
    $stories = [
        [
            'nom' => 'Mme Ahou Touré',
            'role' => 'Maman de Sarah (12 ans)',
            'ville' => 'Cocody, Abidjan',
            'texte' => 'Quand ma fille a commencé à pleurer devant ses devoirs de maths, j’étais désemparée. Son professeur a fait preuve d’une patience infinie. En trois mois, elle a retrouvé le sourire et l’envie d’apprendre. C’est le plus beau des cadeaux.',
            'cours' => 'Maths avec Patrick C.',
        ],
        [
            'nom' => 'Koffi Jean-Eudes',
            'role' => 'Admis en école d’ingénieur',
            'ville' => 'Yamoussoukro',
            'texte' => 'Je viens d’une famille modeste et le concours me paraissait inaccessible. Mon mentor ne m’a pas seulement enseigné la physique, il a cru en moi quand je doutais de mes capacités. Aujourd’hui, je suis admis.',
            'cours' => 'Physique avec Dr. Yao',
        ],
        [
            'nom' => 'Aïssata Bamba',
            'role' => 'Cadre commerciale',
            'ville' => 'Plateau, Abidjan',
            'texte' => 'J’avais un vrai blocage avec l’anglais, je n’osais jamais prendre la parole en réunion. Avec ma professeure, nous avons pratiqué dans la bienveillance. La semaine dernière, j’ai animé ma première présentation internationale sans panique.',
            'cours' => 'Anglais avec Aïcha T.',
        ],
        [
            'nom' => 'Marc-Antoine D.',
            'role' => 'Professeur d’Histoire-Géo',
            'ville' => 'Yopougon, Abidjan',
            'texte' => 'Être enseignant sur Kimboo, c’est voir l’étincelle s’allumer dans les yeux d’un élève qui pensait être perdu. Pouvoir accompagner des jeunes de façon personnalisée donne tout son sens à ma vocation.',
            'cours' => 'Enseignant certifié',
        ],
        [
            'nom' => 'Sonia Kouassi',
            'role' => 'Bachelière 2024',
            'ville' => 'Marcory, Abidjan',
            'texte' => 'Mon professeur de français m’a appris à aimer la littérature et à exprimer mes idées clairement. Passer de 9 à 16/20 au Bac, je n’y aurais jamais cru au début de l’année. Merci du fond du cœur.',
            'cours' => 'Français avec Gervais N.',
        ],
        [
            'nom' => 'Ismaël Traoré',
            'role' => 'Reconversion professionnelle',
            'ville' => 'Koumassi, Abidjan',
            'texte' => 'Partir de zéro en programmation à 28 ans me faisait peur. Massirima a adapté chaque exercice à mon rythme, pas à pas. Il a été bien plus qu’un professeur, un véritable grand frère et guide.',
            'cours' => 'Code avec Massirima P.',
        ],
    ];
    @endphp

    <!-- Ruban flottant défilant sur une seule ligne -->
    <div class="relative w-full overflow-hidden py-2">
        <!-- Dégradés latéraux pour effet flottant infini -->
        <div class="pointer-events-none absolute left-0 top-0 bottom-0 w-16 sm:w-36 bg-gradient-to-r from-[#FAF9F6] to-transparent z-10"></div>
        <div class="pointer-events-none absolute right-0 top-0 bottom-0 w-16 sm:w-36 bg-gradient-to-l from-[#FAF9F6] to-transparent z-10"></div>

        <div class="kimboo-marquee-track flex gap-6 sm:gap-7 w-max">
            <!-- Première boucle -->
            @foreach($stories as $s)
            <div class="rounded-3xl p-7 sm:p-8 bg-white border border-gray-100 shadow-[0_4px_25px_rgba(0,0,0,0.03)] hover:shadow-[0_15px_35px_rgba(0,0,0,0.06)] hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between shrink-0"
                 style="width: 390px; max-width: 85vw;">
                <div>
                    <!-- Étoiles & Badge vérifié -->
                    <div class="flex items-center justify-between gap-2 mb-4">
                        <x-star-rating :rating="5" size="3.5" />
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50/80 px-2.5 py-0.5 rounded-full border border-emerald-100">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Expérience vécue
                        </span>
                    </div>

                    <!-- Témoignage humain -->
                    <p class="text-xs sm:text-sm text-gray-700 leading-relaxed mb-6 font-normal">
                        « {{ $s['texte'] }} »
                    </p>
                </div>

                <!-- Auteur & Accompagnement -->
                <div class="pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <x-avatar :name="$s['nom']" size="10" />
                        <div class="min-w-0">
                            <p class="font-bold text-black text-sm truncate">{{ $s['nom'] }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $s['role'] }} · {{ $s['ville'] }}</p>
                        </div>
                    </div>

                    <span class="text-[11px] font-medium text-gray-500 bg-gray-50 px-2.5 py-1 rounded-md border border-gray-100 shrink-0 text-right">
                        {{ $s['cours'] }}
                    </span>
                </div>
            </div>
            @endforeach

            <!-- Seconde boucle identique pour le défilement infini sans interruption -->
            @foreach($stories as $s)
            <div class="rounded-3xl p-7 sm:p-8 bg-white border border-gray-100 shadow-[0_4px_25px_rgba(0,0,0,0.03)] hover:shadow-[0_15px_35px_rgba(0,0,0,0.06)] hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between shrink-0"
                 style="width: 390px; max-width: 85vw;">
                <div>
                    <!-- Étoiles & Badge vérifié -->
                    <div class="flex items-center justify-between gap-2 mb-4">
                        <x-star-rating :rating="5" size="3.5" />
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50/80 px-2.5 py-0.5 rounded-full border border-emerald-100">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Expérience vécue
                        </span>
                    </div>

                    <!-- Témoignage humain -->
                    <p class="text-xs sm:text-sm text-gray-700 leading-relaxed mb-6 font-normal">
                        « {{ $s['texte'] }} »
                    </p>
                </div>

                <!-- Auteur & Accompagnement -->
                <div class="pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <x-avatar :name="$s['nom']" size="10" />
                        <div class="min-w-0">
                            <p class="font-bold text-black text-sm truncate">{{ $s['nom'] }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $s['role'] }} · {{ $s['ville'] }}</p>
                        </div>
                    </div>

                    <span class="text-[11px] font-medium text-gray-500 bg-gray-50 px-2.5 py-1 rounded-md border border-gray-100 shrink-0 text-right">
                        {{ $s['cours'] }}
                    </span>
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
    animation: kimbooMarquee 48s linear infinite;
}
.kimboo-marquee-track:hover {
    animation-play-state: paused;
}
</style>

@endsection
