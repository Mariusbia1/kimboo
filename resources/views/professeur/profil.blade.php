@extends('layouts.app')

@section('title', $profile->user->name)

@section('content')

@if(session('success'))
<div class="max-w-6xl mx-auto px-6 pt-6">
    <div class="px-4 py-3 rounded-xl text-sm font-medium text-green-700 bg-green-100">
        ✓ {{ session('success') }}
    </div>
</div>
@endif

<div class="max-w-6xl mx-auto px-6 py-12">
    <div style="display:grid; grid-template-columns: 340px 1fr; gap:2rem; align-items:start;">

        <!-- Colonne gauche -->
<div class="bg-white rounded-2xl p-6" style="position:sticky; top:90px;">

    <!-- Photo en carré centré -->
<div class="relative mx-auto mb-4" style="width:120px; height:120px;">
    @if($profile->user->avatar)
    <img src="{{ Storage::url($profile->user->avatar) }}"
         alt="{{ $profile->user->name }}"
         class="w-full h-full object-cover rounded-xl"/>
    @else
    <div class="w-full h-full rounded-xl flex items-center justify-center text-4xl font-bold text-black" style="background:#FCB315;">
        {{ strtoupper(substr($profile->user->name, 0, 1)) }}
    </div>
    @endif

    <!-- Badge certifié haut à droite -->
    @if($profile->is_verified)
    <div class="absolute top-2 right-2 w-6 h-6 rounded-full flex items-center justify-center" style="background:#1877F2;">
        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414L8.414 15 3.293 9.879a1 1 0 011.414-1.414L8.414 12.172l6.879-6.879a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
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
        class="absolute bottom-2 right-2 w-8 h-8 rounded-full flex items-center justify-center transition hover:scale-110"
        style="background:rgba(255,255,255,0.9); box-shadow:0 2px 8px rgba(0,0,0,0.15);">
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

    <!-- Nom + badge -->
    <div class="text-center mb-3">
        <div class="flex items-center justify-center gap-2 flex-wrap">
            <h1 class="text-2xl font-bold text-black" style="font-family:'Plus Jakarta Sans',sans-serif;">
                {{ $profile->user->name }}
            </h1>
            @if($profile->is_verified)
            <div class="w-6 h-6 rounded-full flex items-center justify-center shrink-0" style="background:#1877F2;" title="Certifié">
                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414L8.414 15 3.293 9.879a1 1 0 011.414-1.414L8.414 12.172l6.879-6.879a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </div>
            @endif
        </div>

        <!-- Localisation avec icône -->
        <div class="flex items-center justify-center gap-1 mt-1">
            <svg class="w-3.5 h-3.5 shrink-0" style="color:#FCB315;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
            </svg>
            <span class="text-sm" style="color:#2b2b2b;">{{ $profile->user->ville }}</span>
        </div>
    </div>

    <!-- Note étoiles -->
    <div class="flex items-center justify-center gap-2 mb-4">
        <div class="flex gap-0.5">
            @for($i = 1; $i <= 5; $i++)
            <span style="color: {{ $i <= round($profile->rating) ? '#FCB315' : '#E5E7EB' }}; font-size:16px;">★</span>
            @endfor
        </div>
        <span class="text-sm font-semibold text-black">{{ $profile->rating }}</span>
        <span class="text-xs" style="color:#2b2b2b;">({{ $profile->reviews_count }} avis)</span>
    </div>

    <!-- Tarif -->
    <div class="text-center mb-4">
        <p class="text-2xl font-bold text-black" style="font-family:'Plus Jakarta Sans',sans-serif;">
            {{ number_format($profile->hourly_rate, 0, ',', ' ') }} FCFA / H
        </p>
        @if($profile->first_course_free)
        <div class="mt-2 space-y-1">
            <span class="inline-block text-xs font-semibold text-green-700 bg-green-100 px-3 py-1 rounded-full">
                ✓ 1er cours offert
            </span>
            <p class="text-xs" style="color:#2b2b2b;">✓ Annulation gratuite 24h à l'avance</p>
        </div>
        @endif
    </div>

    <!-- Lieu du cours -->
    @if($profile->lieu_cours && count($profile->lieu_cours) > 0)
    <div class="mb-4">
        <p class="text-xs font-semibold mb-2 text-center" style="color:#2b2b2b;">
            @foreach($profile->lieu_cours as $lieu)
            {{ $lieu === 'chez_prof' ? '🏠 Chez le prof' : ($lieu === 'chez_eleve' ? '📍 Chez l\'élève' : '💻 Webcam') }}
            @if(!$loop->last) · @endif
            @endforeach
        </p>
        @if($profile->zone_deplacement)
        <p class="text-xs text-center" style="color:#2b2b2b;">
            Je peux me déplacer dans un périmètre de {{ $profile->zone_deplacement }}
        </p>
        @endif
    </div>
    @endif

    <!-- Détails -->
    <div class="text-sm mb-5">
        <div class="flex items-center justify-between py-2 border-b border-gray-100">
            <span style="color:#2b2b2b;">Expérience</span>
            <span class="font-medium text-black">{{ $profile->experience_years }}</span>
        </div>
        <div class="flex items-center justify-between py-2 border-b border-gray-100">
            <span style="color:#2b2b2b;">Cours disponibles</span>
            <span class="font-medium text-black">{{ $profile->courses->count() }}</span>
        </div>
        <div class="flex items-center justify-between py-2">
    <span style="color:#2b2b2b;">⚡ Répond en moyenne</span>
    <span class="font-medium text-black">
        @if($profile->response_time)
            @if($profile->response_time < 60)
                en {{ $profile->response_time }} min
            @elseif($profile->response_time < 1440)
                en {{ $profile->response_time / 60 }}h
            @else
                en 24h
            @endif
        @else
            en 2h
        @endif
    </span>
