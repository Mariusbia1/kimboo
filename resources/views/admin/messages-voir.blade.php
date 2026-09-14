@extends('layouts.dashboard')

@section('title', 'Conversation : ' . $user1->name . ' & ' . $user2->name)
@section('page-title', 'Surveillance de conversation')
@section('page-subtitle', $user1->name . ' (' . ucfirst($user1->role) . ') & ' . $user2->name . ' (' . ucfirst($user2->role) . ')')

@section('content')

@if(session('success'))
<div class="mb-5 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-center justify-between gap-3 shadow-xs">
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

{{-- Bandeau alertes actives sur cette conversation --}}
@php
    $pendingAlerts = $alerts->where('status', 'pending');
@endphp
@if($pendingAlerts->isNotEmpty())
<div class="mb-5 p-4 rounded-2xl bg-red-50 border border-red-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-xs">
    <div class="flex items-center gap-3">
        <span class="w-9 h-9 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </span>
        <div>
            <p class="text-xs font-bold text-red-900">
                {{ $pendingAlerts->count() }} alerte(s) de sécurité détectée(s) dans cette discussion
            </p>
            <p class="text-[11px] text-red-700 mt-0.5">
                Certains messages ont été bloqués ou signalés pour transmission non autorisée de données bancaires ou liens externes.
            </p>
        </div>
    </div>
    <div class="flex items-center gap-2 shrink-0 w-full sm:w-auto">
        <form method="POST" action="{{ route('admin.messages.resoudre-alertes', [$user1->id, $user2->id]) }}"
              onsubmit="return confirm('Confirmer le règlement de toutes les alertes de cette discussion ?');">
            @csrf @method('PATCH')
            <button type="submit"
                    class="flex items-center gap-1.5 text-xs px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold transition text-center shadow-2xs cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Marquer comme réglé</span>
            </button>
        </form>

        <a href="{{ route('admin.messages.alertes') }}"
           class="flex-1 sm:flex-initial text-xs px-3.5 py-2 rounded-xl bg-white border border-red-200 text-red-700 font-bold hover:bg-red-50 transition text-center shadow-2xs">
            Détail des alertes
        </a>
    </div>
</div>
@endif

