@extends('layouts.dashboard')

@section('title', 'Modifier mon profil')
@section('page-title', 'Mon profil')
@section('page-subtitle', 'Modifiez vos inations')

@section('content')

@if(session('success'))
<div class="mb-6 px-4 py-3 rounded-xl text-sm font-medium text-green-700 bg-green-100">
    ✓ {{ session('success') }}
</div>
@endif

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem;">

    <!-- Formulaire -->
    <div style="display:flex; flex-direction:column; gap:1.5rem;">

        <!-- Infos générales -->
        <div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
            <h2 class="font-bold text-black text-lg mb-5" style="font-family:'Plus Jakarta Sans',sans-serif;">
                Informations générales
            </h2>
            <form method="POST" action="{{ route('professeur.update-profil') }}"  enctype="multipart/form-data">
                @csrf
                <!-- Photo de profil -->
                <div class="flex items-center gap-5 mb-2">
                    <!-- Avatar actuel -->
                    <div class="shrink-0">
                        @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}"
                             alt="Photo de profil"
                             class="w-20 h-20 rounded-2xl object-cover"
                             id="avatar-preview"/>
                        @else
                        <div class="mx-auto mb-3" style="width:fit-content;">
                            <x-avatar :user="$user" size="20" rounded="2xl"/>
                        </div>
                        <img src="" alt="" class="w-20 h-20 rounded-2xl object-cover hidden" id="avatar-preview"/>
                        @endif
                    </div>

                    <!-- Upload -->
                    <div class="flex-1">
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Photo de profil</label>
                        <label for="avatar-input" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-dashed border-gray-200 cursor-pointer hover:border-yellow-400 transition bg-gray-50 w-fit">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-sm text-[rgb(43,43,43)]">Choisir une photo</span>
                        </label>
                        <input type="file" name="avatar" id="avatar-input" accept="image/*" class="hidden"/>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG ou WEBP · Max 2MB</p>
                    </div>
                </div>
                <div style="display:flex; flex-direction:column; gap:1rem;">

                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">À propos de vous (parcours, diplômes)</label>
                        <textarea name="bio" rows="4"
                            class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 resize-none"
                            placeholder="Parlez de votre parcours universitaire, vos diplômes...">{{ old('bio', $profile->bio) }}</textarea>
                        @error('bio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">À propos du cours (méthodes, approche)</label>
                        <textarea name="a_propos_cours" rows="3"
                            class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 resize-none"
                            placeholder="Décrivez vos méthodes d'enseignement...">{{ old('a_propos_cours', $profile->a_propos_cours) }}</textarea>
                    </div>

                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Tarif horaire (Fcfa)</label>
                            <input type="number" name="hourly_rate" value="{{ old('hourly_rate', $profile->hourly_rate) }}"
                                class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50"/>
                            @error('hourly_rate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Années d'expérience</label>
                            <input type="text" name="experience_years" value="{{ old('experience_years', $profile->experience_years) }}"
                                class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50"
                                placeholder="Ex: 5 ans"/>
                            @error('experience_years') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Ville</label>
                            <input type="text" name="ville" value="{{ old('ville', $user->ville) }}"
                                class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50"/>
                            @error('ville') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Téléphone</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50"/>
                        </div>
                    </div>

                    <!-- Lieu du cours -->
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-3 block">Lieu du cours</label>
                        <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
                            @foreach([
                                ['value' => 'chez_prof', 'label' => 'Chez le prof', 'icon' => '🏠'],
                                ['value' => 'chez_eleve', 'label' => 'Chez l\'élève', 'icon' => '📍'],
                                ['value' => 'webcam', 'label' => 'En webcam', 'icon' => '💻'],
                            ] as $lieu)
                            <label class="flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 cursor-pointer transition"
                                   style="border-color: {{ in_array($lieu['value'], old('lieu_cours', $profile->lieu_cours ?? [])) ? '#FCB315' : '#F3F4F6' }}; background: {{ in_array($lieu['value'], old('lieu_cours', $profile->lieu_cours ?? [])) ? '#FFF8E7' : '#F9FAFB' }}">
                                <input type="checkbox" name="lieu_cours[]" value="{{ $lieu['value'] }}"
                                    {{ in_array($lieu['value'], old('lieu_cours', $profile->lieu_cours ?? [])) ? 'checked' : '' }}
                                    class="hidden">
                                <span>{{ $lieu['icon'] }}</span>
                                <span class="text-sm font-medium text-gray-700">{{ $lieu['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Zone de déplacement -->
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Zone de déplacement (km)</label>
                        <input type="text" name="zone_deplacement" value="{{ old('zone_deplacement', $profile->zone_deplacement) }}"
                            class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50"
                            placeholder="Ex: 10 km autour d'Abidjan"/>
                    </div>

                    <!-- Vidéo -->
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Lien vidéo de présentation (YouTube)</label>
                        <input type="url" name="video_url" value="{{ old('video_url', $profile->video_url) }}"
                            class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50"
                            placeholder="https://youtube.com/..."/>
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="first_course_free" id="first_course_free" value="1"
                               {{ $profile->first_course_free ? 'checked' : '' }}
                               class="w-4 h-4 rounded" style="accent-color:#FCB315;">
                        <label for="first_course_free" class="text-sm font-medium text-gray-700">
                            Offrir le premier cours gratuitement
                        </label>
                    </div>
                    <!-- Parcours académique -->
<div>
    <label class="text-sm font-semibold text-gray-700 mb-3 block">Parcours académique</label>
    <div id="parcours-container" style="display:flex; flex-direction:column; gap:0.75rem;">
        @php $parcours_list = is_array($profile->parcours_academique) ? $profile->parcours_academique : json_decode($profile->parcours_academique, true) ?? []; @endphp
@if(count($parcours_list) > 0)
            @foreach($parcours_list as $index => $parcours)
            <div class="parcours-item rounded-xl border-2 border-gray-100 p-4 bg-gray-50" style="display:grid; grid-template-columns: 1fr 1fr 1fr auto; gap:0.75rem; align-items:center;">
                <input type="text" name="parcours_academique[{{ $index }}][annees]"
                    value="{{ $parcours['annees'] ?? '' }}"
                    placeholder="Ex: 2018 - 2020"
                    class="border-2 border-gray-100 rounded-xl px-3 py-2 text-sm outline-none focus:border-yellow-400 bg-white"/>
                <input type="text" name="parcours_academique[{{ $index }}][diplome]"
                    value="{{ $parcours['diplome'] ?? '' }}"
                    placeholder="Ex: Master en Physique"
                    class="border-2 border-gray-100 rounded-xl px-3 py-2 text-sm outline-none focus:border-yellow-400 bg-white"/>
                <input type="text" name="parcours_academique[{{ $index }}][etablissement]"
                    value="{{ $parcours['etablissement'] ?? '' }}"
                    placeholder="Ex: Université de..."
                    class="border-2 border-gray-100 rounded-xl px-3 py-2 text-sm outline-none focus:border-yellow-400 bg-white"/>
                <button type="button" onclick="this.closest('.parcours-item').remove()"
                    class="w-8 h-8 rounded-lg flex items-center justify-center bg-red-100 text-red-500 hover:bg-red-200 transition shrink-0">
                    ✕
                </button>
            </div>
            @endforeach
        @endif
    </div>
    <button type="button" onclick="addParcours()"
        class="mt-3 text-sm px-4 py-2 rounded-xl border-2 border-dashed border-gray-200 hover:border-yellow-400 transition w-full"
        style="color:#2b2b2b;">
        + Ajouter un diplôme
    </button>
</div>

<!-- Temps de réponse -->
<div>
    <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Temps de réponse moyen (minutes)</label>
    <select name="response_time" class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50">
        <option value="">Choisir...</option>
        <option value="30" {{ $profile->response_time == 30 ? 'selected' : '' }}>30 minutes</option>
        <option value="60" {{ $profile->response_time == 60 ? 'selected' : '' }}>1 heure</option>
        <option value="120" {{ $profile->response_time == 120 ? 'selected' : '' }}>2 heures</option>
        <option value="240" {{ $profile->response_time == 240 ? 'selected' : '' }}>4 heures</option>
        <option value="1440" {{ $profile->response_time == 1440 ? 'selected' : '' }}>24 heures</option>
    </select>
</div>
                    <button type="submit"
                        class="w-full py-3 rounded-xl text-black font-semibold transition hover:opacity-90"
                        style="background:#FCB315;">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Aperçu -->
    <div class="bg-white rounded-2xl p-6 sticky top-24" style="box-shadow:0 4px 12px rgba(0,0,0,0.06); height:fit-content;">
        <h2 class="font-bold text-black text-lg mb-5" style="font-family:'Plus Jakarta Sans',sans-serif;">
            Aperçu de votre profil
        </h2>

        <div class="text-center mb-5">
            <div class="w-20 h-20 rounded-2xl mx-auto mb-3 flex items-center justify-center text-3xl font-bold text-black" style="background:#FCB315;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h3 class="font-bold text-black">{{ $user->name }}</h3>
            <p class="text-sm text-gray-400">{{ $user->ville }}</p>
            @if($profile->is_verified)
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold text-white mt-2" style="background:#1A2B3C;">
                ✓ Certifié
            </span>
            @endif
        </div>

        <!-- Lieu du cours -->
        @if($profile->lieu_cours && count($profile->lieu_cours) > 0)
        <div class="mb-4">
            <p class="text-xs font-semibold text-gray-400 mb-2">LIEU DU COURS</p>
            <div class="flex flex-wrap gap-2">
                @foreach($profile->lieu_cours as $lieu)
                <span class="text-xs px-3 py-1 rounded-full font-medium" style="background:#FFF8E7; color:#FCB315;">
                    {{ $lieu === 'chez_prof' ? '🏠 Chez le prof' : ($lieu === 'chez_eleve' ? '📍 Chez l\'élève' : '💻 Webcam') }}
                </span>
                @endforeach
            </div>
        </div>
        @endif

        <div class="rounded-xl p-4 text-center mb-4" style="background:#FFF8E7;">
            <p class="text-2xl font-bold" style="color:#FCB315;">{{ number_format($profile->hourly_rate, 0, ',', ' ') }} Fcfa</p>
            <p class="text-xs text-[rgb(43,43,43)] mt-0.5">par heure</p>
        </div>

        <div class="text-sm space-y-2 mb-5">
            <div class="flex justify-between py-2 border-b border-gray-50">
                <span class="text-gray-400">Note</span>
                <span class="font-medium">★ {{ $profile->rating }}</span>
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

<!-- Script pour les checkboxes lieu -->
<script>
document.querySelectorAll('input[name="lieu_cours[]"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const label = this.closest('label');
        if (this.checked) {
            label.style.borderColor = '#FCB315';
            label.style.background = '#FFF8E7';
        } else {
            label.style.borderColor = '#F3F4F6';
            label.style.background = '#F9FAFB';
        }
    });
});

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
    div.className = 'parcours-item rounded-xl border-2 border-gray-100 p-4 bg-gray-50';
    div.style.cssText = 'display:grid; grid-template-columns: 1fr 1fr 1fr auto; gap:0.75rem; align-items:center;';
    div.innerHTML = `
        <input type="text" name="parcours_academique[${index}][annees]"
            placeholder="Ex: 2018 - 2020"
            class="border-2 border-gray-100 rounded-xl px-3 py-2 text-sm outline-none focus:border-yellow-400 bg-white"/>
        <input type="text" name="parcours_academique[${index}][diplome]"
            placeholder="Ex: Master en Physique"
            class="border-2 border-gray-100 rounded-xl px-3 py-2 text-sm outline-none focus:border-yellow-400 bg-white"/>
        <input type="text" name="parcours_academique[${index}][etablissement]"
            placeholder="Ex: Université de..."
            class="border-2 border-gray-100 rounded-xl px-3 py-2 text-sm outline-none focus:border-yellow-400 bg-white"/>
        <button type="button" onclick="this.closest('.parcours-item').remove()"
            class="w-8 h-8 rounded-lg flex items-center justify-center bg-red-100 text-red-500 hover:bg-red-200 transition shrink-0">
            ✕
        </button>
    `;
    container.appendChild(div);
}
</script>

@endsection
