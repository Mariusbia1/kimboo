@extends('layouts.app')

@section('title', 'Accueil')

@section('content')

<!-- HERO -->
<section class="w-full py-20 px-4" style="background: linear-gradient(0deg, #FCB315 0%, #ffffff 100%); min-height: calc(80vh); border-radius: 0 0 70px 70px">
    <div class="max-w-4xl mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-black mb-4" style="font-family:'Poppins',sans-serif; line-height:1.2;">
            Que voulez-vous <span class="text-black">apprendre</span> aujourd'hui ?
        </h1>
        <p class="text-gray-500 text-lg mb-10">Trouvez un professeur qualifié près de chez vous en quelques secondes.</p>

        <!-- Barre de recherche -->
        <form action="{{ route('cours.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3 bg-white rounded-2xl shadow-md p-3 max-w-2xl mx-auto" style="box-shadow: 0 8px 30px rgba(252,179,21,0.15);">
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
        <div class="flex flex-wrap justify-center gap-2 mt-5">
            @foreach(['Mathématiques', 'Cuisine', 'Anglais', 'Sport', 'Lingala', 'Informatique'] as $suggestion)
            <button onclick="document.getElementById('search-input').value='{{ $suggestion }}'"
                class="text-sm px-4 py-1.5 rounded-full border border-gray-200 bg-white text-gray-600 hover:border-yellow-400 hover:text-yellow-500 transition">
                {{ $suggestion }}
            </button>
            @endforeach
        </div>
    </div>
     
</section>

<!-- STATS -->
<section class="py-10 bg-white border-b border-gray-100">
    <div class="max-w-5xl mx-auto px-4 grid grid-cols-3 gap-4 text-center">
        <div>
            <p class="text-3xl font-bold" style="color:#FCB315; font-family:'Poppins',sans-serif;">500+</p>
            <p class="text-sm text-gray-500 mt-1">Professeurs</p>
        </div>
        <div>
            <p class="text-3xl font-bold" style="color:#FCB315; font-family:'Poppins',sans-serif;">50+</p>
            <p class="text-sm text-gray-500 mt-1">Matières</p>
        </div>
        <div>
            <p class="text-3xl font-bold" style="color:#FCB315; font-family:'Poppins',sans-serif;">2000+</p>
            <p class="text-sm text-gray-500 mt-1">Élèves satisfaits</p>
        </div>
    </div>
</section>