<div class="bg-white rounded-2xl overflow-hidden flex flex-col border border-gray-100"
     style="box-shadow:0 4px 12px rgba(0,0,0,0.06); height:calc(100vh - 210px); min-height:550px;">

    {{-- En-tête de surveillance --}}
    <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4 border-b border-gray-100 bg-gray-50/50">
        
        {{-- Bouton retour et participants --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.messages.conversations') }}"
               class="w-9 h-9 rounded-xl flex items-center justify-center bg-white border border-gray-200 text-gray-500 hover:text-black hover:bg-gray-100 transition shadow-2xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>

            {{-- Participant 1 --}}
            <button type="button" onclick="openAdminUserModal(@json($user1))"
                    class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl bg-white border border-gray-200/80 hover:border-amber-400 hover:shadow-xs transition text-left cursor-pointer group">
                <x-avatar :user="$user1" size="8" rounded="full"/>
                <div>
                    <span class="text-xs font-bold text-black group-hover:text-amber-600 block underline underline-offset-2">
                        {{ $user1->name }}
                    </span>
                    <span class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider">
                        {{ $user1->role }} • {{ $user1->ville ?? 'CI' }}
                    </span>
                </div>
            </button>

            <span class="text-gray-400 font-bold text-xs">↔</span>

            {{-- Participant 2 --}}
            <button type="button" onclick="openAdminUserModal(@json($user2))"
                    class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl bg-white border border-gray-200/80 hover:border-amber-400 hover:shadow-xs transition text-left cursor-pointer group">
                <x-avatar :user="$user2" size="8" rounded="full"/>
                <div>
                    <span class="text-xs font-bold text-black group-hover:text-amber-600 block underline underline-offset-2">
                        {{ $user2->name }}
                    </span>
                    <span class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider">
                        {{ $user2->role }} • {{ $user2->ville ?? 'CI' }}
                    </span>
                </div>
            </button>
        </div>

        {{-- Barre d'actions & recherche rapide --}}
        <div class="flex items-center gap-3">
            <div class="hidden sm:block">
                <input type="text" id="conversation-search" placeholder="Filtrer dans ce fil..."
                       class="w-48 text-xs bg-white border border-gray-200 rounded-xl px-3.5 py-1.5 text-black focus:outline-none focus:border-amber-400 transition"
                       oninput="filterMessages(this.value)">
            </div>

            <span class="px-3 py-1.5 rounded-xl text-xs font-bold bg-gray-900 text-white flex items-center gap-1.5 shadow-2xs">
                <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Surveillance Admin ({{ $messages->count() }} messages)
            </span>
        </div>
    </div>

    {{-- Fil des messages --}}
    <div class="flex-1 overflow-y-auto px-6 py-6 space-y-4 bg-gray-50/30" id="messages-container">

        @if($messages->isEmpty())
        <div class="text-center py-16">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-3 bg-gray-100 text-gray-400">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <p class="text-black font-bold text-sm">Aucun message</p>
            <p class="text-gray-400 text-xs mt-1">Aucun message échangé dans cette conversation.</p>
        </div>
        @else

        @php $lastDate = null; @endphp
        @foreach($messages as $message)
        @php
            $isUser1 = $message->sender_id === $user1->id;
            $sender  = $isUser1 ? $user1 : $user2;
            $msgDate = \Carbon\Carbon::parse($message->created_at)->format('d/m/Y');

            $messageAlerts = $alerts->where('message_id', $message->id);
            $hasAlert = $messageAlerts->isNotEmpty();
            $isBlocked = (bool)$message->is_blocked;
        @endphp

        {{-- Séparateur temporel --}}
        @if($msgDate !== $lastDate)
        <div class="flex items-center gap-3 my-4 date-separator">
            <div class="flex-1 h-px bg-gray-200"></div>
            <span class="text-xs font-semibold text-gray-400 bg-white px-3 py-1 rounded-full border border-gray-200 shadow-2xs">
                {{ \Carbon\Carbon::parse($message->created_at)->isToday() ? "Aujourd'hui" :
                   (\Carbon\Carbon::parse($message->created_at)->isYesterday() ? 'Hier' : $msgDate) }}
            </span>
            <div class="flex-1 h-px bg-gray-200"></div>
        </div>
        @php $lastDate = $msgDate; @endphp
        @endif

        {{-- Bulle de message --}}
        <div class="flex {{ $isUser1 ? 'justify-start' : 'justify-end' }} items-end gap-2.5 message-item"
             data-content="{{ mb_strtolower($message->content) }}">

            @if($isUser1)
            <button type="button" onclick="openAdminUserModal(@json($sender))" class="shrink-0 hover:opacity-80 transition cursor-pointer" title="{{ $sender->name }}">
                <x-avatar :user="$sender" size="8" rounded="full"/>
            </button>
            @endif

            <div class="max-w-md lg:max-w-xl">
                
                {{-- Badge alerte de sécurité si suspect --}}
                @if($hasAlert || $isBlocked)
                <div class="flex items-center gap-1.5 mb-1.5 {{ $isUser1 ? '' : 'justify-end' }}">
                    @if($isBlocked)
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-red-600 text-white flex items-center gap-1 shadow-2xs uppercase tracking-wider">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        Message bloqué par le système
                    </span>
                    @else
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 text-amber-800 flex items-center gap-1 border border-amber-300">
                        <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Signalé (lien externe)
                    </span>
                    @endif
                </div>
                @endif

                {{-- Contenu du message --}}
                <div class="px-4 py-3 rounded-2xl text-xs sm:text-sm leading-relaxed shadow-2xs
                    {{ $isBlocked ? 'bg-red-50 text-red-900 border-2 border-red-300 font-medium' :
                       ($isUser1 ? 'bg-white text-gray-800 rounded-bl-xs border border-gray-200' : 'text-black rounded-br-xs') }}"
                    style="{{ !$isUser1 && !$isBlocked ? 'background:#FCB315;' : '' }}">
                    @if($message->content)
                        <div>{{ $message->content }}</div>
                    @endif

                    {{-- Pièce jointe / Fichier --}}
                    @if($message->hasAttachment())
                        @if($message->isImageAttachment())
                        <div class="mt-2">
                            <a href="{{ $message->attachment_url }}" target="_blank" class="block group relative">
                                <img src="{{ $message->attachment_url }}" alt="{{ $message->attachment_name }}" class="max-h-60 w-auto rounded-lg object-contain transition group-hover:opacity-95">
                                <span class="absolute bottom-1 right-1 bg-black/70 text-white text-[10px] px-2 py-0.5 rounded-md font-medium flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Agrandir
                                </span>
                            </a>
                        </div>
                        @else
                        <div class="mt-2 p-2.5 rounded-xl border flex items-center justify-between gap-3 {{ !$isUser1 ? 'bg-black/10 border-black/15' : 'bg-gray-50 border-gray-200' }}">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ !$isUser1 ? 'bg-black text-white' : 'bg-amber-100 text-amber-800' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold truncate">{{ $message->attachment_name ?? 'Document joint' }}</p>
                                    <p class="text-[10px] text-gray-500">{{ $message->formatted_attachment_size }}</p>
                                </div>
                            </div>
                            <a href="{{ $message->attachment_url }}" target="_blank" download="{{ $message->attachment_name }}"
                               class="px-2.5 py-1.5 rounded-lg text-[11px] font-bold shrink-0 transition flex items-center gap-1 {{ !$isUser1 ? 'bg-black text-white hover:bg-black/80' : 'bg-white text-gray-800 border border-gray-200 hover:bg-gray-100' }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                <span>Télécharger</span>
                            </a>
                        </div>
                        @endif
                    @endif
                </div>

                {{-- Horodatage et émetteur --}}
                <div class="flex items-center gap-2 mt-1 {{ $isUser1 ? 'justify-start' : 'justify-end' }}">
                    <span class="text-[11px] font-bold text-gray-500">{{ $sender->name }}</span>
                    <span class="text-[11px] text-gray-400">· {{ \Carbon\Carbon::parse($message->created_at)->format('H:i') }}</span>
                    @if($message->is_read)
                    <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-1.5 py-0.2 rounded border border-emerald-100">Lu</span>
                    @endif
                </div>
            </div>

            @if(!$isUser1)
            <button type="button" onclick="openAdminUserModal(@json($sender))" class="shrink-0 hover:opacity-80 transition cursor-pointer" title="{{ $sender->name }}">
                <x-avatar :user="$sender" size="8" rounded="full"/>
            </button>
            @endif

        </div>
        @endforeach
        @endif
    </div>

    {{-- Bas de conversation : Réponse directe ou Actions d'assistance --}}
    @php
        $isAssistanceThread = ($user1->role === 'admin' || $user2->role === 'admin');
        $chatContact = ($user1->role === 'admin') ? $user2 : $user1;
    @endphp

    @if($isAssistanceThread)
    {{-- Formulaire de réponse pour l'administrateur (Assistance Kimboo) --}}
    <div class="px-6 py-4 border-t border-gray-100 bg-white shrink-0">
        {{-- Badge prévisualisation fichier --}}
        <div id="admin-file-preview" class="hidden mb-3 p-2.5 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p id="admin-file-name" class="text-xs font-bold text-black truncate"></p>
                    <p id="admin-file-size" class="text-[10px] text-gray-400"></p>
                </div>
            </div>
            <button type="button" onclick="removeAdminFile()" class="p-1 rounded-lg text-gray-400 hover:text-red-600 hover:bg-gray-100 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('messages.send', $chatContact->id) }}" method="POST" enctype="multipart/form-data" class="flex items-end gap-2.5">
            @csrf
            <input type="file" id="admin-attachment" name="attachment" class="hidden" onchange="handleAdminFileChange(this)">

            <button type="button" onclick="document.getElementById('admin-attachment').click()"
                    class="h-11 w-11 rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center shrink-0 transition"
                    title="Joindre un fichier">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                </svg>
            </button>

            <div class="flex-1 relative">
                <textarea name="content" rows="1" placeholder="Répondre en tant qu'Assistance Kimboo à {{ $chatContact->name }}..."
                          class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-xs sm:text-sm text-black focus:outline-none focus:border-amber-400 focus:bg-white resize-none transition"
                          style="min-height:44px; max-height:120px;"
                          onkeydown="if(event.key==='Enter' && !event.shiftKey){event.preventDefault(); this.form.submit();}"></textarea>
            </div>

            <button type="submit"
                    class="h-11 px-5 rounded-2xl text-xs sm:text-sm font-bold text-black flex items-center gap-1.5 transition hover:opacity-90 shrink-0 shadow-xs"
                    style="background:#FCB315;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                <span>Envoyer</span>
            </button>
        </form>
    </div>
    @else
    {{-- Barre de contact pour échange direct avec l'un des deux participants --}}
    <div class="px-6 py-3.5 border-t border-gray-100 bg-gray-50/80 flex flex-col sm:flex-row items-center justify-between gap-3 shrink-0">
        <div class="text-xs text-gray-500">
            <span class="font-bold text-gray-700">Surveillance :</span> Vous observez l'échange entre <strong>{{ $user1->name }}</strong> et <strong>{{ $user2->name }}</strong>.
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('messages.show', $user1->id) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-white border border-gray-200 text-black hover:bg-amber-50 hover:border-amber-300 transition shadow-2xs flex items-center gap-1.5">
                <x-avatar :user="$user1" size="5" rounded="full"/>
                <span>Écrire à {{ $user1->name }}</span>
            </a>
            <a href="{{ route('messages.show', $user2->id) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-white border border-gray-200 text-black hover:bg-amber-50 hover:border-amber-300 transition shadow-2xs flex items-center gap-1.5">
                <x-avatar :user="$user2" size="5" rounded="full"/>
                <span>Écrire à {{ $user2->name }}</span>
            </a>
        </div>
    </div>
    @endif
</div>

<script>
    const container = document.getElementById('messages-container');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }

    function filterMessages(query) {
        const q = query.trim().toLowerCase();
        const items = document.querySelectorAll('.message-item');
        items.forEach(item => {
            const content = item.getAttribute('data-content') || '';
            if (!q || content.includes(q)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function handleAdminFileChange(input) {
        const file = input.files[0];
        if (!file) return;
        document.getElementById('admin-file-name').textContent = file.name;
        document.getElementById('admin-file-size').textContent = (file.size / 1024).toFixed(1) + ' Ko';
        document.getElementById('admin-file-preview').classList.remove('hidden');
    }

    function removeAdminFile() {
        const input = document.getElementById('admin-attachment');
        if (input) input.value = '';
        document.getElementById('admin-file-preview').classList.add('hidden');
    }
</script>

<x-admin-user-modal />
@endsection