</div>
    </div>

    <!-- CTA -->
    @auth
    <div style="display:flex; flex-direction:column; gap:8px;">
        <a href="#reserver"
           class="text-center py-2.5 rounded-xl text-black font-semibold text-sm transition hover:opacity-90"
           style="background:#FCB315; display:block;">
            Réserver un cours
        </a>
        <a href="{{ route('messages.show', $profile->user->id) }}"
           class="text-center py-2.5 rounded-xl text-black font-semibold text-sm border-2 border-gray-100 hover:border-yellow-400 transition"
           style="display:block;">
            Contacter
        </a>
    </div>
    @else
    <a href="{{ route('login') }}"
       class="text-center py-2.5 rounded-xl text-black font-semibold text-sm transition hover:opacity-90"
       style="background:#FCB315; display:block;">
        Se connecter pour réserver
    </a>
    @endauth
</div>

        <!-- Colonne droite -->
        <div style="display:flex; flex-direction:column; gap:1.5rem;">

            <!-- À propos du prof -->
            <div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
                <h2 class="font-bold text-black text-lg mb-3" style="font-family:'Plus Jakarta Sans',sans-serif;">
                    À propos de {{ explode(' ', $profile->user->name)[0] }}
                </h2>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $profile->bio }}</p>
            </div>

            <!-- À propos du cours -->
            @if($profile->a_propos_cours)
            <div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
                <h2 class="font-bold text-black text-lg mb-3" style="font-family:'Plus Jakarta Sans',sans-serif;">
                    À propos du cours
                </h2>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $profile->a_propos_cours }}</p>
            </div>
            @endif


            <!-- Parcours académique -->