<!-- CATÉGORIES -->
<section class="py-16 px-4 bg-white">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-2xl font-bold text-black mb-2" style="font-family:'Poppins',sans-serif;">Explorez par catégorie</h2>
        <p class="text-gray-400 text-sm mb-8">Des cours pour tous les goûts et tous les niveaux</p>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
            @foreach($categories as $cat)
            <a href="{{ route('cours.index', ['categorie' => $cat['label']]) }}"
               class="flex flex-col items-center gap-2 p-4 rounded-2xl border border-gray-100 bg-gray-50 hover:border-yellow-400 hover:bg-yellow-50 transition group">
                <span class="text-3xl">{{ $cat['emoji'] }}</span>
                <span class="text-sm font-medium text-gray-700 group-hover:text-yellow-600">{{ $cat['label'] }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- PROFESSEURS RECOMMANDÉS -->
<section class="py-16 px-4" style="background:#F7F7F7;">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-2xl font-bold text-black mb-2" style="font-family:'Poppins',sans-serif;">Professeurs recommandés</h2>
        <p class="text-gray-400 text-sm mb-8">Vérifiés par l'équipe Kimboo</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($professeurs as $profile)
            <div class="bg-white rounded-2xl p-5 relative" style="box-shadow: 0 4px 12px rgba(0,0,0,0.08);">

                <!-- Badge vérifié -->
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

                <!-- Infos -->
                <h3 class="font-semibold text-black text-base">{{ $profile->user->name }}</h3>
                <p class="text-xs text-gray-400 mb-1">
                    {{ $profile->courses->first()->category ?? 'Cours divers' }}
                </p>

                <!-- Note -->
                <div class="flex items-center gap-1 mb-2">
                    <span style="color:#FCB315;">★</span>
                    <span class="text-sm font-medium text-black">{{ $profile->rating }}</span>
                    <span class="text-xs text-gray-400">({{ $profile->reviews_count }} avis)</span>
                </div>

                <!-- Bio -->
                <p class="text-xs text-gray-500 mb-4 leading-relaxed">{{ Str::limit($profile->bio, 70) }}</p>

                <!-- Prix + 1er cours -->
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
            <p class="text-gray-400 col-span-4 text-center py-10">Aucun professeur disponible pour le moment.</p>
            @endforelse
        </div>
    </div>
</section>

<!-- COMMENT ÇA MARCHE -->
<section class="py-24 px-4 bg-white" id="comment-ca-marche">
    <div class="max-w-6xl mx-auto">

        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 rounded-full text-xs font-bold mb-4" style="background:#FFF8E7; color:#FCB315;">
                SIMPLE & RAPIDE
            </span>
            <h2 class="text-4xl font-bold text-black mb-4" style="font-family:'Plus Jakarta Sans',sans-serif;">
                Comment marche Kimboo ?
            </h2>
            <p class="text-gray-400 max-w-md mx-auto">Trouvez votre professeur idéal et commencez à apprendre en moins de 5 minutes</p>
        </div>

        <!-- Steps -->
        <div class="relative">
            <!-- Ligne de connexion -->
            <div class="hidden md:block absolute top-12 left-0 right-0 h-0.5 mx-32" style="background: linear-gradient(90deg, #FCB315 0%, #FCB315 100%); opacity:0.2;"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

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
                        <p class="text-gray-500 text-sm leading-relaxed">
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
                        <p class="text-gray-500 text-sm leading-relaxed">
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
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:5rem; align-items:center;">

            <!-- Texte -->
            <div>
                <span class="inline-block px-4 py-1.5 rounded-full text-xs font-bold mb-6" style="background:rgba(252,179,21,0.15); color:#FCB315;">
                    POUR LES PROFESSEURS
                </span>
                <h2 class="text-4xl font-bold text-white mb-6" style="font-family:'Plus Jakarta Sans',sans-serif; line-height:1.2;">
                    Devenez mentor<br>sur <span style="color:#FCB315;">Kimboo</span>
                </h2>
                <p class="text-gray-400 text-sm leading-relaxed mb-8">
                    Gagnez de l'argent en partageant votre savoir. Inscrivez-vous pour donner des cours particuliers en ligne ou en présentiel et recevez vos paiements en toute sécurité.
                </p>

                <!-- Avantages -->
                <div class="space-y-4 mb-10">
                    @foreach([
                        ['icon' => '⚡', 'text' => 'Créez votre profil en quelques minutes'],
                        ['icon' => '💰', 'text' => 'Fixez vos propres tarifs et disponibilités'],
                        ['icon' => '🔒', 'text' => 'Recevez vos paiements en toute sécurité'],
                        ['icon' => '🚀', 'text' => 'Développez votre clientèle facilement'],
                    ] as $av)
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 text-lg" style="background:rgba(252,179,21,0.1);">
                            {{ $av['icon'] }}
                        </div>
                        <span class="text-gray-300 text-sm">{{ $av['text'] }}</span>
                    </div>
                    @endforeach
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

            {{-- <!-- Visuel cards profs -->
            <div class="relative" style="height:400px;">

                <!-- Card principale -->
                <div class="absolute top-0 right-0 w-64 bg-white rounded-3xl p-5" style="box-shadow: 0 20px 60px rgba(0,0,0,0.4);">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl font-bold text-black shrink-0" style="background:#FCB315;">
                            A
                        </div>
                        <div>
                            <p class="font-bold text-black text-sm">Alain K.</p>
                            <p class="text-xs text-gray-400">Mathématiques</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1 mb-3">
                        <span style="color:#FCB315; font-size:14px;">★★★★★</span>
                        <span class="text-sm font-bold text-black ml-1">5.0</span>
                        <span class="text-xs text-gray-400">(42 avis)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded-full">✓ Certifié</span>
                        <span class="text-sm font-bold text-black">15 000 Fcfa/h</span>
                    </div>
                </div>

                <!-- Card secondaire -->
                <div class="absolute bottom-10 left-0 w-56 bg-white rounded-3xl p-5" style="box-shadow: 0 20px 60px rgba(0,0,0,0.4);">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg font-bold text-black shrink-0" style="background:#FCB315;">
                            S
                        </div>
                        <div>
                            <p class="font-bold text-black text-sm">Sophie T.</p>
                            <p class="text-xs text-gray-400">Anglais</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <span style="color:#FCB315; font-size:12px;">★★★★★</span>
                        <span class="text-xs font-bold text-black ml-1">4.8</span>
                    </div>
                </div>

                <!-- Badge stat -->
                <div class="absolute top-32 left-10 rounded-2xl px-4 py-3 text-center" style="background:#FCB315; box-shadow: 0 10px 30px rgba(252,179,21,0.4);">
                    <p class="text-2xl font-black text-black" style="font-family:'Plus Jakarta Sans',sans-serif;">500+</p>
                    <p class="text-xs font-semibold text-black/70">Professeurs</p>
                </div>

                <!-- Badge élèves -->
                <div class="absolute bottom-0 right-10 rounded-2xl px-4 py-3 text-center" style="background:#1a1a1a; border: 1px solid rgba(252,179,21,0.2);">
                    <p class="text-2xl font-black" style="color:#FCB315; font-family:'Plus Jakarta Sans',sans-serif;">2000+</p>
                    <p class="text-xs font-semibold text-gray-400">Élèves satisfaits</p>
                </div>

            </div> --}}
            <!-- Visuel -->
<div class="flex items-center justify-center">
    <div class="bg-white rounded-3xl p-8 text-center" style="box-shadow: 0 20px 60px rgba(0,0,0,0.4); width:280px;">

        <!-- Photo / Avatar -->
        <div class="w-24 h-24 rounded-2xl mx-auto mb-4 flex items-center justify-center text-4xl font-bold text-black" style="background:#FCB315; font-family:'Plus Jakarta Sans',sans-serif;">
            A
        </div>

        <!-- Nom -->
        <p class="font-bold text-black text-lg mb-1" style="font-family:'Plus Jakarta Sans',sans-serif;">Alain K.</p>

        <!-- Matière -->
        <p class="text-gray-400 text-sm mb-4">Professeur de mathématiques</p>

        <!-- Note -->
        <div class="flex items-center justify-center gap-2 mb-4">
            <span style="color:#FCB315; font-size:18px;">★★★★★</span>
            <span class="font-bold text-black">5.0</span>
        </div>

        <!-- Badge certifié -->
        <span class="inline-block text-xs font-semibold text-green-700 bg-green-100 px-3 py-1.5 rounded-full">
            ✓ Profil certifié
        </span>
    </div>
</div>
        </div>
    </div>
</section>

@endsection
