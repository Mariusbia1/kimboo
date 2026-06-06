@extends('layouts.dashboard')

@section('title', 'Alertes messages')
@section('page-title', 'Alertes messages')
@section('page-subtitle', 'Messages suspects détectés par le système')

@section('content')

@if(session('success'))
<div class="mb-6 px-4 py-3 rounded-xl text-sm font-medium text-green-700 bg-green-100 flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
</div>
@endif

{{-- Stats rapides --}}
<div class="grid grid-cols-1 gap-4 mb-8 sm:grid-cols-2 lg:grid-cols-3">
    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm text-gray-400">En attente</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-red-100">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">
            {{ \App\Models\MessageAlert::where('status', 'pending')->count() }}
        </p>
        <p class="text-sm text-gray-400 mt-1">alertes à traiter</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm text-gray-400">Traitées</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-green-100">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">
            {{ \App\Models\MessageAlert::where('status', 'reviewed')->count() }}
        </p>
        <p class="text-sm text-gray-400 mt-1">alertes traitées</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm text-gray-400">Ignorées</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-gray-100">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">
            {{ \App\Models\MessageAlert::where('status', 'ignored')->count() }}
        </p>
        <p class="text-sm text-gray-400 mt-1">alertes ignorées</p>
    </div>
</div>

