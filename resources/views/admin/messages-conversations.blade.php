@extends('layouts.dashboard')

@section('title', 'Conversations & Assistance')
@section('page-title', 'Conversations & Assistance')
@section('page-subtitle', 'Supervision globale des échanges et réponses directes de l\'assistance Kimboo')

@section('content')

@if(session('success'))
<div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-center justify-between gap-3 shadow-xs">
    <div class="flex items-center gap-3">
        <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </span>
        <p class="text-xs font-bold">{{ session('success') }}</p>
    </div>
</div>
@endif

{{-- KPI Statistiques globales --}}
<div class="grid grid-cols-1 gap-4 mb-8 sm:grid-cols-2 lg:grid-cols-4">
    <div class="bg-white rounded-2xl p-5 border border-gray-100/80" style="box-shadow:0 4px 12px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Total Discussions</span>
            <span class="w-9 h-9 rounded-xl flex items-center justify-center bg-gray-100 text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-black" style="font-family:'Poppins',sans-serif;">
            {{ $totalConversations }}
        </p>
        <p class="text-xs text-gray-400 mt-1">toutes conversations confondues</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100/80" style="box-shadow:0 4px 12px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-700">Assistance Kimboo</span>
            <span class="w-9 h-9 rounded-xl flex items-center justify-center bg-amber-50 text-amber-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-amber-600" style="font-family:'Poppins',sans-serif;">
            {{ $totalAssistanceCount }}
        </p>
        <p class="text-xs text-gray-400 mt-1">demandes de support reçues</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100/80" style="box-shadow:0 4px 12px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-red-600">Alertes en attente</span>
            <span class="w-9 h-9 rounded-xl flex items-center justify-center bg-red-50 text-red-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-red-600" style="font-family:'Poppins',sans-serif;">
            {{ $totalPendingAlerts }}
        </p>
        <p class="text-xs text-gray-400 mt-1">signalements de sécurité</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100/80" style="box-shadow:0 4px 12px rgba(0,0,0,0.04);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Messages bloqués</span>
            <span class="w-9 h-9 rounded-xl flex items-center justify-center bg-rose-50 text-rose-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-black" style="font-family:'Poppins',sans-serif;">
            {{ $totalBlockedMessages }}
        </p>
        <p class="text-xs text-gray-400 mt-1">tentatives bancaires stoppées</p>
    </div>
</div>

