@extends('layouts.dashboard')

@section('title', 'Gestion des utilisateurs')
@section('page-title', 'Utilisateurs')
@section('page-subtitle', 'Gérez et modérez tous les comptes utilisateurs de la plateforme')

@section('content')

@if(session('success'))
<div class="mb-6 px-4 py-3.5 rounded-2xl text-sm font-semibold text-emerald-900 bg-emerald-50 border border-emerald-200/80 flex items-center gap-2.5 shadow-sm">
    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="mb-6 px-4 py-3.5 rounded-2xl text-sm font-semibold text-red-900 bg-red-50 border border-red-200/80 flex items-center gap-2.5 shadow-sm">
    <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
    </svg>
    <span>{{ session('error') }}</span>
</div>
@endif

<div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-100" style="box-shadow:0 4px 20px rgba(0,0,0,0.03);">

    {{-- En-tête & Onglets de filtrage par statut --}}
    <div class="flex flex-col gap-5 mb-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-lg font-bold text-gray-900">Tous les comptes utilisateurs</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/60">
                        {{ $totalUsersCount }} au total
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Supervision, modération et gestion des accès des membres</p>
            </div>

            {{-- Onglets de filtre statut rapide --}}
            <div class="flex items-center gap-1.5 p-1 bg-gray-100/80 rounded-2xl self-start sm:self-auto">
                <a href="{{ route('admin.users', array_merge(request()->except(['status', 'page']), ['status' => 'all'])) }}"
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ ($status === 'all' || !$status) ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                    Tous ({{ $totalUsersCount }})
                </a>
                <a href="{{ route('admin.users', array_merge(request()->except(['status', 'page']), ['status' => 'active'])) }}"
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-1.5 {{ $status === 'active' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-600 hover:text-emerald-700' }}">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Actifs ({{ $totalActiveCount }})</span>
                </a>
                <a href="{{ route('admin.users', array_merge(request()->except(['status', 'page']), ['status' => 'suspended'])) }}"
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-1.5 {{ $status === 'suspended' ? 'bg-white text-red-700 shadow-sm' : 'text-gray-600 hover:text-red-700' }}">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                    <span>Suspendus ({{ $totalSuspendedCount }})</span>
                </a>
            </div>
        </div>

        {{-- Barre de recherche et filtres combinés --}}
        <form method="GET" action="{{ route('admin.users') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 pt-4 border-t border-gray-100">
            <input type="hidden" name="status" value="{{ $status }}">

            <div class="sm:col-span-6 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="q" value="{{ $search }}"
                       placeholder="Rechercher par nom, email, téléphone ou ville..."
                       class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border-gray-200 focus:border-amber-400 focus:ring focus:ring-amber-200 text-gray-900 placeholder-gray-400">
            </div>

            <div class="sm:col-span-4">
                <select name="role" onchange="this.form.submit()"
                        class="w-full py-2 px-3 text-xs rounded-xl border-gray-200 focus:border-amber-400 focus:ring focus:ring-amber-200 text-gray-800 font-medium">
                    <option value="all" {{ $role === 'all' ? 'selected' : '' }}>Tous les rôles</option>
                    <option value="eleve" {{ $role === 'eleve' ? 'selected' : '' }}>Élèves uniquement</option>
                    <option value="professeur" {{ $role === 'professeur' ? 'selected' : '' }}>Professeurs uniquement</option>
                    <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Administrateurs uniquement</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit"
                        class="w-full py-2 px-3 rounded-xl text-xs font-bold text-black bg-[#FCB315] hover:opacity-95 transition-all text-center">
                    Filtrer
                </button>
                @if($search || ($role && $role !== 'all') || ($status && $status !== 'all'))
                <a href="{{ route('admin.users') }}"
                   class="py-2 px-2.5 rounded-xl text-xs font-semibold text-gray-500 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 transition-colors"
                   title="Réinitialiser les filtres">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Tableau des utilisateurs --}}
    <div class="overflow-x-auto">
        <table class="w-full min-w-[900px] text-sm">
            <thead>
                <tr class="border-b border-gray-100 text-left text-xs font-bold uppercase tracking-wider text-gray-400">
                    <th class="py-3 px-4">Utilisateur</th>
                    <th class="py-3 px-4">Rôle</th>
                    <th class="py-3 px-4">Statut</th>
                    <th class="py-3 px-4">Ville</th>
                    <th class="py-3 px-4">Dernière connexion</th>
                    <th class="py-3 px-4">Temps passé</th>
                    <th class="py-3 px-4">Inscrit le</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                <tr onclick='openAdminUserModal(@json($user))'
                    class="hover:bg-amber-50/40 transition-colors duration-150 cursor-pointer group {{ $user->is_suspended ? 'bg-red-50/20' : '' }}">

                    {{-- Avatar + nom + email --}}
                    <td class="py-3.5 px-4">
                        <div class="flex items-center gap-3">
                            <x-avatar :user="$user" size="10" rounded="full"/>
                            <div>
                                <p class="font-bold text-gray-900 leading-tight group-hover:text-amber-900 transition-colors flex items-center gap-1.5">
                                    <span>{{ $user->name }}</span>
                                    <svg class="w-3.5 h-3.5 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Rôle --}}
                    <td class="py-3.5 px-4">
                        @if($user->role === 'admin')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200/60 w-fit">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span>Admin</span>
                        </span>
                        @elseif($user->role === 'professeur')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/60 w-fit">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span>Professeur</span>
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200/60 w-fit">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Élève</span>
                        </span>
                        @endif
                    </td>

                    {{-- Statut du compte (Actif / Suspendu) --}}
                    <td class="py-3.5 px-4">
                        @if($user->is_suspended)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200/80">
                            <svg class="w-3 h-3 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                            <span>Suspendu</span>
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/70">
                            <svg class="w-3 h-3 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Actif</span>
                        </span>
                        @endif
                    </td>

                    {{-- Ville --}}
                    <td class="py-3.5 px-4">
                        <div class="flex items-center gap-1.5 text-xs text-gray-600 font-medium">
                            @if($user->ville)
                            <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $user->ville }}</span>
                            @else
                            <span class="text-gray-300">—</span>
                            @endif
                        </div>
                    </td>

                    {{-- Dernière connexion --}}
                    <td class="py-3.5 px-4">
                        @if($user->last_login_at)
                        <p class="font-bold text-gray-900 text-xs">{{ $user->last_login_at->format('d/m/Y H:i') }}</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">{{ $user->last_login_at->diffForHumans() }}</p>
                        @else
                        <span class="text-xs text-gray-400">Jamais connecté</span>
                        @endif
                    </td>

                    {{-- Temps passé --}}
                    <td class="py-3.5 px-4">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-800 text-xs font-bold border border-amber-200/60">
                            <svg class="w-3 h-3 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $user->formatted_time_spent }}</span>
                        </span>
                    </td>

                    {{-- Date inscription --}}
                    <td class="py-3.5 px-4">
                        <div class="flex items-center gap-1.5 text-xs text-gray-600 font-medium">
                            <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</span>
                        </div>
                    </td>

                    {{-- Actions --}}
                    <td class="py-3.5 px-4 text-right">
                        <div class="flex items-center justify-end gap-1.5" onclick="event.stopPropagation();">
                            {{-- Bouton Détails --}}
                            <button type="button"
                                onclick='openAdminUserModal(@json($user));'
                                class="text-xs font-bold px-3 py-1.5 rounded-xl text-black bg-[#FCB315] hover:opacity-90 transition-all inline-flex items-center gap-1.5 shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <span>Détails</span>
                            </button>

                            {{-- Actions Suspension / Réactivation rapide (si non admin) --}}
                            @if($user->role !== 'admin')
                                @if($user->is_suspended)
                                <form method="POST" action="{{ route('admin.reactivate-user', $user->id) }}" class="inline-block">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        onclick="return confirm('Confirmer la levée de la suspension pour {{ $user->name }} ?')"
                                        class="text-xs px-2.5 py-1.5 rounded-xl text-emerald-800 bg-emerald-50 hover:bg-emerald-100 font-bold inline-flex items-center gap-1 transition-colors border border-emerald-300 shadow-sm"
                                        title="Lever la suspension">
                                        <svg class="w-3.5 h-3.5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="hidden md:inline">Lever suspension</span>
                                    </button>
                                </form>
                                @else
                                <button type="button"
                                    onclick='openAdminUserModal(@json($user)); setTimeout(() => toggleAdminSuspendBox(true), 250);'
                                    class="text-xs px-2.5 py-1.5 rounded-xl text-amber-900 bg-amber-50 hover:bg-amber-100 font-semibold inline-flex items-center gap-1 transition-colors border border-amber-300"
                                    title="Suspendre ce compte">
                                    <svg class="w-3.5 h-3.5 text-amber-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                    </svg>
                                    <span class="hidden md:inline">Suspendre</span>
                                </button>
                                @endif

                                {{-- Bouton Supprimer --}}
                                <form method="POST" action="{{ route('admin.delete-user', $user->id) }}" class="inline-block">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Confirmer la suppression définitive de {{ $user->name }} ?')"
                                        class="text-xs px-2 py-1.5 rounded-xl text-red-600 bg-red-50 hover:bg-red-100 font-semibold inline-flex items-center gap-1 transition-colors border border-red-200/60"
                                        title="Supprimer définitivement">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-10 text-center text-gray-400 text-xs">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Aucun utilisateur trouvé pour ces critères de recherche.</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="mt-6 pt-4 border-t border-gray-100 flex justify-center">
        {{ $users->links() }}
    </div>
    @endif

</div>

{{-- Composant Modal Détails Utilisateur --}}
<x-admin-user-modal />

@endsection
