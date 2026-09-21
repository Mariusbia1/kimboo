@extends('layouts.dashboard')

@section('title', 'Mes cours')
@section('page-title', 'Mes cours')
@section('page-subtitle', 'Gérez vos cours publiés et ajoutez de nouvelles annonces')

@section('content')

@if(session('success'))
<div class="mb-6 p-4 rounded-2xl bg-green-50 border border-green-200 text-green-800 text-sm flex items-center gap-3 shadow-2xs">
    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    </div>
    <span class="font-medium">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-sm flex items-center gap-3 shadow-2xs">
    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    </div>
    <span class="font-medium">{{ session('error') }}</span>
</div>
@endif

<!-- Disposition en Row côte à côte sur la même ligne (2 colonnes à 50% / 50%) -->
<div class="flex flex-col md:flex-row gap-6 w-full min-w-0 items-start">

    <!-- Colonne 1 : Affichage des cours existants (50%) -->
    <div class="w-full md:w-1/2 min-w-0 bg-white rounded-3xl p-5 sm:p-6 border border-gray-100 shadow-sm flex flex-col">
        <div class="flex items-center justify-between gap-3 pb-4 mb-4 border-b border-gray-100">
            <div>
                <h2 class="text-base sm:text-lg font-extrabold text-gray-900" style="font-family:'Plus Jakarta Sans',sans-serif;">
                    Mes cours enregistrés
                </h2>
                <p class="text-xs text-gray-500 mt-0.5 font-medium">
                    Vos cours publiés et leur statut
                </p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold shrink-0 shadow-2xs" style="background:#FFF8E7; color:#FCB315;">
                {{ $courses->count() }} cours
            </span>
        </div>

        @if($courses->isEmpty())
        <div class="text-center py-12 px-4 rounded-2xl bg-amber-50/40 border border-dashed border-amber-200/80 my-auto">
            <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center mx-auto mb-3 shadow-2xs text-[#FCB315]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.832 5.477 15.418 5 17.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <p class="font-bold text-gray-800 text-sm">Aucun cours pour le moment</p>
            <p class="text-xs text-gray-500 max-w-xs mx-auto mt-1 font-medium">
                Remplissez le formulaire ci-contre pour publier votre première annonce de cours.
            </p>
        </div>
        @else
        <div class="space-y-3 max-h-[calc(100vh-220px)] overflow-y-auto pr-1">
            @foreach($courses as $course)
            <div class="p-4 rounded-2xl border border-gray-100 bg-gray-50/70 hover:bg-amber-50/30 hover:border-amber-200/80 transition-all flex flex-col justify-between gap-3">
                <div>
                    <div class="flex items-start justify-between gap-2 mb-1.5">
                        <h3 class="font-extrabold text-gray-900 text-sm leading-snug line-clamp-1">
                            {{ $course->title }}
                        </h3>
                        @if($course->status === 'pending')
                        <span class="text-[10px] px-2 py-0.5 rounded-full font-bold bg-amber-100 text-amber-800 border border-amber-200 shrink-0">En attente</span>
                        @elseif($course->status === 'approved')
                        <span class="text-[10px] px-2 py-0.5 rounded-full font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 shrink-0">Approuvé</span>
                        @elseif($course->status === 'rejected')
                        <span class="text-[10px] px-2 py-0.5 rounded-full font-bold bg-red-100 text-red-800 border border-red-200 shrink-0">Refusé</span>
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center gap-1.5 text-xs text-gray-500 font-medium mb-2">
                        <span class="px-2 py-0.5 rounded-md bg-white border border-gray-200 text-gray-700 font-semibold text-[11px]">{{ $course->category }}</span>
                        <span>·</span>
                        <span>{{ $course->level }}</span>
                        <span>·</span>
                        <span class="font-semibold text-amber-900">{{ $course->formatted_format }}</span>
                        @if($course->is_group)
                        <span>·</span>
                        <span class="text-blue-700 font-semibold">Groupe ({{ $course->max_students ?? 5 }})</span>
                        @endif
                    </div>

                    @if($course->description)
                    <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed mb-1">
                        {{ $course->description }}
                    </p>
                    @endif

                    @if($course->formatted_zone_deplacement)
                    <p class="text-[11px] text-gray-400">
                        Zone : <span class="text-gray-700 font-semibold">{{ $course->formatted_zone_deplacement }}</span>
                    </p>
                    @endif
                </div>

                <div class="pt-2.5 border-t border-gray-200/60 flex items-center justify-between">
                    <div>
                        <span class="font-extrabold text-sm text-gray-900">{{ number_format($course->price_per_hour, 0, ',', ' ') }} FCFA</span>
                        <span class="text-[11px] text-gray-400">/h</span>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('professeur.edit-cours', $course->id) }}"
                           title="Modifier ce cours"
                           class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-bold text-gray-700 bg-white border border-gray-200 hover:border-yellow-400 hover:bg-amber-50 hover:text-amber-900 transition shadow-2xs">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            <span>Modifier</span>
                        </a>
                        <form method="POST" action="{{ route('professeur.delete-cours', $course->id) }}"
                              onsubmit="return confirm('Êtes-vous certain de vouloir supprimer ce cours ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    title="Supprimer le cours"
                                    class="p-1.5 rounded-xl border border-gray-200 bg-white text-gray-400 hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition shadow-2xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Colonne 2 : Formulaire de création de cours (50%) -->
    <div id="nouveau-cours-form" class="w-full md:w-1/2 min-w-0 bg-white rounded-3xl p-5 sm:p-6 border border-gray-100 shadow-sm scroll-mt-6">
        <div class="flex items-center gap-3 pb-4 mb-4 border-b border-gray-100">
            <div class="w-9 h-9 rounded-2xl flex items-center justify-center shrink-0" style="background:#FFF8E7; color:#FCB315;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-extrabold text-gray-900" style="font-family:'Plus Jakarta Sans',sans-serif;">
                    Publier un nouveau cours
                </h2>
                <p class="text-xs text-gray-500 font-medium">
                    Renseignez les détails du cours pour les élèves
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('professeur.store-cours') }}">
            @csrf

            <div class="flex flex-col gap-3.5">

                <div>
                    <label class="text-xs font-bold text-gray-700 mb-1 block">Titre du cours <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                        class="w-full border-2 border-gray-100 rounded-xl px-3.5 py-2 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 font-medium"
                        placeholder="Ex: Cours de mathématiques lycée & préparation BAC"/>
                    @error('title') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-700 mb-1 block">Description détaillée</label>
                    <textarea name="description" rows="2"
                        class="w-full border-2 border-gray-100 rounded-xl px-3.5 py-2 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 resize-none font-medium"
                        placeholder="Décrivez la méthode pédagogique, le programme et les objectifs...">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="text-xs font-bold text-gray-700 block">Catégorie / Matière <span class="text-red-500">*</span></label>
                            <span class="text-[10px] text-gray-400 font-medium">Saisie libre ou choix</span>
                        </div>
                        <input type="text"
                               name="category"
                               id="category-input"
                               list="categories-list"
                               value="{{ old('category', old('new_category')) }}"
                               required
                               class="w-full border-2 border-gray-100 rounded-xl px-3.5 py-2 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 font-medium placeholder-gray-400"
                               placeholder="Ex: Mathématiques, Piano, Comptabilité, Anglais..." />
                        
                        <datalist id="categories-list">
                            @foreach($categories as $cat)
                            <option value="{{ $cat }}"></option>
                            @endforeach
                        </datalist>

                        @error('category') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        @error('new_category') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror

                        <!-- Suggestions rapides en 1 clic -->
                        <div class="flex flex-wrap gap-1 mt-2">
                            <span class="text-[10px] text-gray-400 font-medium py-0.5 mr-0.5">Exemples :</span>
                            @foreach(['Mathématiques', 'Français', 'Anglais', 'Physique-Chimie', 'Informatique', 'SVT', 'Cuisine', 'Musique'] as $suggestCat)
                            <button type="button"
                                    onclick="document.getElementById('category-input').value = '{{ $suggestCat }}'"
                                    class="text-[10px] font-semibold px-2 py-0.5 rounded-md bg-gray-100 hover:bg-amber-100 text-gray-700 hover:text-amber-900 border border-gray-200/60 transition">
                                {{ $suggestCat }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-700 mb-1 block">Niveau cible <span class="text-red-500">*</span></label>
                        <select name="level" required class="w-full border-2 border-gray-100 rounded-xl px-3.5 py-2 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 font-medium">
                            <option value="">Choisir...</option>
                            @foreach(['Tous niveaux', 'Débutant', 'Intermédiaire', 'Avancé', 'Primaire', 'Collège', 'Lycée', 'Université'] as $level)
                            <option value="{{ $level }}" {{ old('level') == $level ? 'selected' : '' }}>{{ $level }}</option>
                            @endforeach
                        </select>
                        @error('level') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <label class="text-xs font-bold text-gray-700 mb-1 block">Format <span class="text-red-500">*</span></label>
                        <select name="format" required class="w-full border-2 border-gray-100 rounded-xl px-3.5 py-2 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 font-medium">
                            <option value="">Choisir...</option>
                            <option value="Présentiel" {{ old('format') == 'Présentiel' ? 'selected' : '' }}>Présentiel</option>
                            <option value="En ligne" {{ old('format') == 'En ligne' ? 'selected' : '' }}>En ligne</option>
                            <option value="Les deux" {{ old('format') == 'Les deux' ? 'selected' : '' }}>En ligne & Présentiel (les deux)</option>
                        </select>
                        @error('format') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-700 mb-1 block">Prix / heure (FCFA) <span class="text-red-500">*</span></label>
                        <input type="number" name="price_per_hour" value="{{ old('price_per_hour', auth()->user()->teacherProfile->hourly_rate ?? '') }}" required
                            class="w-full border-2 border-gray-100 rounded-xl px-3.5 py-2 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 font-medium"
                            placeholder="Ex: 10000"/>
                        @error('price_per_hour') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Lieu du cours -->
                <div>
                    <label class="text-xs font-bold text-gray-700 mb-1.5 block">Lieu(x) de déroulement</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach([
                            ['value' => 'chez_prof', 'label' => 'Chez le prof', 'type' => 'home'],
                            ['value' => 'chez_eleve', 'label' => 'Chez l\'élève', 'type' => 'location'],
                            ['value' => 'webcam', 'label' => 'En ligne / Webcam', 'type' => 'video'],
                        ] as $lieu)
                        <label class="flex items-center gap-2 px-3 py-2 rounded-xl border-2 cursor-pointer transition lieu-label text-xs font-medium"
                                style="border-color: {{ in_array($lieu['value'], old('lieu_cours', [])) ? '#FCB315' : '#F3F4F6' }}; background: {{ in_array($lieu['value'], old('lieu_cours', [])) ? '#FFF8E7' : '#F9FAFB' }}">
                            <input type="checkbox" name="lieu_cours[]" value="{{ $lieu['value'] }}"
                                {{ in_array($lieu['value'], old('lieu_cours', [])) ? 'checked' : '' }}
                                class="hidden lieu-checkbox">
                            @if($lieu['type'] === 'home')
                            <svg class="w-3.5 h-3.5 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            @elseif($lieu['type'] === 'location')
                            <svg class="w-3.5 h-3.5 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            @else
                            <svg class="w-3.5 h-3.5 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            @endif
                            <span>{{ $lieu['label'] }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Zone de déplacement compacte et épurée -->
                <x-zone-deplacement-input :value="old('zone_deplacement', auth()->user()->teacherProfile->zone_deplacement ?? '')" idPrefix="create_cours_zone" label="Zone d'intervention" />

                <!-- 1ère heure offerte -->
                <div class="rounded-2xl border border-gray-200/90 p-3.5 bg-white hover:border-gray-300 transition-all duration-200 shadow-2xs">
                    <label for="first_course_free" class="flex items-center justify-between gap-3 cursor-pointer select-none">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 bg-green-50 text-green-600 border border-green-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <span class="text-xs font-bold text-gray-900 block truncate">1ère heure offerte</span>
                                <span class="text-[11px] text-gray-500 font-medium block truncate">Offrir la 1ère heure gratuitement (attire plus d'élèves)</span>
                            </div>
                        </div>

                        <div class="relative shrink-0 flex items-center">
                            <input type="checkbox" name="first_course_free" id="first_course_free" value="1"
                                   {{ old('first_course_free') ? 'checked' : '' }}
                                   class="sr-only"
                                   onchange="toggleFreeCourse(this.checked)">
                            <div id="first_free_track"
                                 class="relative w-11 h-6 rounded-full transition-colors duration-200 ease-in-out cursor-pointer"
                                 style="background-color: {{ old('first_course_free') ? '#FCB315' : '#E2E8F0' }};">
                                <div id="first_free_thumb"
                                     class="absolute top-[2px] left-[2px] w-5 h-5 rounded-full bg-white transition-transform duration-200 ease-in-out"
                                     style="box-shadow: 0 1px 3px rgba(0,0,0,0.25); transform: {{ old('first_course_free') ? 'translateX(20px)' : 'translateX(0px)' }};"></div>
                            </div>
                        </div>
                    </label>
                </div>

                <!-- Cours de groupe -->
                <div class="rounded-2xl border border-gray-200/90 p-3.5 bg-white hover:border-gray-300 transition-all duration-200 shadow-2xs">
                    <label for="is_group" class="flex items-center justify-between gap-3 cursor-pointer select-none">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 bg-blue-50 text-blue-600 border border-blue-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <span class="text-xs font-bold text-gray-900 block truncate">Cours de groupe</span>
                                <span class="text-[11px] text-gray-500 font-medium block truncate">Permettre les inscriptions collectives à plusieurs élèves</span>
                            </div>
                        </div>

                        <!-- Toggle switch sleek iOS style -->
                        <div class="relative shrink-0 flex items-center">
                            <input type="checkbox" name="is_group" id="is_group" value="1"
                                   {{ old('is_group') ? 'checked' : '' }}
                                   class="sr-only"
                                   onchange="toggleGroupOptions(this.checked)">
                            <div id="is_group_track"
                                 class="relative w-11 h-6 rounded-full transition-colors duration-200 ease-in-out cursor-pointer"
                                 style="background-color: {{ old('is_group') ? '#FCB315' : '#E2E8F0' }};">
                                <div id="is_group_thumb"
                                     class="absolute top-[2px] left-[2px] w-5 h-5 rounded-full bg-white transition-transform duration-200 ease-in-out"
                                     style="box-shadow: 0 1px 3px rgba(0,0,0,0.25); transform: {{ old('is_group') ? 'translateX(20px)' : 'translateX(0px)' }};"></div>
                            </div>
                        </div>
                    </label>

                    <div id="group-options" class="mt-3 pt-3 border-t border-gray-100 {{ old('is_group') ? '' : 'hidden' }}">
                        <label class="text-[11px] font-bold text-gray-700 mb-1 block">Nombre maximum d'élèves par groupe</label>
                        <input type="number" name="max_students" value="{{ old('max_students', 5) }}"
                            min="2" max="50"
                            class="w-full border border-gray-200 rounded-xl px-3 py-1.5 text-xs outline-none focus:border-yellow-400 transition bg-gray-50 font-medium"
                            placeholder="Ex: 5"/>
                    </div>
                </div>

                <div class="flex gap-2.5 mt-2">
                    <button type="submit"
                        class="flex-1 py-2.5 px-5 rounded-full text-black font-bold transition hover:opacity-90 shadow-sm flex items-center justify-center gap-2 text-xs"
                        style="background:#FCB315;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Publier ce cours</span>
                    </button>
                    <a href="{{ route('professeur.dashboard') }}"
                       class="px-4 py-2.5 rounded-full font-semibold border-2 border-gray-100 hover:border-gray-300 transition text-center text-gray-700 bg-gray-50 text-xs">
                        Annuler
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function toggleNewCategory(val) {
    const wrapper = document.getElementById('new-category-wrapper');
    if (wrapper) {
        wrapper.classList.toggle('hidden', val !== '__new__');
        if (val === '__new__') {
            const input = wrapper.querySelector('input');
            if (input) input.focus();
        }
    }
}

function toggleFreeCourse(checked) {
    const track = document.getElementById('first_free_track');
    const thumb = document.getElementById('first_free_thumb');
    if (track) track.style.backgroundColor = checked ? '#FCB315' : '#E2E8F0';
    if (thumb) thumb.style.transform = checked ? 'translateX(20px)' : 'translateX(0px)';
}

function toggleGroupOptions(checked) {
    const groupOpts = document.getElementById('group-options');
    const track = document.getElementById('is_group_track');
    const thumb = document.getElementById('is_group_thumb');
    
    if (groupOpts) groupOpts.classList.toggle('hidden', !checked);
    if (track) track.style.backgroundColor = checked ? '#FCB315' : '#E2E8F0';
    if (thumb) thumb.style.transform = checked ? 'translateX(20px)' : 'translateX(0px)';
}

document.querySelectorAll('.lieu-label').forEach(label => {
    label.addEventListener('click', function() {
        const checkbox = this.querySelector('.lieu-checkbox');
        setTimeout(() => {
            if (checkbox.checked) {
                this.style.borderColor = '#FCB315';
                this.style.background = '#FFF8E7';
            } else {
                this.style.borderColor = '#F3F4F6';
                this.style.background = '#F9FAFB';
            }
        }, 10);
    });
});
</script>

@endsection
