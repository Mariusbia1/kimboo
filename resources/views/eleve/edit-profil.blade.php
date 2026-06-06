@extends('layouts.dashboard')

@section('title', 'Mon profil')
@section('page-title', 'Mon profil')
@section('page-subtitle', 'Gérez vos informations personnelles')

@section('content')

@if(session('success'))
<div class="mb-6 px-4 py-3 rounded-xl text-sm font-medium text-green-700 bg-green-100 flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="mb-6 px-4 py-3 rounded-xl text-sm font-medium text-red-700 bg-red-100">
    @foreach($errors->all() as $error)
    <p>{{ $error }}</p>
    @endforeach
</div>
@endif

<form method="POST" action="{{ route('eleve.update-profil') }}" enctype="multipart/form-data">
@csrf

<div class="grid grid-cols-1 gap-6 lg:grid-cols-[300px_minmax(0,1fr)] lg:items-start">

    {{-- Carte photo --}}
    <div class="bg-white rounded-2xl p-5 sm:p-6 text-center min-w-0" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">

        {{-- Avatar preview --}}
        <div class="relative inline-block mb-4">
            <div id="avatar-preview"
                 class="w-28 h-28 rounded-2xl overflow-hidden mx-auto flex items-center justify-center text-4xl font-bold text-black"
                 style="{{ $user->avatar ? '' : 'background:#FCB315;' }}">
                @if($user->avatar)
                <img src="{{ Storage::url($user->avatar) }}"
                     id="avatar-img"
                     class="w-full h-full object-cover"/>
                @else
                <span id="avatar-initiale">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                @endif
            </div>

            {{-- Bouton changer photo --}}
            <label for="avatar"
                   class="absolute -bottom-2 -right-2 w-8 h-8 rounded-xl flex items-center justify-center cursor-pointer transition hover:opacity-90"
                   style="background:#FCB315;">
                <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </label>
            <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(this)"/>
        </div>

        <p class="font-semibold text-black">{{ $user->name }}</p>
        <p class="text-sm text-gray-400 mt-1">{{ $user->email }}</p>
        <span class="inline-block mt-2 px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600 capitalize">
            {{ $user->role }}
        </span>

        <p class="text-sm text-gray-400 mt-4">Formats acceptés : JPG, PNG. Max 2 Mo.</p>
    </div>

    {{-- Formulaire infos --}}
    <div class="space-y-4 min-w-0">

        {{-- Infos personnelles --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
            <h2 class="font-semibold text-black mb-5 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Informations personnelles
            </h2>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">Nom complet</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50"
                        placeholder="Votre nom complet"/>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">Adresse email</label>
                    <input type="email" value="{{ $user->email }}" disabled
                        class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm bg-gray-100 text-gray-400 cursor-not-allowed"/>
                    <p class="text-sm text-gray-400 mt-1">L'email ne peut pas être modifié.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">Téléphone</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                            class="w-full border-2 border-gray-100 rounded-xl pl-10 pr-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50"
                            placeholder="+225 07 00 00 00 00"/>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">Ville</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <input type="text" name="ville" value="{{ old('ville', $user->ville) }}"
                            class="w-full border-2 border-gray-100 rounded-xl pl-10 pr-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50"
                            placeholder="Abidjan, Cotonou..."/>
                    </div>
                </div>

            </div>
        </div>

        {{-- Sécurité --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
            <h2 class="font-semibold text-black mb-1 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Sécurité
            </h2>
            <p class="text-sm text-gray-400 mb-5">Laissez vide si vous ne souhaitez pas changer de mot de passe.</p>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">Nouveau mot de passe</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input type="password" name="password"
                            class="w-full border-2 border-gray-100 rounded-xl pl-10 pr-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50"
                            placeholder="Min. 8 caractères"/>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">Confirmer le mot de passe</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input type="password" name="password_confirmation"
                            class="w-full border-2 border-gray-100 rounded-xl pl-10 pr-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50"
                            placeholder="Répétez le mot de passe"/>
                    </div>
                </div>

            </div>
        </div>

        {{-- Mode de paiement --}}
        <div class="bg-white rounded-2xl p-5 sm:p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
            <h2 class="font-semibold text-black mb-1 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                Mode de paiement
            </h2>
            <p class="text-sm text-gray-400 mb-5">
                Choisissez votre moyen de paiement mobile pour les réservations (Wave ou Orange Money).
            </p>

            <div class="grid gap-3 sm:grid-cols-2">
                @foreach(\App\Models\User::PAYMENT_METHODS as $value => $label)
                <label class="flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer transition
                    {{ old('payment_method', $user->payment_method) === $value ? 'border-yellow-400 bg-yellow-50' : 'border-gray-100 hover:border-gray-200' }}">
                    <input type="radio" name="payment_method" value="{{ $value }}"
                        {{ old('payment_method', $user->payment_method) === $value ? 'checked' : '' }}
                        class="w-4 h-4 accent-yellow-500"/>
                    <div>
                        <p class="font-semibold text-black text-sm">{{ $label }}</p>
                        <p class="text-sm text-gray-400 mt-0.5">
                            @if($value === 'wave')
                            Paiement via l'application Wave
                            @else
                            Paiement via Orange Money
                            @endif
                        </p>
                    </div>
                </label>
                @endforeach
            </div>

            @error('payment_method')
            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror

            @if($user->phone)
            <p class="text-sm text-gray-500 mt-4">
                Numéro utilisé pour le paiement : <span class="font-medium text-black">{{ $user->phone }}</span>
            </p>
            @else
            <p class="text-sm text-amber-700 bg-amber-50 rounded-xl px-4 py-2.5 mt-4">
                Ajoutez votre numéro de téléphone ci-dessus pour finaliser vos paiements mobiles.
            </p>
            @endif
        </div>

        {{-- Bouton sauvegarder --}}
        <div class="flex justify-stretch sm:justify-end">
            <button type="submit"
                class="w-full sm:w-auto justify-center px-8 py-3 rounded-xl text-black font-semibold text-sm transition hover:opacity-90 flex items-center gap-2"
                style="background:#FCB315;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Sauvegarder les modifications
            </button>
        </div>

    </div>
</div>

</form>

{{-- Suppression du compte --}}
<div class="mt-6 bg-white rounded-2xl p-5 sm:p-6 border-2 border-red-100" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
    <h2 class="font-semibold text-red-600 mb-1 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        Zone de danger
    </h2>
    <p class="text-sm text-gray-500 mb-5">
        La suppression de votre compte est définitive. Vos réservations, favoris et messages seront effacés.
    </p>

    <form method="POST" action="{{ route('eleve.delete-account') }}"
          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.');">
        @csrf
        @method('DELETE')

        <div class="max-w-md">
            <label class="block text-sm font-medium text-gray-600 mb-1.5">Confirmez avec votre mot de passe</label>
            <input type="password" name="password" required
                class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-red-300 transition bg-gray-50"
                placeholder="Votre mot de passe actuel"/>
            @error('password')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="mt-4 w-full sm:w-auto justify-center px-6 py-2.5 rounded-xl text-white font-semibold text-sm transition hover:opacity-90 bg-red-500 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Supprimer mon compte
        </button>
    </form>
</div>

@endsection

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            const initiale = document.getElementById('avatar-initiale');
            let img = document.getElementById('avatar-img');

            if (!img) {
                img = document.createElement('img');
                img.id = 'avatar-img';
                img.className = 'w-full h-full object-cover';
                preview.innerHTML = '';
                preview.appendChild(img);
                preview.style.background = '';
            }

            img.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
