@extends('layouts.dashboard')

@section('title', 'Ajouter un cours')
@section('page-title', 'Ajouter un cours')
@section('page-subtitle', 'Publiez un nouveau cours sur Kimboo')

@section('content')

@if(session('success'))
<div class="mb-6 px-4 py-3 rounded-xl text-sm font-medium text-green-700 bg-green-100">
    ✓ {{ session('success') }}
</div>
@endif

<div class="max-w-2xl">
    <div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
        <form method="POST" action="{{ route('professeur.store-cours') }}">
            @csrf

            <div style="display:flex; flex-direction:column; gap:1rem;">

                <div>
                    <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Titre du cours</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                        class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50"
                        placeholder="Ex: Cours de mathématiques lycée"/>
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 resize-none"
                        placeholder="Décrivez le contenu du cours...">{{ old('description') }}</textarea>
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Catégorie</label>
                        <select name="category" class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50">
                            <option value="">Choisir...</option>
                            @foreach(['Mathématiques', 'Cuisine', 'Sport', 'Langues', 'Musique', 'Informatique', 'Sciences', 'Arts'] as $cat)
                            <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Niveau</label>
                        <select name="level" class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50">
                            <option value="">Choisir...</option>
                            @foreach(['Tous niveaux', 'Débutant', 'Intermédiaire', 'Avancé', 'Primaire', 'Collège', 'Lycée', 'Université'] as $level)
                            <option value="{{ $level }}" {{ old('level') == $level ? 'selected' : '' }}>{{ $level }}</option>
                            @endforeach
                        </select>
                        @error('level') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Format</label>
                        <select name="format" class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50">
                            <option value="">Choisir...</option>
                            <option value="Présentiel" {{ old('format') == 'Présentiel' ? 'selected' : '' }}>Présentiel</option>
                            <option value="En ligne" {{ old('format') == 'En ligne' ? 'selected' : '' }}>En ligne</option>
                            <option value="Les deux" {{ old('format') == 'Les deux' ? 'selected' : '' }}>Les deux</option>
                        </select>
                        @error('format') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Prix par heure (Fcfa)</label>
                        <input type="number" name="price_per_hour" value="{{ old('price_per_hour') }}"
                            class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50"
                            placeholder="Ex: 10000"/>
                        @error('price_per_hour') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Cours de groupe -->
                <div class="rounded-2xl border-2 border-gray-100 p-5" style="background:#F9FAFB;">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Cours de groupe</p>
                            <p class="text-xs text-gray-400 mt-0.5">Permettre à plusieurs élèves de rejoindre ce cours</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_group" id="is_group" value="1"
                                   {{ old('is_group') ? 'checked' : '' }}
                                   class="sr-only peer"
                                   onchange="toggleGroupOptions(this.checked)">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-400"></div>
                        </label>
                    </div>

                    <!-- Options groupe -->
                    <div id="group-options" class="{{ old('is_group') ? '' : 'hidden' }}">
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Nombre maximum d'élèves</label>
                        <input type="number" name="max_students" value="{{ old('max_students', 5) }}"
                            min="2" max="50"
                            class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-white"
                            placeholder="Ex: 5"/>
                        @error('max_students') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div style="display:flex; gap:0.75rem; margin-top:0.5rem;">
                    <button type="submit"
                        class="flex-1 py-3 rounded-xl text-black font-semibold transition hover:opacity-90"
                        style="background:#FCB315;">
                        Publier le cours
                    </button>
                    <a href="{{ route('professeur.dashboard') }}"
                       class="flex-1 py-3 rounded-xl text-gray-600 font-semibold border-2 border-gray-100 hover:border-gray-300 transition text-center">
                        Annuler
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function toggleGroupOptions(checked) {
    const options = document.getElementById('group-options');
    if (checked) {
        options.classList.remove('hidden');
    } else {
        options.classList.add('hidden');
    }
}
</script>

@endsection
