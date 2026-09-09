@extends('layouts.dashboard')

@section('title', 'Modifier mon profil')
@section('page-title', 'Mon profil')
@section('page-subtitle', 'Modifiez vos inations')

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

<div class="grid grid-cols-1 gap-6 xl:grid-cols-2">

    <!-- Formulaire -->
    <div class="flex min-w-0 flex-col gap-6">

        <!-- Infos générales -->
        <div class="bg-white rounded-2xl p-5 sm:p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
            <h2 class="font-bold text-black text-lg mb-5" style="font-family:'Plus Jakarta Sans',sans-serif;">
                Informations générales
            </h2>
            <form method="POST" action="{{ route('professeur.update-profil') }}"  enctype="multipart/form-data">
                @csrf
                <!-- Photo de profil -->
                <div class="flex flex-col gap-4 mb-2 sm:flex-row sm:items-center sm:gap-5">
                    <!-- Avatar actuel -->
                    <div class="shrink-0 relative w-20 h-20">
                        <div id="avatar-placeholder" class="w-20 h-20 rounded-2xl overflow-hidden shadow-sm {{ $user->avatar ? 'hidden' : '' }}">
                            <x-avatar :user="$user" full="true" rounded="2xl"/>
                        </div>
                        <img src="{{ $user->avatar ? Storage::url($user->avatar) : '' }}"
                             alt="Photo de profil"
                             class="w-20 h-20 rounded-2xl object-cover object-top {{ $user->avatar ? '' : 'hidden' }}"
                             style="object-position: center top;"
                             id="avatar-preview"
                             onerror="this.classList.add('hidden'); document.getElementById('avatar-placeholder')?.classList.remove('hidden');"/>
                    </div>

                    <!-- Upload -->
                    <div class="flex-1 min-w-0">
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Photo de profil</label>
                        <label for="avatar-input" class="inline-flex max-w-full items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-dashed border-gray-200 cursor-pointer hover:border-yellow-400 transition bg-gray-50">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-sm text-[rgb(43,43,43)]">Choisir une photo</span>
                        </label>
                        <input type="file" name="avatar" id="avatar-input" accept="image/*" class="hidden"/>
                        <p class="text-sm text-gray-400 mt-1">JPG, PNG ou WEBP · Max 2MB</p>
                    </div>
                </div>
                <div style="display:flex; flex-direction:column; gap:1.25rem;">

                    <!-- Nom complet -->
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Nom complet <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 font-medium"
                            placeholder="Ex: Kouamé Jean"/>
                        @error('name') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <!-- À propos de vous -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-sm font-semibold text-gray-700 block">À propos de vous (parcours, diplômes) <span class="text-red-500">*</span></label>
                            <span class="text-xs text-gray-400">Obligatoire</span>
                        </div>
                        <textarea name="bio" rows="4" required
                            class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 resize-none font-medium"
                            placeholder="Présentez-vous en quelques lignes : votre formation, vos compétences, vos diplômes et vos passions...">{{ old('bio', $profile->bio) }}</textarea>
                        @error('bio') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <!-- À propos du cours -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-sm font-semibold text-gray-700 block">À propos du cours (méthodologie, approche) <span class="text-red-500">*</span></label>
                            <span class="text-xs text-gray-400">Obligatoire</span>
                        </div>
                        <textarea name="a_propos_cours" rows="3" required
                            class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 resize-none font-medium"
                            placeholder="Décrivez votre pédagogie, comment se déroule une séance type, votre méthode d'évaluation et de suivi...">{{ old('a_propos_cours', $profile->a_propos_cours) }}</textarea>
                        @error('a_propos_cours') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Années d'expérience <span class="text-red-500">*</span></label>
                            <input type="text" name="experience_years" value="{{ old('experience_years', $profile->experience_years) }}" required
                                class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 font-medium"
                                placeholder="Ex: 5 ans d'expérience"/>
                            @error('experience_years') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Ville de résidence <span class="text-red-500">*</span></label>
                            <input type="text" name="ville" value="{{ old('ville', $user->ville) }}" required
                                class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 font-medium"
                                placeholder="Ex: Abidjan, Cocody, Bouaké..."/>
                            @error('ville') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Numéro de téléphone <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required
                                class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 font-medium"
                                placeholder="Ex: 07 00 00 00 00"/>
                            @error('phone') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Zone de déplacement -->
                    <x-zone-deplacement-input :value="$profile->zone_deplacement" idPrefix="profil_zone" />

                    <!-- Vidéo -->
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Lien vidéo de présentation (YouTube)</label>
                        <input type="url" name="video_url" value="{{ old('video_url', $profile->video_url) }}"
                            class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 font-medium"
                            placeholder="https://youtube.com/watch?v=..."/>
                    </div>

                    <!-- Parcours académique -->
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-3 block">Parcours académique & Diplômes</label>
                        <div id="parcours-container" style="display:flex; flex-direction:column; gap:0.75rem;">
                            @php $parcours_list = is_array($profile->parcours_academique) ? $profile->parcours_academique : json_decode($profile->parcours_academique, true) ?? []; @endphp
                            @if(count($parcours_list) > 0)
                                @foreach($parcours_list as $index => $parcours)
                                <div class="parcours-item grid grid-cols-1 gap-3 rounded-xl border-2 border-gray-100 p-4 bg-gray-50 sm:grid-cols-[1fr_1fr] xl:grid-cols-[1fr_1fr_1fr_auto] xl:items-center">
                                    <input type="text" name="parcours_academique[{{ $index }}][annees]"
                                        value="{{ $parcours['annees'] ?? '' }}"
                                        placeholder="Ex: 2018 - 2020"
                                        class="w-full border-2 border-gray-100 rounded-xl px-3 py-2 text-sm outline-none focus:border-yellow-400 bg-white"/>
                                    <input type="text" name="parcours_academique[{{ $index }}][diplome]"
                                        value="{{ $parcours['diplome'] ?? '' }}"
                                        placeholder="Ex: Master en Physique"
                                        class="w-full border-2 border-gray-100 rounded-xl px-3 py-2 text-sm outline-none focus:border-yellow-400 bg-white"/>
                                    <input type="text" name="parcours_academique[{{ $index }}][etablissement]"
                                        value="{{ $parcours['etablissement'] ?? '' }}"
                                        placeholder="Ex: Université de..."
                                        class="w-full border-2 border-gray-100 rounded-xl px-3 py-2 text-sm outline-none focus:border-yellow-400 bg-white"/>
                                    <button type="button" onclick="this.closest('.parcours-item').remove()"
                                        class="w-full h-10 rounded-lg flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-100 transition sm:col-span-2 xl:col-span-1 xl:w-8 xl:h-8 shrink-0" title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" onclick="addParcours()"
                            class="mt-3 text-sm px-4 py-2.5 rounded-xl border-2 border-dashed border-gray-200 hover:border-yellow-400 transition w-full font-semibold text-gray-700 bg-gray-50/50">
                            + Ajouter un diplôme / certification
                        </button>
                    </div>

                    <!-- Temps de réponse -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-sm font-semibold text-gray-700 block">Temps de réponse moyen <span class="text-red-500">*</span></label>
                            <span class="text-xs text-gray-400">Obligatoire</span>
                        </div>
                        <select name="response_time" required class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 font-medium text-gray-800">
                            <option value="">Choisir un délai...</option>
                            <option value="15" {{ old('response_time', $profile->response_time) == 15 ? 'selected' : '' }}>15 minutes (Très rapide)</option>
                            <option value="30" {{ old('response_time', $profile->response_time) == 30 ? 'selected' : '' }}>30 minutes (Rapide)</option>
                            <option value="60" {{ old('response_time', $profile->response_time) == 60 ? 'selected' : '' }}>1 heure</option>
                            <option value="120" {{ old('response_time', $profile->response_time) == 120 ? 'selected' : '' }}>2 heures</option>
                            <option value="240" {{ old('response_time', $profile->response_time) == 240 ? 'selected' : '' }}>4 heures</option>
                            <option value="720" {{ old('response_time', $profile->response_time) == 720 ? 'selected' : '' }}>12 heures</option>
                            <option value="1440" {{ old('response_time', $profile->response_time) == 1440 ? 'selected' : '' }}>24 heures (1 jour)</option>
                        </select>
                        @error('response_time') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        <p class="text-xs text-gray-400 mt-1">Indiquez aux élèves à quelle rapidité vous répondez généralement aux demandes.</p>
                    </div>

                    <button type="submit"
                        class="w-full py-3.5 rounded-full text-black font-bold text-sm transition hover:opacity-90 shadow-md flex items-center justify-center gap-2"
                        style="background:#FCB315;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Enregistrer les modifications</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Aperçu -->
    <div class="bg-white rounded-2xl p-5 sm:p-6 xl:sticky xl:top-24 min-w-0" style="box-shadow:0 4px 12px rgba(0,0,0,0.06); height:fit-content;">
        <h2 class="font-bold text-black text-lg mb-5" style="font-family:'Plus Jakarta Sans',sans-serif;">
            Aperçu de votre profil
        </h2>

        <div class="text-center mb-5">
            <div class="w-20 h-20 rounded-2xl mx-auto mb-3 overflow-hidden shadow-sm">
                <x-avatar :user="$user" full="true" rounded="2xl"/>
            </div>
            <h3 class="font-bold text-black">{{ $user->name }}</h3>
            <p class="text-sm text-gray-400">{{ $user->ville }}</p>
            @if($profile->is_verified)
            <div class="inline-flex items-center gap-1.5 mt-2">
                <x-verified-badge :size="22" />
                <span class="text-xs font-semibold" style="color:#1877F2;">Profil certifié</span>
            </div>
            @endif
        </div>

        @php
            $displayRate = $profile->courses->min('price_per_hour') ?? $profile->hourly_rate;
        @endphp
        @if($displayRate && $displayRate > 0)
        <div class="rounded-xl p-4 text-center mb-4" style="background:#ffffff; border:2px solid #f0f0f0;">
            <p class="text-xs text-gray-400 font-semibold mb-1 uppercase tracking-wider">Tarif à partir de</p>
            <p class="text-3xl sm:text-4xl font-bold text-black break-words" style="font-family:'Plus Jakarta Sans',sans-serif;">{{ number_format($displayRate, 0, ',', ' ') }} Fcfa</p>
            <p class="text-sm text-gray-400 mt-0.5">par heure</p>
        </div>
        @endif

        <div class="text-sm space-y-2 mb-5">
            <div class="flex justify-between py-2 border-b border-gray-50">
                <span class="text-gray-400">Note</span>
                <span class="font-medium flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-[#FCB315] fill-current" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    {{ $profile->rating }}
                </span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-50">
                <span class="text-gray-400">Avis</span>
                <span class="font-medium">{{ $profile->reviews_count }}</span>
            </div>
            <div class="flex justify-between py-2">
                <span class="text-gray-400">Cours publiés</span>
                <span class="font-medium">{{ $profile->courses->count() }}</span>
            </div>
        </div>

        <a href="{{ route('professeur.profil', $profile->id) }}"
           target="_blank"
           class="block w-full text-center py-2.5 rounded-xl font-semibold text-sm border-2 border-gray-100 hover:border-yellow-400 transition">
            Voir mon profil public
        </a>
    </div>
