@extends('layouts.dashboard')

@section('title', 'Modifier le cours')
@section('page-title', 'Modifier le cours')
@section('page-subtitle', 'Mettez à jour les informations de votre annonce')

@section('content')

@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm flex items-center gap-2">
    <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    <span>{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm flex items-center gap-2">
    <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    <span>{{ session('error') }}</span>
</div>
@endif

<div class="w-full min-w-0">
    <div class="bg-white rounded-2xl p-5 sm:p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
        <form method="POST" action="{{ route('professeur.update-cours', $course->id) }}">
            @csrf
            @method('PUT')

            <div style="display:flex; flex-direction:column; gap:1rem;">

                <div>
                    <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Titre du cours</label>
                    <input type="text" name="title" value="{{ old('title', $course->title) }}"
                        class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50"
                        placeholder="Ex: Cours de mathématiques lycée"/>
                    @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 resize-none"
                        placeholder="Décrivez le contenu du cours...">{{ old('description', $course->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-sm font-semibold text-gray-700 block">Catégorie / Matière <span class="text-red-500">*</span></label>
                            <span class="text-xs text-gray-400 font-medium">Saisie libre ou choix</span>
                        </div>
                        <input type="text"
                               name="category"
                               id="category-input"
                               list="categories-list"
                               value="{{ old('category', old('new_category', $course->category)) }}"
                               required
                               class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 font-medium placeholder-gray-400"
                               placeholder="Ex: Mathématiques, Piano, Comptabilité, Anglais..." />
                        
                        <datalist id="categories-list">
                            @foreach($categories as $cat)
                            <option value="{{ $cat }}"></option>
                            @endforeach
                        </datalist>

                        @error('category') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        @error('new_category') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror

                        <!-- Suggestions rapides en 1 clic -->
                        <div class="flex flex-wrap gap-1.5 mt-2">
                            <span class="text-xs text-gray-400 font-medium py-0.5 mr-0.5">Exemples :</span>
                            @foreach(['Mathématiques', 'Français', 'Anglais', 'Physique-Chimie', 'Informatique', 'SVT', 'Cuisine', 'Musique'] as $suggestCat)
                            <button type="button"
                                    onclick="document.getElementById('category-input').value = '{{ $suggestCat }}'"
                                    class="text-xs font-semibold px-2.5 py-0.5 rounded-lg bg-gray-100 hover:bg-amber-100 text-gray-700 hover:text-amber-900 border border-gray-200/60 transition">
                                {{ $suggestCat }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Niveau</label>
                        <select name="level" class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50">
                            <option value="">Choisir...</option>
                            @foreach(['Tous niveaux', 'Débutant', 'Intermédiaire', 'Avancé', 'Primaire', 'Collège', 'Lycée', 'Université'] as $level)
                            <option value="{{ $level }}" {{ old('level', $course->level) == $level ? 'selected' : '' }}>{{ $level }}</option>
                            @endforeach
                        </select>
                        @error('level') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Format</label>
                        <select name="format" class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50">
                            <option value="">Choisir...</option>
                            <option value="Présentiel" {{ old('format', $course->format) == 'Présentiel' ? 'selected' : '' }}>Présentiel</option>
                            <option value="En ligne" {{ old('format', $course->format) == 'En ligne' ? 'selected' : '' }}>En ligne</option>
                            <option value="Les deux" {{ old('format', $course->format) == 'Les deux' ? 'selected' : '' }}>En ligne & Présentiel (les deux)</option>
                        </select>
                        @error('format') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Prix par heure (FCFA)</label>
                        <input type="number" name="price_per_hour" value="{{ old('price_per_hour', $course->price_per_hour) }}"
                            class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50"
                            placeholder="Ex: 10000"/>
                        @error('price_per_hour') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Lieu du cours -->
                <div>
                    <label class="text-sm font-semibold text-gray-700 mb-3 block">Lieu du cours</label>
                    <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                        @php
                            $selectedLieux = old('lieu_cours', is_array($course->lieu_cours) ? $course->lieu_cours : []);
                        @endphp
                        @foreach([
                            ['value' => 'chez_prof', 'label' => 'Chez le professeur', 'type' => 'home'],
                            ['value' => 'chez_eleve', 'label' => 'Chez l\'élève', 'type' => 'location'],
                            ['value' => 'webcam', 'label' => 'En ligne / Webcam', 'type' => 'video'],
                        ] as $lieu)
                        <label class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl border-2 cursor-pointer transition lieu-label"
                                style="border-color: {{ in_array($lieu['value'], $selectedLieux) ? '#FCB315' : '#F3F4F6' }}; background: {{ in_array($lieu['value'], $selectedLieux) ? '#FFF8E7' : '#F9FAFB' }}">
                            <input type="checkbox" name="lieu_cours[]" value="{{ $lieu['value'] }}"
                                {{ in_array($lieu['value'], $selectedLieux) ? 'checked' : '' }}
                                class="hidden lieu-checkbox">
                            @if($lieu['type'] === 'home')
                            <svg class="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            @elseif($lieu['type'] === 'location')
                            <svg class="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            @else
                            <svg class="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            @endif
                            <span class="text-sm font-medium text-gray-700">{{ $lieu['label'] }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Zone de déplacement -->
                <x-zone-deplacement-input :value="$course->zone_deplacement" idPrefix="edit_cours_zone" />

                <!-- 1ère heure offerte -->
                <div class="rounded-2xl border border-gray-200/90 p-4 bg-white hover:border-gray-300 transition-all duration-200 shadow-2xs">
                    <label for="first_course_free" class="flex items-center justify-between gap-3 cursor-pointer select-none">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 bg-green-50 text-green-600 border border-green-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <span class="text-sm font-bold text-gray-900 block truncate">1ère heure offerte</span>
                                <span class="text-xs text-gray-500 font-medium block truncate">Offrir la 1ère heure gratuitement (attire plus d'élèves)</span>
                            </div>
                        </div>

                        <div class="relative shrink-0 flex items-center">
                            <input type="checkbox" name="first_course_free" id="first_course_free" value="1"
                                   {{ old('first_course_free', $course->first_course_free) ? 'checked' : '' }}
                                   class="sr-only"
                                   onchange="toggleFreeCourse(this.checked)">
                            <div id="first_free_track"
                                 class="relative w-11 h-6 rounded-full transition-colors duration-200 ease-in-out cursor-pointer"
                                 style="background-color: {{ old('first_course_free', $course->first_course_free) ? '#FCB315' : '#E2E8F0' }};">
                                <div id="first_free_thumb"
                                     class="absolute top-[2px] left-[2px] w-5 h-5 rounded-full bg-white transition-transform duration-200 ease-in-out"
                                     style="box-shadow: 0 1px 3px rgba(0,0,0,0.25); transform: {{ old('first_course_free', $course->first_course_free) ? 'translateX(20px)' : 'translateX(0px)' }};"></div>
                            </div>
                        </div>
                    </label>
                </div>

                <!-- Cours de groupe -->
                <div class="rounded-2xl border border-gray-200/90 p-4 bg-white hover:border-gray-300 transition-all duration-200 shadow-2xs">
                    <label for="is_group" class="flex items-center justify-between gap-3 cursor-pointer select-none">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 bg-blue-50 text-blue-600 border border-blue-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <span class="text-sm font-bold text-gray-900 block truncate">Cours de groupe</span>
                                <span class="text-xs text-gray-500 font-medium block truncate">Permettre à plusieurs élèves de rejoindre ce cours</span>
                            </div>
                        </div>

                        <!-- Toggle switch sleek iOS style -->
                        <div class="relative shrink-0 flex items-center">
                            <input type="checkbox" name="is_group" id="is_group" value="1"
                                   {{ old('is_group', $course->is_group) ? 'checked' : '' }}
                                   class="sr-only"
                                   onchange="toggleGroupOptions(this.checked)">
                            <div id="is_group_track"
                                 class="relative w-11 h-6 rounded-full transition-colors duration-200 ease-in-out cursor-pointer"
                                 style="background-color: {{ old('is_group', $course->is_group) ? '#FCB315' : '#E2E8F0' }};">
                                <div id="is_group_thumb"
                                     class="absolute top-[2px] left-[2px] w-5 h-5 rounded-full bg-white transition-transform duration-200 ease-in-out"
                                     style="box-shadow: 0 1px 3px rgba(0,0,0,0.25); transform: {{ old('is_group', $course->is_group) ? 'translateX(20px)' : 'translateX(0px)' }};"></div>
                            </div>
                        </div>
                    </label>

                    <div id="group-options" class="mt-3 pt-3 border-t border-gray-100 {{ old('is_group', $course->is_group) ? '' : 'hidden' }}">
                        <label class="text-xs font-bold text-gray-700 mb-1 block">Nombre maximum d'élèves par groupe</label>
                        <input type="number" name="max_students" value="{{ old('max_students', $course->max_students ?? 5) }}"
                            min="2" max="50"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 font-medium"
                            placeholder="Ex: 5"/>
                    </div>
                </div>

                <div class="flex flex-col gap-3 mt-2 sm:flex-row">
                    <button type="submit"
                        class="flex-1 py-3 rounded-full text-black font-semibold transition hover:opacity-90 shadow-md"
                        style="background:#FCB315;">
                        Enregistrer les modifications
                    </button>
                    <a href="{{ route('professeur.dashboard') }}"
                       class="flex-1 py-3 rounded-full font-semibold border-2 border-gray-100 hover:border-gray-300 transition text-center"
                       style="color:#2b2b2b;">
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