@php $parcours_list = is_array($profile->parcours_academique) ? $profile->parcours_academique : json_decode($profile->parcours_academique, true) ?? []; @endphp
@if(count($parcours_list) > 0)
<div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
    <h2 class="font-bold text-black text-lg mb-5" style="font-family:'Plus Jakarta Sans',sans-serif;">
        Parcours académique
    </h2>
    <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:1rem;">
        @foreach($parcours_list as $parcours)
        <div class="p-4 rounded-xl border-2 border-gray-100">
            <p class="text-xs mb-2" style="color:#2b2b2b;">{{ $parcours['annees'] }}</p>
            <p class="font-bold text-black text-sm mb-1">{{ $parcours['diplome'] }}</p>
            <p class="text-xs" style="color:#2b2b2b;">{{ $parcours['etablissement'] }}</p>
        </div>
        @endforeach
    </div>
</div>
@endif

            <!-- Vidéo de présentation -->
            @if($profile->video_url)
            <div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
                <h2 class="font-bold text-black text-lg mb-4" style="font-family:'Plus Jakarta Sans',sans-serif;">
                    Vidéo de présentation
                </h2>
                @php
                    preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $profile->video_url, $matches);
                    $videoId = $matches[1] ?? null;
                @endphp
                @if($videoId)
                <div style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden; border-radius:12px;">
                    <iframe
                        src="https://www.youtube.com/embed/{{ $videoId }}"
                        style="position:absolute; top:0; left:0; width:100%; height:100%; border:none;"
                        allowfullscreen>
                    </iframe>
                </div>
                @endif
            </div>
            @endif

            <!-- Cours proposés -->
<div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
    <h2 class="font-bold text-black text-lg mb-4" style="font-family:'Plus Jakarta Sans',sans-serif;">
        Cours proposés
    </h2>
    <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap:1rem;">
        @forelse($profile->courses as $course)
        <div class="p-4 rounded-xl border-2 border-gray-100 hover:border-yellow-300 transition">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:8px;">
                <p class="font-semibold text-black text-sm">{{ $course->title }}</p>
                <div class="flex gap-1 shrink-0 ml-2">
                    @if($course->is_group)
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background:#E8F5E9; color:#2E7D32;">
                        Groupe
                    </span>
                    @endif
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background:#FFF8E7; color:#FCB315;">
                        {{ $course->format }}
                    </span>
                </div>

            </div>
            <p class="text-xs mb-2" style="color:#2b2b2b;">
    {{ $course->category }} · {{ $course->level }}
    @if($course->lieu_cours && count(is_array($course->lieu_cours) ? $course->lieu_cours : json_decode($course->lieu_cours, true) ?? []) > 0)
    @php $lieux = is_array($course->lieu_cours) ? $course->lieu_cours : json_decode($course->lieu_cours, true) ?? []; @endphp
    · @foreach($lieux as $l){{ $l === 'chez_prof' ? '🏠' : ($l === 'chez_eleve' ? '📍' : '💻') }}@endforeach
    @endif
</p>
@if($course->zone_deplacement)
<p class="text-xs mb-2" style="color:#2b2b2b;">{{ $course->zone_deplacement }}</p>
@endif
            @if($course->description)
            <p class="text-xs text-[rgb(43,43,43)] leading-relaxed mb-3">{{ Str::limit($course->description, 80) }}</p>
            @endif
            <div class="pt-2 border-t border-gray-50">
                <p class="font-bold text-black text-sm">{{ number_format($course->price_per_hour, 0, ',', ' ') }} Fcfa/h</p>
            </div>
        </div>
        @empty
        <p class="text-gray-400 text-sm">Aucun cours publié.</p>
        @endforelse
    </div>
