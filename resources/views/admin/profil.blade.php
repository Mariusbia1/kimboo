@extends('layouts.dashboard')

@section('title', 'Profil Administrateur')
@section('page-title', 'Profil Administrateur')
@section('page-subtitle', 'Gérez les identifiants et informations du compte Assistance Kimboo')

@section('content')

@if(session('success'))
<div class="mb-6 px-4 py-3 rounded-xl text-sm font-medium text-green-700 bg-green-100 flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
</div>
@endif

<div class="max-w-2xl min-w-0">
    <div class="bg-white rounded-2xl p-6 sm:p-8" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">

        {{-- Header profil --}}
        <div class="flex items-center gap-4 mb-8 pb-6 border-b border-gray-100">
            <x-avatar :user="$user" size="16" rounded="2xl" />
            <div>
                <h2 class="text-xl font-bold text-black" style="font-family:'Plus Jakarta Sans',sans-serif;">{{ $user->name }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                        Administrateur Kimboo
                    </span>
                    <span class="text-xs text-gray-400">· {{ $user->email }}</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.profil.update') }}">
            @csrf

            <div class="space-y-5">
                <div>
                    <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Nom du compte / Affichage</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50"
                        placeholder="Ex: Assistance Kimboo"/>
                    <p class="text-xs text-gray-400 mt-1">Ce nom apparaîtra auprès des utilisateurs et dans les échanges.</p>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Adresse email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full border-2 border-gray-100 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-400 transition bg-gray-50"
                        placeholder="admin@kimboo.net"/>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800 mb-1">Sécurité & Mot de passe</h3>
                    <p class="text-xs text-gray-400 mb-4">Laissez ces champs vides si vous ne souhaitez pas modifier votre mot de passe.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-700 mb-1 block">Nouveau mot de passe</label>
                            <input type="password" name="password"
                                class="w-full border-2 border-gray-100 rounded-xl px-4 py-2 text-sm outline-none focus:border-yellow-400 transition bg-gray-50"
                                placeholder="Min. 8 caractères"/>
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-700 mb-1 block">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation"
                                class="w-full border-2 border-gray-100 rounded-xl px-4 py-2 text-sm outline-none focus:border-yellow-400 transition bg-gray-50"
                                placeholder="Répéter le mot de passe"/>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full sm:w-auto px-8 py-3 rounded-full text-black font-semibold transition hover:opacity-90 shadow-md flex items-center justify-center gap-2 text-sm"
                        style="background:#FCB315;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Enregistrer les modifications
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

@endsection
