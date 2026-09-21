@extends('layouts.app')

@php
    $mainCourse = $profile->courses->first();
@endphp

@section('title', ($mainCourse ? $mainCourse->title . ' — ' : '') . $profile->user->name . ' — Kimboo')

@section('content')

@if(session('success'))
<div class="max-w-6xl mx-auto px-6 pt-6">
    <div class="px-5 py-3 rounded-full text-sm font-medium text-green-700 bg-green-100 flex items-center gap-2">
        <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
</div>
@endif

<div class="max-w-6xl mx-auto px-4 py-8 sm:px-6 lg:py-12">
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-[340px_minmax(0,1fr)] lg:items-start">

        <!-- Colonne gauche (Profil & Tarifs) -->
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-100 shadow-sm lg:sticky lg:top-[90px]">

            <!-- Photo grand format centrée -->
            <div class="relative mb-5 w-full aspect-square rounded-3xl overflow-hidden bg-gray-100 shadow-sm">
                <x-avatar :user="$profile->user" full="true" rounded="3xl" />

                <!-- Badge certifié haut à droite -->
                @if($profile->is_verified)
                <div class="absolute top-3 right-3 z-10">
                    <x-verified-badge />
                </div>
                @endif

                <!-- Bouton like bas à droite -->
                @auth
                @php
                    $isFavori = \App\Models\Favorite::where('user_id', auth()->id())
                        ->where('teacher_profile_id', $profile->id)
                        ->exists();
                @endphp
                <button onclick="toggleFavori({{ $profile->id }}, this)"
                    class="absolute bottom-3 right-3 w-9 h-9 rounded-full flex items-center justify-center transition hover:scale-110 shadow-sm z-10"
                    style="background:rgba(255,255,255,0.95);">
                    <svg class="w-4 h-4 favori-icon"
                         fill="{{ $isFavori ? '#e53e3e' : 'none' }}"
                         stroke="{{ $isFavori ? '#e53e3e' : '#666' }}"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </button>
                @endauth
            </div>

            <!-- Nom + statut -->
            <div class="text-center mb-3">
                <div class="flex items-center justify-center gap-2 flex-wrap">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-black break-words" style="font-family:'Plus Jakarta Sans',sans-serif;">
                        {{ $profile->user->name }}
                    </h2>
                    @if($profile->is_verified)
                    <x-verified-badge :size="20" title="Certifié" />
                    @endif
                </div>

                <!-- Localisation avec icône -->
                <div class="flex items-center justify-center gap-1.5 mt-1.5 text-gray-600">
                    <svg class="w-4 h-4 shrink-0 text-[#FCB315]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium">{{ $profile->user->ville ?? 'Côte d\'Ivoire' }}</span>
                </div>
            </div>

            <!-- Note étoiles -->
            <div class="flex items-center justify-center gap-2 mb-4">
                <x-star-rating :rating="$profile->rating" size="4" />
                <span class="text-sm font-bold text-black">{{ $profile->rating }}</span>
                <span class="text-sm text-gray-500">({{ $profile->reviews_count }} avis)</span>
            </div>

            <!-- Tarif horaire -->
            <div class="text-center mb-5 p-3 rounded-2xl bg-[#FFF8E7]/50 border border-[#FCB315]/20">
                <p class="text-2xl sm:text-3xl font-black text-black" style="font-family:'Plus Jakarta Sans',sans-serif;">
                    {{ number_format($profile->hourly_rate, 0, ',', ' ') }} FCFA <span class="text-sm font-bold text-gray-500">/ h</span>
                </p>
                @if($profile->first_course_free)
                <div class="mt-2">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-green-700 bg-green-100 px-3 py-1 rounded-full">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>1er cours offert</span>
                    </span>
                </div>
                @endif
            </div>

            <!-- Caractéristiques prof -->
            <div class="space-y-3 pt-4 border-t border-gray-100 mb-6 text-sm">
                @if($profile->experience_years)
                <div class="flex items-center justify-between text-gray-600">
                    <span class="font-medium">Expérience</span>
                    <span class="font-bold text-black">{{ $profile->experience_years }} an(s)</span>
                </div>
                @endif

                @if($nombreEleves > 0)
                <div class="flex items-center justify-between text-gray-600">
                    <span class="font-medium">Élèves accompagnés</span>
                    <span class="font-bold text-black">{{ $nombreEleves }}</span>
                </div>
                @endif

                @if($profile->zone_deplacement)
                <div class="flex items-center justify-between text-gray-600">
                    <span class="font-medium">Zone d'intervention</span>
                    <span class="font-bold text-black">{{ $profile->formatted_zone_deplacement }}</span>
                </div>
                @endif

                <div class="flex items-center justify-between text-gray-600">
                    <span class="font-medium">Temps de réponse</span>
                    <span class="font-bold text-black">
                        {{ $profile->formatted_response_time }}
                    </span>
                </div>
            </div>

            <!-- CTA Boutons arrondis (Point 1) -->
            @auth
            <div class="flex flex-col gap-2.5">
                <a href="#reserver"
                   class="text-center py-3 rounded-full text-black font-bold text-sm transition hover:opacity-90 shadow-sm block"
                   style="background:#FCB315;">
                    Réserver un cours
                </a>
                <a href="{{ route('messages.show', $profile->user->id) }}"
                   class="text-center py-3 rounded-full text-black font-semibold text-sm border-2 border-gray-200 hover:border-yellow-400 transition block hover:bg-gray-50">
                    Contacter le professeur
                </a>
            </div>
            @else
            <a href="{{ route('login') }}"
               class="text-center py-3 rounded-full text-black font-bold text-sm transition hover:opacity-90 shadow-sm block"
               style="background:#FCB315;">
                Se connecter pour réserver
            </a>
            @endauth
        </div>

        <!-- Colonne droite (Contenu & Titre d'annonce SEO) -->
        <div class="flex min-w-0 flex-col gap-6">

            <!-- Titre de l'annonce principal pour le référencement SEO Google (Point 8) -->
            @if($mainCourse)
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm">
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="inline-block px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wide shadow-2xs" style="background:#FFF8E7; color:#FCB315;">
                        {{ $mainCourse->category ?? 'Cours particulier' }}
                    </span>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                        {{ $mainCourse->level ?? 'Tous niveaux' }}
                    </span>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-900 border border-amber-200/60">
                        {{ $mainCourse->formatted_format }}
                    </span>
                    @if($mainCourse->is_group)
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/60">
                        Groupe (max {{ $mainCourse->max_students ?? 5 }})
                    </span>
                    @endif
                </div>
                <!-- H1 principal pour Google -->
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-black leading-tight tracking-tight mb-3" style="font-family:'Plus Jakarta Sans',sans-serif;">
                    {{ $mainCourse->title }}
                </h1>
                <p class="text-sm text-gray-500 font-medium mb-4">
                    Cours particulier avec <span class="text-black font-semibold">{{ $profile->user->name }}</span> à <span class="text-black font-semibold">{{ $profile->user->ville ?? 'Côte d\'Ivoire' }}</span>
                </p>
                <button type="button"
                        onclick="openCourseModalById({{ $mainCourse->id }})"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-xs font-bold transition hover:opacity-90 shadow-2xs border border-amber-300"
                        style="background:#FFF8E7; color:#0B0F19;">
                    <svg class="w-4 h-4 text-[#FCB315]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Voir tous les détails de ce cours</span>
                </button>
            </div>
            @endif

            <!-- Bloc Contenu principal épuré sans cadres isolés (Point 5 & 10) -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm space-y-8">

                <!-- À propos du prof -->
                <div>
                    <h2 class="font-extrabold text-xl text-black mb-3" style="font-family:'Plus Jakarta Sans',sans-serif;">
                        À propos du prof
                    </h2>
                    <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">{{ $profile->bio }}</p>
                </div>

                <!-- À propos du cours -->
                @if($profile->a_propos_cours)
                <div class="pt-6 border-t border-gray-100">
                    <h2 class="font-extrabold text-xl text-black mb-3" style="font-family:'Plus Jakarta Sans',sans-serif;">
                        À propos du cours
                    </h2>
                    <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">{{ $profile->a_propos_cours }}</p>
                </div>
                @endif

                <!-- Parcours académique -->
                @php $parcours_list = is_array($profile->parcours_academique) ? $profile->parcours_academique : json_decode($profile->parcours_academique, true) ?? []; @endphp
                @if(count($parcours_list) > 0)
                <div class="pt-6 border-t border-gray-100">
                    <h2 class="font-extrabold text-xl text-black mb-4" style="font-family:'Plus Jakarta Sans',sans-serif;">
                        Parcours académique
                    </h2>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach($parcours_list as $parcours)
                        <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                            <span class="inline-block text-xs font-semibold px-2.5 py-0.5 rounded-md mb-2" style="background:#FFF8E7; color:#FCB315;">
                                {{ $parcours['annees'] }}
                            </span>
                            <p class="font-bold text-black text-sm mb-1">{{ $parcours['diplome'] }}</p>
                            <p class="text-xs text-gray-500 font-medium">{{ $parcours['etablissement'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Vidéo de présentation -->
                @if($profile->video_url)
                @php
                    preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $profile->video_url, $matches);
                    $videoId = $matches[1] ?? null;
                @endphp
                @if($videoId)
                <div class="pt-6 border-t border-gray-100">
                    <h2 class="font-extrabold text-xl text-black mb-4" style="font-family:'Plus Jakarta Sans',sans-serif;">
                        Vidéo de présentation
                    </h2>
                    <div class="relative overflow-hidden rounded-2xl aspect-video shadow-sm">
                        <iframe
                            src="https://www.youtube.com/embed/{{ $videoId }}"
                            class="absolute top-0 left-0 w-full h-full border-none"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
                @endif
                @endif

                <!-- Cours proposés -->
                <div class="pt-6 border-t border-gray-100">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-5">
                        <div>
                            <h2 class="font-extrabold text-xl text-black" style="font-family:'Plus Jakarta Sans',sans-serif;">
                                Tous les cours de ce professeur
                            </h2>
                            <p class="text-xs text-gray-400 mt-0.5">Cliquez sur un cours pour voir son programme complet, les lieux et modalités</p>
                        </div>
                        @if($profile->courses->count() > 0)
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-amber-50 text-amber-900 border border-amber-200 self-start sm:self-auto">
                            {{ $profile->courses->count() }} cours proposé(s)
                        </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        @forelse($profile->courses as $course)
                        <div onclick="openCourseModalById({{ $course->id }})"
                             class="group cursor-pointer p-5 rounded-2xl bg-gray-50/80 border border-gray-100 hover:border-[#FCB315] hover:bg-amber-50/30 hover:shadow-md transition-all duration-200 flex flex-col justify-between"
                             title="Cliquez pour afficher tous les détails de ce cours">
                            <div>
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <h3 class="font-bold text-black text-sm group-hover:text-amber-700 transition line-clamp-1">
                                        {{ $course->title }}
                                    </h3>
                                    <span class="text-xs px-2.5 py-1 rounded-full font-bold shrink-0 shadow-2xs" style="background:#FFF8E7; color:#FCB315;">
                                        {{ $course->formatted_format }}
                                    </span>
                                </div>

                                <div class="flex flex-wrap items-center gap-1.5 text-xs text-gray-500 mb-2.5 font-medium">
                                    <span class="px-2 py-0.5 rounded-md bg-white border border-gray-200 text-gray-700 font-semibold text-[11px]">{{ $course->category }}</span>
                                    <span>·</span>
                                    <span>{{ $course->level }}</span>
                                    @if($course->is_group)
                                    <span>·</span>
                                    <span class="text-blue-600 font-semibold">Groupe (max {{ $course->max_students ?? 5 }})</span>
                                    @endif
                                </div>

                                @if($course->description)
                                <p class="text-xs text-gray-600 leading-relaxed mb-3 line-clamp-2">
                                    {{ $course->description }}
                                </p>
                                @endif
                            </div>

                            <div class="pt-3 border-t border-gray-200/60 flex items-center justify-between mt-2">
                                <div>
                                    <span class="font-extrabold text-black text-sm">{{ number_format($course->price_per_hour, 0, ',', ' ') }} FCFA</span>
                                    <span class="text-[11px] text-gray-400">/ heure</span>
                                </div>
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-black group-hover:text-[#FCB315] transition">
                                    <span>Voir le détail</span>
                                    <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full py-8 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                            <p class="text-gray-400 text-sm">Aucun cours publié pour le moment.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Avis des élèves -->
                <div class="pt-6 border-t border-gray-100">
                    <h2 class="font-extrabold text-xl text-black mb-4" style="font-family:'Plus Jakarta Sans',sans-serif;">
                        Avis des élèves
                    </h2>
                    @php
                        $reviews = $profile->reviews()->with('user')->latest()->take(5)->get();
                    @endphp
                    @forelse($reviews as $review)
                    <div class="flex flex-col gap-3 py-4 border-b border-gray-100 last:border-0 sm:flex-row sm:gap-4">
                        <x-avatar :user="$review->user" size="10" />
                        <div class="flex-1">
                            <div class="flex flex-col gap-1 mb-1 sm:flex-row sm:items-center sm:justify-between">
                                <p class="font-bold text-black text-sm">{{ $review->user->name }}</p>
                                <x-star-rating :rating="$review->rating" size="3.5" />
                            </div>
                            @if($review->comment)
                            <p class="text-gray-700 text-sm leading-relaxed">{{ $review->comment }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($review->created_at)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6">
                        <p class="text-gray-400 text-sm">Aucun avis pour le moment.</p>
                    </div>
                    @endforelse
                </div>

            </div>

            <!-- Formulaire de Réservation -->
            @auth
            <div id="reserver" class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm">
                <h2 class="font-extrabold text-xl text-black mb-5" style="font-family:'Plus Jakarta Sans',sans-serif;">
                    Réserver un cours avec {{ $profile->user->name }}
                </h2>
                <form action="{{ route('booking.store', $profile->id) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Choisir un cours</label>
                            <select name="course_id" id="booking_course_select" class="w-full border-2 border-gray-100 rounded-2xl px-4 py-3 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 font-medium">
                                @foreach($profile->courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }} — {{ number_format($course->price_per_hour, 0, ',', ' ') }} FCFA/h</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Date et heure souhaitées</label>
                                <input type="datetime-local" name="scheduled_at" required
                                       class="w-full border-2 border-gray-100 rounded-2xl px-4 py-3 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 font-medium"/>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Durée de la séance</label>
                                <select name="duration_hours" class="w-full border-2 border-gray-100 rounded-2xl px-4 py-3 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 font-medium">
                                    <option value="1">1 heure</option>
                                    <option value="2">2 heures</option>
                                    <option value="3">3 heures</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full py-3.5 rounded-full text-black font-bold text-sm transition hover:opacity-90 shadow-md"
                            style="background:#FCB315;">
                            Confirmer la réservation
                        </button>
                    </div>
                </form>
            </div>
            @endauth

        </div>
    </div>
</div>

<!-- PROFILS SIMILAIRES -->
<section class="py-16 px-4 sm:px-6 lg:py-20" style="background:#F7F7F7;">
    <div class="max-w-5xl mx-auto">
        <h2 class="font-extrabold text-xl sm:text-2xl text-black mb-6" style="font-family:'Plus Jakarta Sans',sans-serif;">
            Professeurs similaires
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
            @forelse($similaires as $sim)
            @php
                $simCategory = $sim->courses->first()?->category ?? 'Général';
                $simBio = $sim->bio ? Str::limit($sim->bio, 80) : 'Professeur passionné et certifié sur Kimboo.';
            @endphp
            <a href="{{ route('professeur.profil', $sim->id) }}" class="block group transition flex flex-col justify-between">
                <div>
                    <!-- Photo avec texte superposé -->
                    <div class="relative mb-2.5">
                        <div class="w-full aspect-square rounded-2xl overflow-hidden bg-gray-100 relative group-hover:opacity-95 transition shadow-2xs">
                            <x-avatar :user="$sim->user" full="true" rounded="2xl" />
                            <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/55 via-black/15 to-transparent pointer-events-none"></div>

                            <div class="absolute bottom-2.5 left-3 right-3 text-white pointer-events-none z-10">
                                <h3 class="font-bold text-sm sm:text-base leading-tight text-white drop-shadow-sm truncate">
                                    {{ $sim->user->name }}
                                </h3>
                                <p class="text-[11px] text-white/90 font-medium drop-shadow-2xs truncate mt-0.5">
                                    {{ $sim->lieu_cours_summary }}
                                </p>
                            </div>
                        </div>

                        <!-- Badge certifié haut à droite -->
                        @if($sim->is_verified)
                        <div class="absolute top-2.5 right-2.5 z-10">
                            <x-verified-badge size="22" />
                        </div>
                        @endif
                    </div>

                    <!-- Note & Catégorie -->
                    <div class="flex items-center justify-between gap-2 mb-1">
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-[#FCB315] fill-current shrink-0" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="text-xs font-bold text-gray-900">{{ number_format($sim->rating, 1) }}</span>
                            <span class="text-[11px] text-gray-400 font-medium">({{ $sim->reviews_count }} avis)</span>
                        </div>
                        <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-900 border border-amber-200/60 shrink-0">
                            {{ $simCategory }}
                        </span>
                    </div>

                    <!-- Bio concise -->
                    <p class="text-xs text-gray-500 leading-relaxed mb-2 line-clamp-2">
                        {{ $simBio }}
                    </p>
                </div>

                <!-- Prix & 1er cours offert -->
                <div class="flex items-center justify-between gap-1 pt-0.5 mt-auto">
                    <div class="flex items-baseline gap-1">
                        <span class="text-sm font-extrabold text-gray-900">{{ number_format($sim->hourly_rate, 0, ',', ' ') }}</span>
                        <span class="text-[11px] font-semibold text-gray-400">FCFA/h</span>
                    </div>
                    @if($sim->first_course_free)
                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-md bg-[#FFF8E7] text-[#9A6A00] border border-[#FCB315]/40 shrink-0">
                        1er cours offert
                    </span>
                    @endif
                </div>
            </a>
            @empty
            <p class="col-span-3 text-gray-400 text-sm">Aucun professeur similaire disponible.</p>
            @endforelse
        </div>
    </div>
</section>

<!-- MODAL DÉTAIL DU COURS -->
<div id="course-detail-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6 overflow-y-auto"
     aria-labelledby="modal-course-title"
     role="dialog"
     aria-modal="true">

    <!-- Backdrop avec flou d'arrière-plan -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeCourseModal()"></div>

    <!-- Conteneur Modal -->
    <div class="relative bg-white rounded-3xl max-w-2xl w-full shadow-2xl border border-gray-100 overflow-hidden z-10 my-8 transform transition-all flex flex-col max-h-[90vh]">

        <!-- En-tête avec bannière thématique -->
        <div class="p-6 sm:p-7 border-b border-gray-100 relative bg-gradient-to-br from-amber-50/70 via-white to-white">
            <button type="button"
                    onclick="closeCourseModal()"
                    class="absolute top-5 right-5 w-9 h-9 rounded-full bg-white/90 hover:bg-gray-100 border border-gray-200 text-gray-500 hover:text-black flex items-center justify-center transition shadow-sm"
                    aria-label="Fermer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- Badges -->
            <div class="flex flex-wrap items-center gap-2 mb-3 pr-10">
                <span id="modal-course-category" class="px-3 py-1 rounded-full text-xs font-bold tracking-wide uppercase shadow-2xs" style="background:#FFF8E7; color:#FCB315;">
                    Matière
                </span>
                <span id="modal-course-level" class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                    Niveau
                </span>
                <span id="modal-course-format" class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-100/60 text-amber-900 border border-amber-200/60">
                    Format
                </span>
                <span id="modal-course-type" class="px-3 py-1 rounded-full text-xs font-semibold">
                    Type
                </span>
            </div>

            <!-- Titre du cours -->
            <h2 id="modal-course-title" class="text-xl sm:text-2xl font-black text-[#0B0F19] tracking-tight leading-snug" style="font-family:'Plus Jakarta Sans',sans-serif;">
                Titre du cours
            </h2>
        </div>

        <!-- Corps du modal (Scrollable) -->
        <div class="p-6 sm:p-7 overflow-y-auto space-y-6 text-sm text-gray-700">

            <!-- Grille des caractéristiques clés -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <!-- Tarif horaire -->
                <div class="p-3.5 rounded-2xl bg-[#FFFDF5] border border-amber-200/60">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-amber-800/80 mb-1">Tarif horaire</p>
                    <p id="modal-course-price" class="text-base sm:text-lg font-black text-[#0B0F19]">10 000 FCFA</p>
                    <p class="text-[10px] text-gray-500 font-medium">par heure de cours</p>
                </div>

                <!-- Durée / Flexibilité -->
                <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-100">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1">Durée séance</p>
                    <p class="text-sm sm:text-base font-bold text-gray-900">1h, 2h ou 3h</p>
                    <p class="text-[10px] text-gray-500 font-medium">selon vos besoins</p>
                </div>

                <!-- Réponse enseignant -->
                <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-100 col-span-2 sm:col-span-1">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1">Réponse moyenne</p>
                    <p class="text-sm sm:text-base font-bold text-gray-900">{{ $profile->formatted_response_time }}</p>
                    <p class="text-[10px] text-gray-500 font-medium">professeur réactif</p>
                </div>
            </div>

            <!-- Description & Méthode pédagogique -->
            <div>
                <h3 class="font-extrabold text-base text-black mb-2 flex items-center gap-2" style="font-family:'Plus Jakarta Sans',sans-serif;">
                    <svg class="w-4 h-4 text-[#FCB315]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Description & Contenu du cours</span>
                </h3>
                <div class="p-4 sm:p-5 rounded-2xl bg-gray-50 border border-gray-100 text-gray-700 leading-relaxed whitespace-pre-line text-sm" id="modal-course-description">
                    Description du cours...
                </div>
            </div>

            <!-- Lieux acceptés & Déplacements -->
            <div>
                <h3 class="font-extrabold text-base text-black mb-2.5 flex items-center gap-2" style="font-family:'Plus Jakarta Sans',sans-serif;">
                    <svg class="w-4 h-4 text-[#FCB315]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Modalités & Lieux d'enseignement</span>
                </h3>
                <div class="flex flex-wrap gap-2" id="modal-course-lieux">
                    <!-- Généré dynamiquement -->
                </div>
                <div id="modal-course-zone-container" class="mt-2.5 text-xs text-gray-500 font-medium flex items-center gap-1.5 hidden">
                    <svg class="w-3.5 h-3.5 text-[#FCB315] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="font-medium text-gray-500">Zone d'intervention :</span>
                    <span id="modal-course-zone" class="font-bold text-gray-800"></span>
                </div>
            </div>

            <!-- À propos du professeur pour ce cours -->
            <div class="p-4 rounded-2xl bg-amber-50/40 border border-amber-200/50 flex items-center gap-3.5">
                <x-avatar :user="$profile->user" size="11" rounded="2xl" />
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1.5">
                        <p class="font-bold text-gray-900 text-sm truncate">{{ $profile->user->name }}</p>
                        @if($profile->is_verified)
                        <x-verified-badge :size="16" />
                        @endif
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-gray-500 truncate mt-0.5">
                        <span>{{ $profile->user->ville ?? 'Côte d\'Ivoire' }}</span>
                        <span>·</span>
                        <span class="inline-flex items-center gap-1 font-bold text-gray-800">
                            <svg class="w-3 h-3 text-[#FCB315] fill-current shrink-0" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span>{{ $profile->rating ?? '5.0' }}</span>
                        </span>
                        <span>({{ $profile->reviews_count ?? 0 }} avis)</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Pied du modal avec actions directes -->
        <div class="p-4 sm:p-6 border-t border-gray-100 bg-gray-50/80 flex flex-col sm:flex-row items-center justify-between gap-3">
            <button type="button" onclick="closeCourseModal()"
                    class="w-full sm:w-auto px-5 py-2.5 rounded-full text-xs font-semibold text-gray-500 hover:text-black hover:bg-gray-200 transition order-2 sm:order-1">
                Fermer
            </button>
            <div class="flex items-center gap-2.5 w-full sm:w-auto order-1 sm:order-2">
                <a href="{{ route('messages.show', $profile->user->id) }}"
                   class="flex-1 sm:flex-initial px-4 py-2.5 rounded-full text-xs font-bold text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 transition shadow-2xs text-center">
                    Contacter le prof
                </a>
                <button type="button" id="modal-book-btn"
                        class="flex-1 sm:flex-initial px-6 py-2.5 rounded-full text-xs font-bold text-black transition hover:opacity-90 shadow-sm flex items-center justify-center gap-1.5"
                        style="background:#FCB315;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Réserver ce cours</span>
                </button>
            </div>
        </div>

    </div>
</div>

<script>
    const coursesData = @json($profile->courses);
    const teacherData = {
        name: @json($profile->user->name),
        ville: @json($profile->user->ville ?? 'Côte d\'Ivoire'),
        avatar: @json($profile->user->avatar ? Storage::url($profile->user->avatar) : null),
        is_verified: @json((bool)$profile->is_verified),
        response_time: @json($profile->formatted_response_time),
        teacher_id: @json($profile->user->id),
        teacher_profile_id: @json($profile->id),
        hourly_rate: @json($profile->hourly_rate),
        zone_deplacement: @json($profile->formatted_zone_deplacement ?? $profile->zone_deplacement ?? null)
    };

    function openCourseModalById(courseId) {
        const course = coursesData.find(c => c.id == courseId);
        if (!course) return;

        // Titre
        document.getElementById('modal-course-title').textContent = course.title;

        // Badges
        document.getElementById('modal-course-category').textContent = course.category || 'Matière';
        document.getElementById('modal-course-level').textContent = course.level || 'Tous niveaux';
        document.getElementById('modal-course-format').textContent = course.formatted_format || (course.format === 'Les deux' ? 'En ligne & Présentiel' : (course.format || 'En ligne & Présentiel'));

        // Tarif
        const formattedPrice = Number(course.price_per_hour).toLocaleString('fr-FR');
        document.getElementById('modal-course-price').textContent = formattedPrice + ' FCFA';

        // Type de cours (Individuel vs Groupe)
        const typeBadge = document.getElementById('modal-course-type');
        if (course.is_group) {
            typeBadge.textContent = 'Cours de groupe (max ' + (course.max_students || 5) + ' élèves)';
            typeBadge.className = 'px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200';
        } else {
            typeBadge.textContent = 'Cours individuel (1-à-1)';
            typeBadge.className = 'px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200';
        }

        // Lieux
        const lieuxContainer = document.getElementById('modal-course-lieux');
        lieuxContainer.textContent = '';

        let lieux = [];
        if (Array.isArray(course.lieu_cours)) {
            lieux = course.lieu_cours;
        } else if (typeof course.lieu_cours === 'string') {
            try { lieux = JSON.parse(course.lieu_cours); } catch(e) { lieux = [course.lieu_cours]; }
        }

        if (lieux.length === 0) {
            if (course.format === 'En ligne') lieux = ['webcam'];
            else if (course.format === 'Présentiel') lieux = ['chez_eleve', 'chez_prof'];
            else lieux = ['chez_eleve', 'chez_prof', 'webcam'];
        }

        const lieuIcons = {
            'chez_prof': 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            'chez_eleve': 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z',
            'webcam': 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
            'default': 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
        };
        const lieuNames = {
            'chez_prof': 'Chez le professeur',
            'chez_eleve': 'À domicile de l\'élève',
            'webcam': 'Par webcam / En ligne'
        };

        lieux.forEach(l => {
            const span = document.createElement('div');
            span.className = 'flex items-center gap-2 px-3 py-1.5 rounded-xl bg-gray-50 border border-gray-200 text-xs font-semibold text-gray-800';

            const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            svg.setAttribute('class', 'w-3.5 h-3.5 text-[#FCB315] shrink-0');
            svg.setAttribute('fill', 'none');
            svg.setAttribute('stroke', 'currentColor');
            svg.setAttribute('viewBox', '0 0 24 24');
            const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            path.setAttribute('stroke-linecap', 'round');
            path.setAttribute('stroke-linejoin', 'round');
            path.setAttribute('stroke-width', '2');
            path.setAttribute('d', lieuIcons[l] || lieuIcons['default']);
            svg.appendChild(path);

            const labelSpan = document.createElement('span');
            labelSpan.textContent = lieuNames[l] || l;

            span.appendChild(svg);
            span.appendChild(labelSpan);
            lieuxContainer.appendChild(span);
        });

        // Zone de déplacement
        const zoneElem = document.getElementById('modal-course-zone');
        const zoneContainer = document.getElementById('modal-course-zone-container');
        const zone = course.formatted_zone_deplacement || course.zone_deplacement || teacherData.zone_deplacement;
        if (zone) {
            zoneElem.textContent = zone;
            zoneContainer.classList.remove('hidden');
        } else {
            zoneContainer.classList.add('hidden');
        }

        // Description
        const descElem = document.getElementById('modal-course-description');
        if (course.description && course.description.trim()) {
            descElem.textContent = course.description;
            descElem.classList.remove('italic', 'text-gray-400');
            descElem.classList.add('text-gray-700');
        } else {
            descElem.textContent = "Ce professeur adaptera son programme et sa méthode d'apprentissage selon vos objectifs et votre niveau lors des premières séances.";
            descElem.classList.add('italic', 'text-gray-500');
            descElem.classList.remove('text-gray-700');
        }

        // Bouton réserver
        const bookBtn = document.getElementById('modal-book-btn');
        if (bookBtn) {
            bookBtn.onclick = function() {
                closeCourseModal();
                const select = document.getElementById('booking_course_select');
                if (select) {
                    select.value = course.id;
                }
                const reserverSection = document.getElementById('reserver');
                if (reserverSection) {
                    reserverSection.scrollIntoView({ behavior: 'smooth' });
                    reserverSection.classList.add('ring-4', 'ring-[#FCB315]/50');
                    setTimeout(() => reserverSection.classList.remove('ring-4', 'ring-[#FCB315]/50'), 2500);
                } else {
                    window.location.assign('/login');
                }
            };
        }

        // Afficher modal
        const modal = document.getElementById('course-detail-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeCourseModal() {
        const modal = document.getElementById('course-detail-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCourseModal();
        }
    });
</script>

@endsection
