@extends('layouts.dashboard')

@section('title', 'Utilisateurs')
@section('page-title', 'Utilisateurs')
@section('page-subtitle', 'Gérez tous les utilisateurs de la plateforme')

@section('content')

@if(session('success'))
<div class="mb-6 px-4 py-3 rounded-xl text-sm font-medium text-green-700 bg-green-100 flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="font-semibold text-black">Tous les utilisateurs</h2>
            <p class="text-xs text-gray-400 mt-0.5">{{ $users->total() }} comptes enregistrés</p>
        </div>

        {{-- Bouton ajouter (non fonctionnel pour l'instant) --}}
        <button
            onclick="alert('Fonctionnalité en cours de développement.')"
            class="flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-xl text-black transition hover:opacity-90"
            style="background:#FCB315;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Ajouter un utilisateur
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-xs uppercase tracking-wide">Utilisateur</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-xs uppercase tracking-wide">Rôle</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-xs uppercase tracking-wide">Ville</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-xs uppercase tracking-wide">Téléphone</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-xs uppercase tracking-wide">Inscrit le</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium text-xs uppercase tracking-wide">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">

                    {{-- Avatar + nom --}}
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <x-avatar :user="$user" size="9" rounded="full"/>
                            <div>
                                <p class="font-medium text-black">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Rôle --}}
                    <td class="py-3 px-4">
                        @if($user->role === 'admin')
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700 flex items-center gap-1 w-fit">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Admin
                        </span>
                        @elseif($user->role === 'professeur')
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 flex items-center gap-1 w-fit">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Professeur
                        </span>
                        @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 flex items-center gap-1 w-fit">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Élève
                        </span>
                        @endif
                    </td>

                    {{-- Ville --}}
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-1 text-gray-600">
                            @if($user->ville)
                            <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $user->ville }}
                            @else
                            <span class="text-gray-300">—</span>
                            @endif
                        </div>
                    </td>

                    {{-- Téléphone --}}
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-1 text-gray-600">
                            @if($user->phone)
                            <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $user->phone }}
                            @else
                            <span class="text-gray-300">—</span>
                            @endif
                        </div>
                    </td>

                    {{-- Date inscription --}}
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-1 text-gray-600">
                            <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}
                        </div>
                    </td>

                    {{-- Action --}}
                    <td class="py-3 px-4">
                        @if($user->role !== 'admin')
                        <form method="POST" action="{{ route('admin.delete-user', $user->id) }}">
                            @csrf @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('Confirmer la suppression de {{ $user->name }} ?')"
                                class="text-xs px-3 py-1.5 rounded-lg text-white font-medium flex items-center gap-1.5 transition hover:opacity-90 bg-red-500">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Supprimer
                            </button>
                        </form>
                        @else
                        <span class="text-xs text-gray-300">—</span>
                        @endif
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $users->links() }}
    </div>
    @endif

</div>

@endsection