{{-- Liste alertes --}}
<div class="bg-white rounded-2xl p-5 sm:p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
    <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="font-semibold text-black">Alertes détectées</h2>
            <p class="text-sm text-gray-400 mt-0.5">{{ $alertes->total() }} alertes au total</p>
        </div>
        <a href="{{ route('admin.messages.conversations') }}"
           class="text-sm font-semibold px-4 py-2 rounded-xl text-black transition hover:opacity-90 inline-flex items-center justify-center gap-2"
           style="background:#FCB315;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            Voir toutes les conversations
        </a>
    </div>

    @if($alertes->isEmpty())
    <div class="text-center py-12">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-gray-100">
            <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="font-semibold text-black mb-1">Aucune alerte</p>
        <p class="text-sm text-gray-400">Tous les messages respectent les règles de la plateforme.</p>
    </div>
    @else
    <div class="space-y-4">
        @foreach($alertes as $alerte)
        <div class="border rounded-2xl p-5 {{ $alerte->status === 'pending' ? 'border-red-200 bg-red-50' : ($alerte->status === 'reviewed' ? 'border-green-200 bg-green-50' : 'border-gray-200 bg-gray-50') }}">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">

                {{-- Infos alerte --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-3 mb-3">

                        {{-- Badge type --}}
                        @if($alerte->alert_type === 'phone')
                        <span class="px-2.5 py-1 rounded-full text-sm font-medium bg-red-100 text-red-700 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            Numéro de téléphone
                        </span>
                        @elseif($alerte->alert_type === 'bank')
                        <span class="px-2.5 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-700 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            Coordonnées bancaires
                        </span>
                        @elseif($alerte->alert_type === 'link')
                        <span class="px-2.5 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-700 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            Lien externe
                        </span>
                        @endif

                        {{-- Badge statut --}}
                        @if($alerte->status === 'pending')
                        <span class="px-2.5 py-1 rounded-full text-sm font-medium bg-red-100 text-red-700">En attente</span>
                        @elseif($alerte->status === 'reviewed')
                        <span class="px-2.5 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">Traité</span>
                        @else
                        <span class="px-2.5 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-500">Ignoré</span>
                        @endif

                        <span class="text-sm text-gray-400">{{ $alerte->created_at->diffForHumans() }}</span>
                    </div>

                    {{-- Expéditeur → Destinataire --}}
                    <div class="flex items-center gap-3 mb-3">
                        <div class="flex items-center gap-2">
                            <x-avatar :user="$alerte->sender" size="7" rounded="full"/>
                            <span class="text-sm font-medium text-black">{{ $alerte->sender->name }}</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                        <div class="flex items-center gap-2">
                            <x-avatar :user="$alerte->receiver" size="7" rounded="full"/>
                            <span class="text-sm font-medium text-black">{{ $alerte->receiver->name }}</span>
                        </div>
                    </div>

                    {{-- Contenu suspect --}}
                    <div class="bg-white rounded-xl px-4 py-3 border border-gray-200">
                        <p class="text-sm text-gray-400 mb-1">Contenu détecté :</p>
                        <p class="text-sm font-mono text-red-600">{{ $alerte->matched_content }}</p>
                    </div>

                    {{-- Message complet --}}
                    @if($alerte->message)
                    <div class="mt-2 bg-white rounded-xl px-4 py-3 border border-gray-200">
                        <p class="text-sm text-gray-400 mb-1">Message complet :</p>
                        <p class="text-sm text-gray-700">{{ $alerte->message->content }}</p>
                        @if($alerte->message->is_blocked)
                        <span class="inline-block mt-1 text-sm px-2 py-0.5 rounded-full bg-red-100 text-red-600">Message bloqué</span>
                        @else
                        <span class="inline-block mt-1 text-sm px-2 py-0.5 rounded-full bg-amber-100 text-amber-600">Message envoyé (lien signalé)</span>
                        @endif
                    </div>
                    @endif

                    {{-- Lien voir conversation --}}
                    <a href="{{ route('admin.messages.voir', [$alerte->sender_id, $alerte->receiver_id]) }}"
                       class="inline-flex items-center gap-1.5 mt-3 text-sm font-medium hover:underline"
                       style="color:#FCB315;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Voir la conversation complète
                    </a>
                </div>

                {{-- Actions --}}
                @if($alerte->status === 'pending')
                <div class="flex flex-col gap-2 shrink-0">
                    <form method="POST" action="{{ route('admin.messages.alertes.reviewed', $alerte->id) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                            class="w-full text-sm px-4 py-2 rounded-xl text-white font-semibold flex items-center justify-center gap-1.5 transition hover:opacity-90 bg-green-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Traité
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.messages.alertes.ignored', $alerte->id) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                            class="w-full text-sm px-4 py-2 rounded-xl font-semibold flex items-center justify-center gap-1.5 transition hover:bg-gray-200 bg-gray-100 text-gray-600">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Ignorer
                        </button>
                    </form>
                </div>
                @endif

            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($alertes->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $alertes->links() }}
    </div>
    @endif

    @endif
</div>


{{-- Autres alertes système --}}
<p class="text-sm font-semibold text-gray-400 uppercase tracking-widest mb-3 mt-8">Alertes système</p>

<div class="space-y-4">

    {{-- Comptes suspects --}}
    @if($comptesSuspects->count() > 0)
    <div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06); border-left:4px solid #ef4444;">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center bg-red-100">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-black text-sm">Comptes suspects</h3>
                <p class="text-sm text-gray-400">Utilisateurs avec 2+ tentatives de messages bloqués</p>
            </div>
            <span class="sm:ml-auto px-2.5 py-1 rounded-full text-sm font-bold bg-red-100 text-red-700">
                {{ $comptesSuspects->total() }}
            </span>
        </div>
        <div class="space-y-2">
            @foreach($comptesSuspects as $suspect)
            <div class="flex flex-col gap-3 p-3 rounded-xl sm:flex-row sm:items-center sm:justify-between bg-red-50">
                <div class="flex items-center gap-3 min-w-0">
                    <x-avatar :user="$suspect" size="8" rounded="full"/>
                    <div>
                        <p class="font-medium text-black text-sm">{{ $suspect->name }}</p>
                        <p class="text-sm text-gray-500">{{ $suspect->email }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm font-bold text-red-600">{{ $suspect->message_attempts }} tentative(s)</span>
                    @if($suspect->message_blocked_until && now()->lt($suspect->message_blocked_until))
                    <span class="px-2 py-0.5 rounded-full text-sm bg-red-100 text-red-700">
                        Suspendu jusqu'à {{ $suspect->message_blocked_until->format('H:i') }}
                    </span>
                    @endif
                    <a href="{{ route('admin.messages.conversations') }}"
                       class="text-sm px-3 py-1.5 rounded-lg font-medium transition hover:opacity-90"
                       style="background:#1A2B3C; color:#fff;">
                        Voir
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @if($comptesSuspects->hasPages())
        <div class="mt-4 flex justify-center">
            {{ $comptesSuspects->links() }}
        </div>
        @endif
    </div>
    @endif

    {{-- Cours en attente depuis 48h --}}
    @if($coursEnAttente48h->count() > 0)
    <div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06); border-left:4px solid #f59e0b;">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center bg-amber-100">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-black text-sm">Cours en attente depuis 48h+</h3>
                <p class="text-sm text-gray-400">Ces cours attendent votre validation depuis plus de 2 jours</p>
            </div>
            <span class="sm:ml-auto px-2.5 py-1 rounded-full text-sm font-bold bg-amber-100 text-amber-700">
                {{ $coursEnAttente48h->total() }}
            </span>
        </div>
        <div class="space-y-2">
            @foreach($coursEnAttente48h as $cours)
            <div class="flex flex-col gap-3 p-3 rounded-xl sm:flex-row sm:items-center sm:justify-between bg-amber-50">
                <div class="flex items-center gap-3 min-w-0">
                    <x-avatar :user="$cours->teacherProfile->user" size="8" rounded="full"/>
                    <div>
                        <p class="font-medium text-black text-sm">{{ $cours->title }}</p>
                        <p class="text-sm text-gray-500">{{ $cours->teacherProfile->user->name }} · {{ $cours->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.cours') }}"
                   class="text-sm px-3 py-1.5 rounded-lg text-black font-medium transition hover:opacity-90"
                   style="background:#FCB315;">
                    Valider
                </a>
            </div>
            @endforeach
        </div>
        @if($coursEnAttente48h->hasPages())
        <div class="mt-4 flex justify-center">
            {{ $coursEnAttente48h->links() }}
        </div>
        @endif
    </div>
    @endif

    {{-- Professeurs non certifiés depuis 7j --}}
    @if($profsNonVerifies->count() > 0)
    <div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06); border-left:4px solid #6366f1;">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center bg-indigo-100">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-black text-sm">Professeurs non certifiés depuis 7j+</h3>
                <p class="text-sm text-gray-400">Ces professeurs attendent leur certification depuis plus d'une semaine</p>
            </div>
            <span class="sm:ml-auto px-2.5 py-1 rounded-full text-sm font-bold bg-indigo-100 text-indigo-700">
                {{ $profsNonVerifies->total() }}
            </span>
        </div>
        <div class="space-y-2">
            @foreach($profsNonVerifies as $profile)
            <div class="flex flex-col gap-3 p-3 rounded-xl sm:flex-row sm:items-center sm:justify-between bg-indigo-50">
                <div class="flex items-center gap-3 min-w-0">
                    <x-avatar :user="$profile->user" size="8" rounded="full"/>
                    <div>
                        <p class="font-medium text-black text-sm">{{ $profile->user->name }}</p>
                        <p class="text-sm text-gray-500">Inscrit {{ $profile->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.dashboard') }}"
                   class="text-sm px-3 py-1.5 rounded-lg text-white font-medium transition hover:opacity-90"
                   style="background:#1A2B3C;">
                    Certifier
                </a>
            </div>
            @endforeach
        </div>
        @if($profsNonVerifies->hasPages())
        <div class="mt-4 flex justify-center">
            {{ $profsNonVerifies->links() }}
        </div>
        @endif
    </div>
    @endif

    {{-- Réservations annulées en masse --}}
    @if($reservationsAnnulees->count() > 0)
    <div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06); border-left:4px solid #10b981;">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center bg-green-100">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-black text-sm">Annulations en masse (7 derniers jours)</h3>
                <p class="text-sm text-gray-400">Utilisateurs avec 3+ réservations annulées cette semaine</p>
            </div>
            <span class="sm:ml-auto px-2.5 py-1 rounded-full text-sm font-bold bg-green-100 text-green-700">
                {{ $reservationsAnnulees->total() }}
            </span>
        </div>
        <div class="space-y-2">
            @foreach($reservationsAnnulees as $user)
            <div class="flex flex-col gap-3 p-3 rounded-xl sm:flex-row sm:items-center sm:justify-between bg-green-50">
                <div class="flex items-center gap-3 min-w-0">
                    <x-avatar :user="$user" size="8" rounded="full"/>
                    <div>
                        <p class="font-medium text-black text-sm">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $user->annulations }} annulation(s) cette semaine</p>
                    </div>
                </div>
                <span class="text-sm px-3 py-1.5 rounded-lg font-medium bg-green-100 text-green-700">
                    {{ $user->annulations }}x annulé
                </span>
            </div>
            @endforeach
        </div>
        @if($reservationsAnnulees->hasPages())
        <div class="mt-4 flex justify-center">
            {{ $reservationsAnnulees->links() }}
        </div>
        @endif
    </div>
    @endif

    {{-- Professeurs inactifs --}}
    @if($profsInactifs->count() > 0)
    <div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06); border-left:4px solid #9ca3af;">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center bg-gray-100">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-black text-sm">Professeurs inactifs depuis 30j+</h3>
                <p class="text-sm text-gray-400">Aucune activité ni mise à jour de cours depuis 30 jours</p>
            </div>
            <span class="sm:ml-auto px-2.5 py-1 rounded-full text-sm font-bold bg-gray-100 text-gray-600">
                {{ $profsInactifs->total() }}
            </span>
        </div>
        <div class="space-y-2">
            @foreach($profsInactifs as $profile)
            <div class="flex flex-col gap-3 p-3 rounded-xl sm:flex-row sm:items-center sm:justify-between bg-gray-50">
                <div class="flex items-center gap-3 min-w-0">
                    <x-avatar :user="$profile->user" size="8" rounded="full"/>
                    <div>
                        <p class="font-medium text-black text-sm">{{ $profile->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $profile->user->email }}</p>
                    </div>
                </div>
                <a href="{{ route('professeur.profil', $profile->id) }}"
                   target="_blank"
                   class="text-sm px-3 py-1.5 rounded-lg font-medium bg-gray-200 text-gray-700 transition hover:bg-gray-300">
                    Voir profil
                </a>
            </div>
            @endforeach
        </div>
        @if($profsInactifs->hasPages())
        <div class="mt-4 flex justify-center">
            {{ $profsInactifs->links() }}
        </div>
        @endif
    </div>
    @endif

    {{-- Tout va bien --}}
    @if($comptesSuspects->count() === 0 && $coursEnAttente48h->count() === 0 && $profsNonVerifies->count() === 0 && $reservationsAnnulees->count() === 0 && $profsInactifs->count() === 0)
    <div class="bg-white rounded-2xl p-10 text-center" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-green-100">
            <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="font-semibold text-black mb-1">Tout est en ordre</p>
        <p class="text-sm text-gray-400">Aucune alerte système détectée.</p>
    </div>
    @endif

</div>

@endsection