</div>

            <!-- Avis des élèves -->
            <div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
                <h2 class="font-bold text-black text-lg mb-4" style="font-family:'Plus Jakarta Sans',sans-serif;">
                    Avis des élèves
                </h2>
                @php
                    $reviews = $profile->reviews()->with('user')->latest()->take(5)->get();
                @endphp
                @forelse($reviews as $review)
                <div class="flex gap-4 py-4 border-b border-gray-50 last:border-0">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold text-black shrink-0" style="background:#FCB315;">
                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <p class="font-semibold text-black text-sm">{{ $review->user->name }}</p>
                            <div class="flex gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                <span style="color: {{ $i <= $review->rating ? '#FCB315' : '#E5E7EB' }}; font-size:12px;">★</span>
                                @endfor
                            </div>
                        </div>
                        @if($review->comment)
                        <p class="text-[rgb(43,43,43)] text-xs leading-relaxed">{{ $review->comment }}</p>
                        @endif
                        <p class="text-xs text-gray-300 mt-1">{{ \Carbon\Carbon::parse($review->created_at)->format('d/m/Y') }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <p class="text-gray-400 text-sm">Aucun avis pour le moment.</p>
                </div>
                @endforelse
            </div>

            <!-- Réservation -->
            @auth
            <div id="reserver" class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
                <h2 class="font-bold text-black text-lg mb-5" style="font-family:'Plus Jakarta Sans',sans-serif;">
                    Réserver un cours
                </h2>
                <form action="{{ route('booking.store', $profile->id) }}" method="POST">
                    @csrf
                    <div style="display:flex; flex-direction:column; gap:1rem;">
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Choisir un cours</label>
                            <select name="course_id" class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50">
                                @foreach($profile->courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }} — {{ number_format($course->price_per_hour, 0, ',', ' ') }} Fcfa/h</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap:1rem;">
                            <div>
                                <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Date et heure</label>
                                <input type="datetime-local" name="scheduled_at"
                                       class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50"/>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Durée</label>
                                <select name="duration_hours" class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50">
                                    <option value="1">1 heure</option>
                                    <option value="2">2 heures</option>
                                    <option value="3">3 heures</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full py-3 rounded-xl text-black font-semibold transition hover:opacity-90"
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
<section class="py-12 px-6" style="background:#F7F7F7;">
    <div class="max-w-6xl mx-auto">
        <h2 class="font-bold text-black text-xl mb-6" style="font-family:'Plus Jakarta Sans',sans-serif;">
            Professeurs similaires
        </h2>
        <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:1.5rem;">
            @foreach($similaires as $sim)
            <div class="bg-white rounded-2xl p-5 relative" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
                @if($sim->is_verified)
                <div class="absolute top-4 right-4 w-7 h-7 rounded-full flex items-center justify-center" style="background:#1A2B3C;">
                    <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414L8.414 15 3.293 9.879a1 1 0 011.414-1.414L8.414 12.172l6.879-6.879a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                @endif
                <div class="w-14 h-14 rounded-2xl mb-3 flex items-center justify-center text-xl font-bold text-black" style="background:#FCB315;">
                    {{ strtoupper(substr($sim->user->name, 0, 1)) }}
                </div>
                <h3 class="font-semibold text-black text-sm">{{ $sim->user->name }}</h3>
                <p class="text-xs text-gray-400 mb-2">{{ $sim->courses->first()->category ?? 'Cours divers' }}</p>
                <div class="flex items-center gap-1 mb-3">
                    <span style="color:#FCB315;">★</span>
                    <span class="text-sm font-medium text-black">{{ $sim->rating }}</span>
                    <span class="text-xs text-gray-400">({{ $sim->reviews_count }} avis)</span>
                </div>
                <div class="flex items-center justify-between mb-3">
                    <span class="font-bold text-black text-sm">{{ number_format($sim->hourly_rate, 0, ',', ' ') }} Fcfa/h</span>
                    @if($sim->first_course_free)
                    <span class="text-xs font-medium" style="color:#FCB315;">1er cours offert</span>
                    @endif
                </div>
                <a href="{{ route('professeur.profil', $sim->id) }}"
                   class="block w-full text-center py-2 rounded-xl text-black text-xs font-semibold transition hover:opacity-90"
                   style="background:#FCB315;">
                    Voir le profil
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