{{-- Barre de filtres et recherche --}}
<div class="bg-white rounded-2xl p-6 mb-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        
        {{-- Formulaire de recherche avec icône parfaitement centrée --}}
        <form method="GET" action="{{ route('admin.messages.conversations') }}" class="flex-1 flex items-center gap-3">
            @if(request('filter'))
                <input type="hidden" name="filter" value="{{ request('filter') }}">
            @endif
            <div class="flex-1">
                <input type="text" name="q" value="{{ $search }}"
                       placeholder="Rechercher par nom, email d'un utilisateur ou mot-clé..."
                       class="w-full h-11 bg-gray-50 border border-gray-200 rounded-xl px-4 text-sm text-black focus:outline-none focus:border-amber-400 focus:bg-white transition">
            </div>
            <button type="submit"
                    class="h-11 px-5 rounded-xl text-sm font-semibold text-black transition hover:opacity-90 shrink-0"
                    style="background:#FCB315;">
                Rechercher
            </button>
            @if($search || request('filter'))
            <a href="{{ route('admin.messages.conversations') }}"
               class="h-11 px-4 flex items-center rounded-xl text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition shrink-0">
                Réinitialiser
            </a>
            @endif
        </form>

        {{-- Boutons d'action : Message individuel & Diffusion groupée --}}
        <div class="flex items-center gap-2.5 shrink-0 flex-wrap sm:flex-nowrap">
            <button type="button" onclick="openBroadcastModal()"
                    class="h-11 px-4 rounded-xl text-xs font-bold flex items-center gap-2 shadow-2xs hover:opacity-90 transition cursor-pointer"
                    style="background-color: #0F172A; color: #FFFFFF; border: 1px solid #1E293B;">
                <svg class="w-4 h-4" style="color: #FCB315;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                <span style="color: #FFFFFF;">Message groupé / Diffusion</span>
            </button>

            <button type="button" onclick="openNewMessageModal()"
                    class="h-11 px-4 rounded-xl text-xs font-bold flex items-center gap-2 shadow-2xs hover:opacity-90 transition cursor-pointer"
                    style="background-color: #FCB315; color: #000000; border: 1px solid #EAB308;">
                <svg class="w-4 h-4" style="color: #000000;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span style="color: #000000;">Contacter un membre</span>
            </button>
        </div>
    </div>

    {{-- Onglets de filtres --}}
    <div class="flex items-center gap-2 overflow-x-auto pt-4 mt-4 border-t border-gray-100">
        <a href="{{ route('admin.messages.conversations', ['filter' => 'all', 'q' => $search]) }}"
           class="px-3.5 py-2 rounded-xl text-xs font-bold transition shrink-0"
           style="{{ $filter === 'all' ? 'background-color: #000000; color: #FFFFFF;' : 'background-color: #F3F4F6; color: #374151;' }}">
            Toutes ({{ $totalConversations }})
        </a>
        <a href="{{ route('admin.messages.conversations', ['filter' => 'assistance', 'q' => $search]) }}"
           class="px-3.5 py-2 rounded-xl text-xs font-bold transition shrink-0 flex items-center gap-1.5"
           style="{{ $filter === 'assistance' ? 'background-color: #FCB315; color: #000000; font-weight: 800;' : 'background-color: #FEF3C7; color: #78350F;' }}">
            <span class="w-2 h-2 rounded-full" style="background-color: #D97706;"></span>
            Assistance Kimboo ({{ $totalAssistanceCount }})
        </a>
        <a href="{{ route('admin.messages.conversations', ['filter' => 'users', 'q' => $search]) }}"
           class="px-3.5 py-2 rounded-xl text-xs font-bold transition shrink-0"
           style="{{ $filter === 'users' ? 'background-color: #1F2937; color: #FFFFFF;' : 'background-color: #F3F4F6; color: #374151;' }}">
            Entre Membres ({{ $totalUsersCount }})
        </a>
        <a href="{{ route('admin.messages.conversations', ['filter' => 'alerts', 'q' => $search]) }}"
           class="px-3.5 py-2 rounded-xl text-xs font-bold transition shrink-0 flex items-center gap-1.5"
           style="{{ $filter === 'alerts' ? 'background-color: #DC2626; color: #FFFFFF;' : 'background-color: #FEE2E2; color: #991B1B;' }}">
            <span class="w-2 h-2 rounded-full" style="background-color: #F87171;"></span>
            Alertes ({{ $totalPendingAlerts }})
        </a>
        <a href="{{ route('admin.messages.conversations', ['filter' => 'blocked', 'q' => $search]) }}"
           class="px-3.5 py-2 rounded-xl text-xs font-bold transition shrink-0 flex items-center gap-1.5"
           style="{{ $filter === 'blocked' ? 'background-color: #9F1239; color: #FFFFFF;' : 'background-color: #F3F4F6; color: #374151;' }}">
            Bloqués ({{ $totalBlockedMessages }})
        </a>
    </div>
</div>

{{-- Liste des conversations --}}
<div class="bg-white rounded-2xl overflow-hidden border border-gray-100" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h2 class="font-bold text-black text-base" style="font-family:'Poppins',sans-serif;">Conversations en direct</h2>
            <p class="text-xs text-gray-400 mt-0.5">{{ $conversations->count() }} conversation(s) affichée(s)</p>
        </div>
    </div>

    @if($conversations->isEmpty())
    <div class="text-center py-16 px-4">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-gray-100 text-gray-400">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
        </div>
        <p class="font-bold text-black text-base mb-1">Aucune conversation trouvée</p>
        <p class="text-sm text-gray-400 max-w-sm mx-auto">
            @if($search || $filter !== 'all')
                Aucun résultat ne correspond à vos critères de recherche.
            @else
                Aucune conversation n'a encore été enregistrée sur la plateforme.
            @endif
        </p>
        @if($search || $filter !== 'all')
        <div class="mt-4">
            <a href="{{ route('admin.messages.conversations') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold text-black hover:opacity-90 transition"
               style="background:#FCB315;">
                Voir toutes les conversations
            </a>
        </div>
        @endif
    </div>
    @else
    <div class="divide-y divide-gray-100">
        @foreach($conversations as $conv)
        @php
            $user1 = $conv->user1;
            $user2 = $conv->user2;
            $lastMsg = $conv->lastMessage;
            $isLastSenderU1 = $lastMsg->sender_id === $user1->id;
            $isAssistance = $conv->isAssistance;
        @endphp
        <div class="p-5 hover:bg-gray-50/80 transition flex flex-col lg:flex-row lg:items-center justify-between gap-4 {{ $conv->hasAlert ? 'bg-red-50/20' : ($isAssistance ? 'bg-amber-50/20' : '') }}">
            
            {{-- Colonne Participants --}}
            <div class="flex items-center gap-3 shrink-0 lg:w-7/24">
                
                {{-- Participant 1 --}}
                <button type="button" onclick="openAdminUserModal(@json($user1))"
                        class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-white hover:shadow-xs transition text-left cursor-pointer group">
                    <x-avatar :user="$user1" size="10" rounded="full"/>
                    <div class="min-w-0">
                        <span class="text-xs font-bold text-black group-hover:text-amber-600 block truncate underline underline-offset-2">
                            {{ $user1->name }}
                        </span>
                        <span class="inline-block text-[10px] px-1.5 py-0.2 rounded font-semibold uppercase tracking-wider {{ $user1->role === 'professeur' ? 'bg-blue-100 text-blue-700' : ($user1->role === 'admin' ? 'bg-amber-100 text-amber-900 font-bold' : 'bg-emerald-100 text-emerald-700') }}">
                            {{ $user1->role === 'admin' ? 'Assistance' : $user1->role }}
                        </span>
                    </div>
                </button>

                {{-- Séparateur interactif --}}
                <div class="flex flex-col items-center shrink-0 px-1 text-gray-300">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>

                {{-- Participant 2 --}}
                <button type="button" onclick="openAdminUserModal(@json($user2))"
                        class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-white hover:shadow-xs transition text-left cursor-pointer group">
                    <x-avatar :user="$user2" size="10" rounded="full"/>
                    <div class="min-w-0">
                        <span class="text-xs font-bold text-black group-hover:text-amber-600 block truncate underline underline-offset-2">
                            {{ $user2->name }}
                        </span>
                        <span class="inline-block text-[10px] px-1.5 py-0.2 rounded font-semibold uppercase tracking-wider {{ $user2->role === 'professeur' ? 'bg-blue-100 text-blue-700' : ($user2->role === 'admin' ? 'bg-amber-100 text-amber-900 font-bold' : 'bg-emerald-100 text-emerald-700') }}">
                            {{ $user2->role === 'admin' ? 'Assistance' : $user2->role }}
                        </span>
                    </div>
                </button>

            </div>

            {{-- Colonne Dernier message et Statuts --}}
            <div class="flex-1 min-w-0 lg:px-4">
                <div class="flex flex-wrap items-center gap-2 mb-1.5">

                    @if($conv->hasAlert)
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-red-100 text-red-700 flex items-center gap-1 shadow-2xs">
                        <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Alerte active ({{ $conv->pendingAlertsCount }})
                    </span>
                    @endif

                    @if($conv->hasBlocked)
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 flex items-center gap-1">
                        <svg class="w-3 h-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        {{ $conv->blockedCount }} message(s) bloqué(s)
                    </span>
                    @endif

                    <span class="px-2 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 text-gray-600">
                        {{ $conv->totalMessages }} message(s) au total
                    </span>

                    <span class="text-[11px] text-gray-400">
                        Dernier échange : {{ \Carbon\Carbon::parse($lastMsg->created_at)->diffForHumans() }}
                    </span>
                </div>

                <p class="text-xs text-gray-600 line-clamp-2 bg-gray-50/80 p-2 rounded-xl border border-gray-100">
                    <span class="font-bold text-gray-700">{{ $isLastSenderU1 ? $user1->name : $user2->name }} :</span>
                    @if($lastMsg->is_blocked)
                        <span class="text-red-600 font-semibold">[Message bloqué par le système de sécurité]</span>
                    @elseif($lastMsg->hasAttachment())
                        <span class="text-amber-800 font-medium inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            <span>{{ $lastMsg->attachment_name ?? 'Pièce jointe' }} {{ $lastMsg->content ? '— ' . Str::limit($lastMsg->content, 90) : '' }}</span>
                        </span>
                    @else
                        {{ Str::limit($lastMsg->content, 120) }}
                    @endif
                </p>
            </div>

            {{-- Colonne Actions --}}
            <div class="shrink-0 flex items-center gap-2">
                @if($conv->hasAlert)
                <form method="POST" action="{{ route('admin.messages.resoudre-alertes', [$user1->id, $user2->id]) }}"
                      onsubmit="return confirm('Confirmer le règlement de toutes les alertes actives pour cette conversation ?');">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl text-xs font-bold text-emerald-800 bg-emerald-100 hover:bg-emerald-200 border border-emerald-300 transition shadow-2xs cursor-pointer"
                            title="Marquer les alertes de cette conversation comme réglées">
                        <svg class="w-3.5 h-3.5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Régler l'alerte</span>
                    </button>
                </form>
                @endif

                <a href="{{ route('admin.messages.voir', [$user1->id, $user2->id]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-black transition hover:opacity-90 shadow-2xs"
                   style="background:#FCB315;">
                    @if($isAssistance)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <span>Échanger / Répondre</span>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span>Consulter le fil</span>
                    @endif
                </a>
            </div>

        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- Modal pour contacter directement un utilisateur (Format moyen) --}}
