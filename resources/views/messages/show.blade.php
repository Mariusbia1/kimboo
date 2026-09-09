@extends('layouts.dashboard')

@php
    $isAssistance = ($contact->role === 'admin');
@endphp

@section('title', $isAssistance ? 'Assistance Kimboo' : 'Conversation avec ' . $contact->name)
@section('page-title', $isAssistance ? 'Assistance Kimboo' : $contact->name)
@section('page-subtitle', $isAssistance ? 'Échangez avec l\'équipe Kimboo' : ucfirst($contact->role))

@section('content')

<div class="bg-white rounded-2xl overflow-hidden flex flex-col border border-gray-100"
     style="box-shadow:0 4px 12px rgba(0,0,0,0.06); height:calc(100vh - 180px); min-height:550px;">

    {{-- Header conversation --}}
    <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-100 bg-white">
        <a href="{{ route('messages.index') }}" class="w-9 h-9 rounded-xl flex items-center justify-center bg-gray-50 text-gray-400 hover:text-black hover:bg-gray-100 transition shadow-2xs">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>

        <div class="relative shrink-0">
            <x-avatar :user="$contact" size="10" rounded="full"/>
        </div>

        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2">
                <p class="font-bold text-black text-sm truncate">{{ $contact->name }}</p>
                @if(!$isAssistance)
                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-600 capitalize shrink-0">
                    {{ $contact->role }}
                </span>
                @endif
            </div>
            <p class="text-xs text-gray-400 truncate">
                @if($isAssistance)
                    <span class="text-emerald-600 font-semibold flex items-center gap-1 inline-flex">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        En ligne
                    </span>
                @else
                    {{ $contact->ville ? $contact->ville . ' · ' : '' }}Membre Kimboo
                @endif
            </p>
        </div>

        {{-- Actions rapides dans le header --}}
        <div class="flex items-center gap-2 shrink-0">
            @if($contact->role === 'professeur' && $contact->teacherProfile)
            <a href="{{ route('professeur.profil', $contact->teacherProfile->id) }}"
               target="_blank"
               class="text-xs px-3.5 py-2 rounded-xl font-bold flex items-center gap-1.5 transition hover:opacity-90 shadow-2xs"
               style="background:#FCB315; color:#000;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Profil</span>
            </a>
            @endif
        </div>
    </div>

    {{-- Bandeau WhatsApp : Apparaît uniquement après 24h sans réponse de l'assistance --}}
    @if($isAssistance && !empty($unansweredAfter24h) && !empty($whatsappLink))
    <div class="px-6 py-3 bg-gradient-to-r from-emerald-50 via-teal-50 to-amber-50 border-b border-emerald-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-2xs animate-fadeIn">
        <div class="flex items-center gap-2.5 text-xs text-emerald-950 font-medium">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping shrink-0"></span>
            <span><strong>Délai dépassé (24h) :</strong> Votre dernier message à l'assistance est sans réponse depuis plus de 24h ? Contactez directement notre équipe sur WhatsApp :</span>
        </div>
        <a href="{{ $whatsappLink }}"
           target="_blank" rel="noopener"
           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold text-white bg-[#25D366] hover:opacity-95 transition shadow-xs shrink-0">
            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
            </svg>
            <span>Contacter sur WhatsApp</span>
        </a>
    </div>
    @endif

    {{-- Alerte retour serveur --}}
    @if(session('error'))
    <div class="px-6 py-3 text-xs font-bold text-red-700 bg-red-50 border-b border-red-200 flex items-center gap-2">
        <svg class="w-4 h-4 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Fil des messages --}}
    <div class="flex-1 overflow-y-auto px-6 py-6 bg-gray-50/40" id="messages-container">

        @if($messages->isEmpty())
        <div class="text-center py-12">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-3 {{ $isAssistance ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-400' }}">
                @if($isAssistance)
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                @else
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                @endif
            </div>
            <p class="font-bold text-black text-sm mb-1">
                {{ $isAssistance ? 'Bienvenue sur l\'Assistance Kimboo' : 'Démarrez la conversation' }}
            </p>
            <p class="text-gray-400 text-xs max-w-sm mx-auto">
                {{ $isAssistance ? 'Posez votre question ou décrivez votre demande. Vous pouvez également joindre des documents (PDF, devoirs, captures).' : 'Envoyez un message ou partagez des documents avec ' . $contact->name . '.' }}
            </p>
        </div>
        @else

        {{-- Grouper les messages par date --}}
        @php $lastDate = null; @endphp
        @foreach($messages as $message)
        @php
            $isMine    = $message->sender_id === auth()->id();
            $sender    = $isMine ? auth()->user() : $contact;
            $msgDate   = \Carbon\Carbon::parse($message->created_at)->format('d/m/Y');
        @endphp

        {{-- Séparateur de date --}}
        @if($msgDate !== $lastDate)
        <div class="flex items-center gap-3 my-4">
            <div class="flex-1 h-px bg-gray-200"></div>
            <span class="text-xs font-semibold text-gray-400 bg-white px-3 py-1 rounded-full border border-gray-200 shadow-2xs">
                {{ \Carbon\Carbon::parse($message->created_at)->isToday() ? "Aujourd'hui" :
                   (\Carbon\Carbon::parse($message->created_at)->isYesterday() ? 'Hier' : $msgDate) }}
            </span>
            <div class="flex-1 h-px bg-gray-200"></div>
        </div>
        @php $lastDate = $msgDate; @endphp
        @endif

        {{-- Bulle message --}}
        <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} items-end gap-2.5 mb-3">

            @if(!$isMine)
            <x-avatar :user="$sender" size="8" rounded="full"/>
            @endif

            <div class="max-w-xs sm:max-w-md lg:max-w-lg">
                <div class="p-3.5 rounded-2xl text-xs sm:text-sm leading-relaxed shadow-2xs space-y-2.5
                    {{ $isMine ? 'rounded-br-xs text-black font-medium' : 'bg-white text-gray-800 rounded-bl-xs border border-gray-200' }}"
                    style="{{ $isMine ? 'background:#FCB315;' : '' }}">

                    {{-- Texte du message --}}
                    @if(!empty($message->content))
                    <div class="whitespace-pre-wrap">{{ $message->content }}</div>
                    @endif

                    {{-- Affichage de la pièce jointe --}}
                    @if($message->hasAttachment())
                        @if($message->isImageAttachment())
                        <div class="mt-1.5 overflow-hidden rounded-xl border {{ $isMine ? 'border-amber-400/80 bg-black/5' : 'border-gray-200 bg-gray-50' }}">
                            <a href="{{ $message->attachment_url }}" target="_blank" class="block group relative">
                                <img src="{{ $message->attachment_url }}" alt="{{ $message->attachment_name }}" class="max-h-60 w-auto rounded-lg object-contain transition group-hover:opacity-95">
                                <span class="absolute bottom-1 right-1 bg-black/70 text-white text-[10px] px-2 py-0.5 rounded-md font-medium flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Agrandir
                                </span>
                            </a>
                        </div>
                        @else
                        <div class="mt-1.5 p-2.5 rounded-xl border flex items-center justify-between gap-3 {{ $isMine ? 'bg-black/10 border-black/15' : 'bg-gray-50 border-gray-200' }}">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ $isMine ? 'bg-black text-white' : 'bg-amber-100 text-amber-800' }}">
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
                               class="px-2.5 py-1.5 rounded-lg text-[11px] font-bold shrink-0 transition flex items-center gap-1 {{ $isMine ? 'bg-black text-white hover:bg-black/80' : 'bg-white text-gray-800 border border-gray-200 hover:bg-gray-100' }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                <span>Télécharger</span>
                            </a>
                        </div>
                        @endif
                    @endif

                </div>
                <p class="text-[11px] text-gray-400 mt-1 {{ $isMine ? 'text-right' : 'text-left' }}">
                    {{ \Carbon\Carbon::parse($message->created_at)->format('H:i') }}
                    @if($isMine)
                    ·
                    @if($message->is_read)
                    <span class="text-emerald-600 font-semibold">Lu</span>
                    @else
                    <span>Envoyé</span>
                    @endif
                    @endif
                </p>
            </div>

            @if($isMine)
            <x-avatar :user="$sender" size="8" rounded="full"/>
            @endif

        </div>
        @endforeach
        @endif
    </div>

    {{-- Formulaire envoi avec support de fichier / pièce jointe --}}
    <div class="px-6 py-4 border-t border-gray-100 bg-white">
        
        {{-- Bannière d'avertissement dynamique données bancaires --}}
        <div id="bank-warning-banner" class="hidden mb-3 px-4 py-2.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-medium flex items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span><strong>Sécurité Kimboo :</strong> Le partage de coordonnées bancaires ou cartes de paiement est formellement interdit. <em>(Les numéros de téléphone sont autorisés).</em></span>
            </div>
            <span class="text-[10px] font-extrabold text-red-600 uppercase tracking-wider shrink-0 bg-red-100 px-2 py-0.5 rounded">Interdit</span>
        </div>

        {{-- Badge de prévisualisation du fichier sélectionné --}}
        <div id="file-preview-bar" class="hidden mb-3 p-2.5 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-between gap-3 animate-fadeIn">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p id="file-name-display" class="text-xs font-bold text-black truncate"></p>
                    <p id="file-size-display" class="text-[10px] text-gray-400"></p>
                </div>
            </div>
            <button type="button" onclick="removeSelectedFile()" class="p-1 rounded-lg text-gray-400 hover:text-red-600 hover:bg-gray-100 transition" title="Supprimer le fichier">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="message-form" action="{{ route('messages.send', $contact->id) }}" method="POST" enctype="multipart/form-data"
              class="flex items-end gap-2.5" onsubmit="return handleMessageSubmit(event)">
            @csrf

            {{-- Input fichier invisible --}}
            <input type="file" id="attachment-input" name="attachment"
                   class="hidden"
                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.webp,.gif,.txt,.zip"
                   onchange="handleFileSelected(this)">

            {{-- Bouton Trombone / Joindre un fichier --}}
            <button type="button" onclick="document.getElementById('attachment-input').click()"
                    class="w-11 h-11 rounded-2xl flex items-center justify-center text-gray-500 bg-gray-50 hover:bg-gray-100 hover:text-black border border-gray-200 transition shrink-0 cursor-pointer shadow-2xs"
                    title="Joindre un fichier (PDF, image, document...)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                </svg>
            </button>

            {{-- Zone de texte --}}
            <textarea name="content" rows="1"
                placeholder="{{ $isAssistance ? 'Décrivez votre demande ou joignez un document...' : 'Écrire un message à ' . $contact->name . '...' }}"
                class="flex-1 border-2 border-gray-100 rounded-2xl px-4 py-3 text-xs sm:text-sm outline-none focus:border-yellow-400 transition bg-gray-50 resize-none"
                id="message-input"
                oninput="checkMessageContent(this)"
                onkeydown="if(event.key==='Enter' && !event.shiftKey){ event.preventDefault(); if(validateAndSubmit()){ this.form.submit(); } }"></textarea>
            
            {{-- Bouton Envoyer --}}
            <button type="submit" id="send-btn"
                class="w-11 h-11 rounded-2xl flex items-center justify-center text-black transition hover:opacity-90 shrink-0 shadow-xs cursor-pointer"
                style="background:#FCB315;" title="Envoyer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </form>
    </div>
</div>

{{-- Modal Popup d'avertissement données bancaires interdites --}}
<div id="bank-block-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs hidden">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-gray-100 text-center transform transition-all scale-100">
        <div class="w-14 h-14 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-4 shadow-xs">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h3 class="text-base sm:text-lg font-bold text-black mb-2" style="font-family:'Poppins',sans-serif;">Données bancaires interdites</h3>
        <p class="text-xs text-gray-600 leading-relaxed mb-6">
            Votre message contient des informations financières sensibles (carte bancaire, IBAN, RIB ou code secret). Pour votre sécurité et la conformité de Kimboo, ce message ne peut pas être envoyé.<br><br>
            <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-800 text-[11px] font-semibold px-3 py-1.5 rounded-lg border border-amber-200">
                <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Remarque : Les numéros de téléphone et le partage de fichiers restent autorisés.</span>
            </span>
        </p>
        <button type="button" onclick="closeBankBlockModal()"
                class="w-full py-3 px-5 rounded-xl font-bold text-xs sm:text-sm text-black transition hover:opacity-90 shadow-sm cursor-pointer"
                style="background:#FCB315;">
            Compris, je modifie mon message
        </button>
    </div>
</div>

<script>
    const container = document.getElementById('messages-container');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }

    const bankPatterns = [
        /\b\d{4}[\s\-]\d{4}[\s\-]\d{4}[\s\-]\d{4}\b/,
        /\b(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|3[47][0-9]{13}|6(?:011|5[0-9]{2})[0-9]{12})\b/,
        /\b\d{16}\b/,
        /\bIBAN\s?:?\s?[A-Z]{2}\d{2}[\w\s]{10,34}\b/i,
        /\b[A-Z]{2}\d{2}\s?\d{4}\s?\d{4}\s?\d{4}\s?\d{4}\s?\d{2,4}\b/i,
        /\b(CVV|CVC)\s?:?\s?\d{3,4}\b/i,
        /\bRIB\s?:?\s?\d{5}[\s\-]?\d{5}[\s\-]?[\w\d]{11}[\s\-]?\d{2}\b/i,
        /\bcode\s?(secret|pin)\s?:?\s?\d{4,6}\b/i,
        /\bcarte\s+(bancaire|de\s+cr[ée]dit|de\s+paiement|bleue)\b/i,
        /\bnum[ée]ro\s+de\s+carte\b/i,
        /\bcoordonn[ée]es\s+bancaires\b/i,
        /\bcompte\s+bancaire\b/i,
        /\bvirement\s+bancaire\b/i
    ];

    function containsBankData(text) {
        if (!text || typeof text !== 'string') return false;
        return bankPatterns.some(pattern => pattern.test(text));
    }

    function checkMessageContent(textarea) {
        const warningBanner = document.getElementById('bank-warning-banner');
        if (!warningBanner) return;

        if (containsBankData(textarea.value)) {
            warningBanner.classList.remove('hidden');
            textarea.classList.add('border-red-400', 'bg-red-50/20');
            textarea.classList.remove('border-gray-100', 'bg-gray-50');
        } else {
            warningBanner.classList.add('hidden');
            textarea.classList.remove('border-red-400', 'bg-red-50/20');
            textarea.classList.add('border-gray-100', 'bg-gray-50');
        }
    }

    function handleFileSelected(input) {
        const previewBar = document.getElementById('file-preview-bar');
        const nameDisplay = document.getElementById('file-name-display');
        const sizeDisplay = document.getElementById('file-size-display');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            nameDisplay.textContent = file.name;
            
            let formattedSize = '';
            if (file.size >= 1048576) {
                formattedSize = (file.size / 1048576).toFixed(1) + ' Mo';
            } else if (file.size >= 1024) {
                formattedSize = (file.size / 1024).toFixed(0) + ' Ko';
            } else {
                formattedSize = file.size + ' octets';
            }
            sizeDisplay.textContent = formattedSize;
            previewBar.classList.remove('hidden');
        } else {
            previewBar.classList.add('hidden');
        }
    }

    function removeSelectedFile() {
        const fileInput = document.getElementById('attachment-input');
        const previewBar = document.getElementById('file-preview-bar');
        if (fileInput) {
            fileInput.value = '';
        }
        if (previewBar) {
            previewBar.classList.add('hidden');
        }
    }

    function validateAndSubmit() {
        const input = document.getElementById('message-input');
        const fileInput = document.getElementById('attachment-input');

        const textVal = input ? input.value.trim() : '';
        const hasFile = fileInput && fileInput.files && fileInput.files.length > 0;

        if (!textVal && !hasFile) {
            return false;
        }

        if (containsBankData(textVal)) {
            showBankBlockModal();
            return false;
        }

        return true;
    }

    function handleMessageSubmit(e) {
        if (!validateAndSubmit()) {
            if (e) e.preventDefault();
            return false;
        }
        return true;
    }

    function showBankBlockModal() {
        const modal = document.getElementById('bank-block-modal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    function closeBankBlockModal() {
        const modal = document.getElementById('bank-block-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
        const input = document.getElementById('message-input');
        if (input) {
            input.focus();
        }
    }
</script>

@endsection