</div>

<script>
// Prévisualisation de la photo
document.getElementById('avatar-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('avatar-preview');
        const placeholder = document.getElementById('avatar-placeholder');

        preview.src = e.target.result;
        preview.classList.remove('hidden');
        if (placeholder) placeholder.classList.add('hidden');
    }
    reader.readAsDataURL(file);
});

let parcoursCount = {{ count($parcours_list) }};

function addParcours() {
    const container = document.getElementById('parcours-container');
    const index = parcoursCount++;
    const div = document.createElement('div');
    div.className = 'parcours-item grid grid-cols-1 gap-3 rounded-xl border-2 border-gray-100 p-4 bg-gray-50 sm:grid-cols-[1fr_1fr] xl:grid-cols-[1fr_1fr_1fr_auto] xl:items-center';

    const inputAnnees = document.createElement('input');
    inputAnnees.type = 'text';
    inputAnnees.name = `parcours_academique[${index}][annees]`;
    inputAnnees.placeholder = 'Ex: 2018 - 2020';
    inputAnnees.className = 'w-full border-2 border-gray-100 rounded-xl px-3 py-2 text-sm outline-none focus:border-yellow-400 bg-white';

    const inputDiplome = document.createElement('input');
    inputDiplome.type = 'text';
    inputDiplome.name = `parcours_academique[${index}][diplome]`;
    inputDiplome.placeholder = 'Ex: Master en Physique';
    inputDiplome.className = 'w-full border-2 border-gray-100 rounded-xl px-3 py-2 text-sm outline-none focus:border-yellow-400 bg-white';

    const inputEtab = document.createElement('input');
    inputEtab.type = 'text';
    inputEtab.name = `parcours_academique[${index}][etablissement]`;
    inputEtab.placeholder = 'Ex: Université de...';
    inputEtab.className = 'w-full border-2 border-gray-100 rounded-xl px-3 py-2 text-sm outline-none focus:border-yellow-400 bg-white';

    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'w-full h-10 rounded-lg flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-100 transition sm:col-span-2 xl:col-span-1 xl:w-8 xl:h-8 shrink-0';
    btn.title = 'Supprimer';
    btn.onclick = function() { div.remove(); };

    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('class', 'w-4 h-4');
    svg.setAttribute('fill', 'none');
    svg.setAttribute('stroke', 'currentColor');
    svg.setAttribute('viewBox', '0 0 24 24');
    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttribute('stroke-linecap', 'round');
    path.setAttribute('stroke-linejoin', 'round');
    path.setAttribute('stroke-width', '2');
    path.setAttribute('d', 'M6 18L18 6M6 6l12 12');
    svg.appendChild(path);
    btn.appendChild(svg);

    div.appendChild(inputAnnees);
    div.appendChild(inputDiplome);
    div.appendChild(inputEtab);
    div.appendChild(btn);

    container.appendChild(div);
}
</script>

@endsection