<div id="new-message-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6 overflow-y-auto" style="background-color: rgba(0,0,0,0.6);" role="dialog">
    <div class="fixed inset-0" onclick="closeNewMessageModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100 z-10 w-full" style="max-width: 480px; margin: auto;">
        <div class="px-5 py-4 text-white flex items-center justify-between" style="background: #0F172A; color: #FFFFFF;">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold shrink-0" style="background: #FCB315; color: #000000;">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white">Contacter un membre</h3>
                    <p class="text-xs text-gray-300">Écrire en tant qu'Assistance Kimboo</p>
                </div>
            </div>
            <button type="button" onclick="closeNewMessageModal()" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-4 sm:p-5">
            <div class="mb-3">
                <input type="text" id="member-search-input" placeholder="Rechercher un élève ou un professeur..."
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 text-xs sm:text-sm text-black focus:outline-none focus:border-amber-400 focus:bg-white transition"
                       style="height: 40px;"
                       oninput="filterAvailableMembers(this.value)">
            </div>

            <div class="overflow-y-auto divide-y divide-gray-100 border border-gray-100 rounded-xl" style="max-height: 340px;" id="available-members-list">
                @forelse($availableUsers as $user)
                <a href="{{ route('messages.show', $user->id) }}"
                   class="member-item flex items-center justify-between p-2.5 sm:p-3 hover:bg-amber-50/60 transition group"
                   data-name="{{ mb_strtolower($user->name) }}" data-email="{{ mb_strtolower($user->email) }}">
                    <div class="flex items-center gap-3 min-w-0">
                        <x-avatar :user="$user" size="8" rounded="full"/>
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm font-bold text-black group-hover:text-amber-600 truncate">{{ $user->name }}</p>
                            <p class="text-[11px] text-gray-400 truncate">{{ $user->email }} • {{ $user->ville ?? 'Côte d\'Ivoire' }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold shrink-0 uppercase tracking-wider {{ $user->role === 'professeur' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' }}">
                        {{ $user->role === 'professeur' ? 'Professeur' : 'Élève' }}
                    </span>
                </a>
                @empty
                <div class="p-6 text-center text-xs text-gray-400">Aucun membre disponible.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Modal de diffusion groupée (Tous les professeurs, Tous les élèves ou Sélection personnalisée) --}}
<div id="broadcast-message-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6 overflow-y-auto" style="background-color: rgba(0,0,0,0.55);" role="dialog">
    <div class="fixed inset-0" onclick="closeBroadcastModal()"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl w-full overflow-hidden border border-gray-100 z-10 my-8" style="max-width: 580px; margin: auto;">
        
        {{-- Header Modal --}}
        <div class="px-6 py-5 text-white flex items-center justify-between" style="background: #0F172A; color: #FFFFFF;">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold shrink-0" style="background: #FCB315; color: #000000;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-white">Message groupé & Diffusion</h3>
                    <p class="text-xs text-gray-300">Envoyer un message officiel d'Assistance Kimboo à plusieurs destinataires</p>
                </div>
            </div>
            <button onclick="closeBroadcastModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Formulaire de diffusion --}}
        <form action="{{ route('admin.messages.broadcast') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5" onsubmit="return validateBroadcastSubmit()">
            @csrf

            {{-- Sélection de l'audience --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2.5">
                    1. Destinataires ciblés
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                    <label class="audience-card border rounded-2xl p-3 flex flex-col items-center justify-center text-center cursor-pointer transition border-amber-400 bg-amber-50/60 shadow-xs" onclick="selectAudience('all_teachers')">
                        <input type="radio" name="target_audience" value="all_teachers" checked class="hidden">
                        <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center mb-1.5 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-gray-900 block leading-tight">Tous les profs</span>
                        <span class="text-[10px] text-amber-800 font-semibold mt-0.5">{{ $totalTeachersCount }} enseignant(s)</span>
                    </label>

                    <label class="audience-card border border-gray-200 rounded-2xl p-3 flex flex-col items-center justify-center text-center cursor-pointer transition hover:border-gray-300" onclick="selectAudience('all_students')">
                        <input type="radio" name="target_audience" value="all_students" class="hidden">
                        <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center mb-1.5 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-gray-900 block leading-tight">Tous les élèves</span>
                        <span class="text-[10px] text-gray-500 font-semibold mt-0.5">{{ $totalStudentsCount }} élève(s)</span>
                    </label>

                    <label class="audience-card border border-gray-200 rounded-2xl p-3 flex flex-col items-center justify-center text-center cursor-pointer transition hover:border-gray-300" onclick="selectAudience('all_users')">
                        <input type="radio" name="target_audience" value="all_users" class="hidden">
                        <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center mb-1.5 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-gray-900 block leading-tight">Tous les membres</span>
                        <span class="text-[10px] text-gray-500 font-semibold mt-0.5">{{ $totalMembersCount }} membres</span>
                    </label>

                    <label class="audience-card border border-gray-200 rounded-2xl p-3 flex flex-col items-center justify-center text-center cursor-pointer transition hover:border-gray-300" onclick="selectAudience('custom')">
                        <input type="radio" name="target_audience" value="custom" class="hidden">
                        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center mb-1.5 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-gray-900 block leading-tight">Sélection libre</span>
                        <span class="text-[10px] text-gray-500 font-semibold mt-0.5" id="custom-count-badge">0 sélectionné(s)</span>
                    </label>
                </div>
            </div>

            {{-- Panneau de sélection manuelle (affiché uniquement quand 'custom' est sélectionné) --}}
            <div id="custom-selection-panel" class="hidden p-4 rounded-2xl bg-gray-50 border border-gray-200 space-y-3">
                <div class="flex items-center justify-between gap-2 flex-wrap">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" placeholder="Filtrer les membres..."
                               class="w-full h-9 bg-white border border-gray-200 rounded-xl px-3.5 text-xs text-black focus:outline-none focus:border-amber-400 transition"
                               oninput="filterBroadcastMembers(this.value)">
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="selectAllBroadcastMembers(true)" class="text-[11px] font-bold text-amber-800 bg-amber-100 hover:bg-amber-200 px-2.5 py-1.5 rounded-lg transition">Tout cocher</button>
                        <button type="button" onclick="selectAllBroadcastMembers(false)" class="text-[11px] font-bold text-gray-600 bg-gray-200 hover:bg-gray-300 px-2.5 py-1.5 rounded-lg transition">Tout décocher</button>
                    </div>
                </div>

                <div class="max-h-48 overflow-y-auto divide-y divide-gray-200 bg-white border border-gray-200 rounded-xl" id="broadcast-members-list">
                    @foreach($availableUsers as $user)
                    <label class="broadcast-member-item flex items-center justify-between p-2.5 hover:bg-amber-50/40 transition cursor-pointer"
                           data-name="{{ mb_strtolower($user->name) }}" data-email="{{ mb_strtolower($user->email) }}">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="broadcast-checkbox w-4 h-4 text-amber-500 border-gray-300 rounded focus:ring-amber-400 cursor-pointer" onchange="updateCustomCount()">
                            <x-avatar :user="$user" size="7" rounded="full"/>
                            <div class="min-w-0">
                                <span class="text-xs font-bold text-black block truncate">{{ $user->name }}</span>
                                <span class="text-[10px] text-gray-400 block truncate">{{ $user->email }}</span>
                            </div>
                        </div>
                        <span class="text-[9px] font-semibold uppercase px-1.5 py-0.5 rounded {{ $user->role === 'professeur' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' }}">
                            {{ $user->role }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Contenu du message --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    2. Contenu du message officiel
                </label>
                <textarea name="content" id="broadcast-content" rows="4" required
                          placeholder="Bonjour à tous, voici une annonce importante de l'Assistance Kimboo..."
                          class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 text-xs sm:text-sm text-black focus:outline-none focus:border-amber-400 focus:bg-white resize-none transition leading-relaxed"></textarea>
            </div>

            {{-- Pièce jointe optionnelle --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    3. Pièce jointe / Document (Optionnel)
                </label>
                <div class="flex items-center gap-3">
                    <input type="file" id="broadcast-attachment" name="attachment" class="hidden" onchange="handleBroadcastFileChange(this)">
                    <button type="button" onclick="document.getElementById('broadcast-attachment').click()"
                            class="px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 hover:bg-gray-100 text-xs font-bold text-gray-700 flex items-center gap-2 transition">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                        <span>Choisir un fichier (PDF, Word, Image...)</span>
                    </button>
                    <span class="text-[11px] text-gray-400">Max 10 Mo</span>
                </div>

                {{-- Preview fichier joint --}}
                <div id="broadcast-file-preview" class="hidden mt-2 p-2.5 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p id="broadcast-file-name" class="text-xs font-bold text-black truncate"></p>
                            <p id="broadcast-file-size" class="text-[10px] text-gray-400"></p>
                        </div>
                    </div>
                    <button type="button" onclick="removeBroadcastFile()" class="p-1 rounded-lg text-gray-400 hover:text-red-600 hover:bg-gray-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Footer et bouton d'envoi --}}
            <div class="pt-3 border-t border-gray-100 flex items-center justify-between gap-3">
                <button type="button" onclick="closeBroadcastModal()" class="px-4 py-2.5 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-100 transition">
                    Annuler
                </button>
                <button type="submit" id="broadcast-submit-btn"
                        class="px-6 py-3 rounded-xl text-xs sm:text-sm font-bold text-black transition hover:opacity-90 shadow-xs flex items-center gap-2"
                        style="background:#FCB315;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    <span id="broadcast-btn-text">Diffuser à tous les professeurs ({{ $totalTeachersCount }})</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openNewMessageModal() {
        const modal = document.getElementById('new-message-modal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                document.getElementById('member-search-input')?.focus();
            }, 50);
        }
    }

    function closeNewMessageModal() {
        const modal = document.getElementById('new-message-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function filterAvailableMembers(query) {
        const q = query.trim().toLowerCase();
        const items = document.querySelectorAll('#available-members-list .member-item');
        items.forEach(item => {
            const name = item.getAttribute('data-name') || '';
            const email = item.getAttribute('data-email') || '';
            if (!q || name.includes(q) || email.includes(q)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function openBroadcastModal() {
        const modal = document.getElementById('broadcast-message-modal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                document.getElementById('broadcast-content')?.focus();
            }, 50);
        }
    }

    function closeBroadcastModal() {
        const modal = document.getElementById('broadcast-message-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    let currentAudience = 'all_teachers';
    const totalTeachers = {{ $totalTeachersCount }};
    const totalStudents = {{ $totalStudentsCount }};
    const totalMembers  = {{ $totalMembersCount }};

    function selectAudience(audience) {
        currentAudience = audience;
        const cards = document.querySelectorAll('.audience-card');
        cards.forEach(card => {
            const radio = card.querySelector('input[type="radio"]');
            if (radio.value === audience) {
                radio.checked = true;
                card.classList.add('border-amber-400', 'bg-amber-50/60', 'shadow-xs');
                card.classList.remove('border-gray-200');
            } else {
                radio.checked = false;
                card.classList.remove('border-amber-400', 'bg-amber-50/60', 'shadow-xs');
                card.classList.add('border-gray-200');
            }
        });

        const customPanel = document.getElementById('custom-selection-panel');
        const btnText = document.getElementById('broadcast-btn-text');

        if (audience === 'custom') {
            customPanel.classList.remove('hidden');
            updateCustomCount();
        } else {
            customPanel.classList.add('hidden');
            if (audience === 'all_teachers') {
                btnText.textContent = `Diffuser à tous les professeurs (${totalTeachers})`;
            } else if (audience === 'all_students') {
                btnText.textContent = `Diffuser à tous les élèves (${totalStudents})`;
            } else if (audience === 'all_users') {
                btnText.textContent = `Diffuser à tous les membres (${totalMembers})`;
            }
        }
    }

    function updateCustomCount() {
        const checked = document.querySelectorAll('#broadcast-members-list input[type="checkbox"]:checked').length;
        document.getElementById('custom-count-badge').textContent = `${checked} sélectionné(s)`;
        if (currentAudience === 'custom') {
            document.getElementById('broadcast-btn-text').textContent = `Diffuser aux ${checked} membre(s) sélectionné(s)`;
        }
    }

    function selectAllBroadcastMembers(check) {
        const visibleItems = document.querySelectorAll('#broadcast-members-list .broadcast-member-item');
        visibleItems.forEach(item => {
            if (item.style.display !== 'none') {
                const cb = item.querySelector('input[type="checkbox"]');
                if (cb) cb.checked = check;
            }
        });
        updateCustomCount();
    }

    function filterBroadcastMembers(query) {
        const q = query.trim().toLowerCase();
        const items = document.querySelectorAll('#broadcast-members-list .broadcast-member-item');
        items.forEach(item => {
            const name = item.getAttribute('data-name') || '';
            const email = item.getAttribute('data-email') || '';
            if (!q || name.includes(q) || email.includes(q)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function handleBroadcastFileChange(input) {
        const file = input.files[0];
        if (!file) return;
        document.getElementById('broadcast-file-name').textContent = file.name;
        document.getElementById('broadcast-file-size').textContent = (file.size / 1024).toFixed(1) + ' Ko';
        document.getElementById('broadcast-file-preview').classList.remove('hidden');
    }

    function removeBroadcastFile() {
        const input = document.getElementById('broadcast-attachment');
        if (input) input.value = '';
        document.getElementById('broadcast-file-preview').classList.add('hidden');
    }

    function validateBroadcastSubmit() {
        if (currentAudience === 'custom') {
            const checked = document.querySelectorAll('#broadcast-members-list input[type="checkbox"]:checked').length;
            if (checked === 0) {
                alert('Veuillez sélectionner au moins un membre pour la diffusion ciblée.');
                return false;
            }
        }
        return true;
    }
</script>

<x-admin-user-modal />

@endsection
