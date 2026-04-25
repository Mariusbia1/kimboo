@extends('layouts.dashboard')

@section('title', 'Conversation avec ' . $contact->name)
@section('page-title', $contact->name)
@section('page-subtitle', ucfirst($contact->role))

@section('content')

<div class="bg-white rounded-2xl overflow-hidden flex flex-col"
     style="box-shadow:0 4px 12px rgba(0,0,0,0.06); height:calc(100vh - 180px);">

    {{-- Header conversation --}}
    <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-100">
        <a href="{{ route('messages.index') }}" class="text-gray-400 hover:text-black transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>

        <x-avatar :user="$contact" size="10" rounded="full"/>

        <div class="flex-1">
            <p class="font-semibold text-black text-sm">{{ $contact->name }}</p>
            <p class="text-xs text-gray-400 capitalize">{{ $contact->role }}</p>
        </div>

        {{-- Lien vers profil si professeur --}}
        @if($contact->role === 'professeur' && $contact->teacherProfile)
        <a href="{{ route('professeur.profil', $contact->teacherProfile->id) }}"
           target="_blank"
           class="text-xs px-3 py-1.5 rounded-xl font-medium flex items-center gap-1.5 transition hover:opacity-90"
           style="background:#FCB315; color:#000;">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Voir le profil
        </a>
        @endif
    </div>

    {{-- Alerte message bloqué --}}
    @if(session('error'))
    <div class="px-6 py-3 text-sm font-medium text-red-700 bg-red-50 border-b border-red-100 flex items-center gap-2">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- Messages --}}
    <div class="flex-1 overflow-y-auto px-6 py-4" id="messages-container">

        @if($messages->isEmpty())
        <div class="text-center py-10">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3 bg-gray-100">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <p class="text-gray-400 text-sm">Démarrez la conversation avec {{ $contact->name }}</p>
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
            <div class="flex-1 h-px bg-gray-100"></div>
            <span class="text-xs text-gray-400 shrink-0">
                {{ \Carbon\Carbon::parse($message->created_at)->isToday() ? "Aujourd'hui" :
                   (\Carbon\Carbon::parse($message->created_at)->isYesterday() ? 'Hier' : $msgDate) }}
            </span>
            <div class="flex-1 h-px bg-gray-100"></div>
        </div>
        @php $lastDate = $msgDate; @endphp
        @endif

        {{-- Bulle message --}}
        <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} items-end gap-2 mb-3">

            {{-- Avatar expéditeur (côté gauche si pas moi) --}}
            @if(!$isMine)
            <x-avatar :user="$sender" size="7" rounded="full"/>
            @endif

            <div class="max-w-xs lg:max-w-md">
                <div class="px-4 py-3 rounded-2xl text-sm leading-relaxed
                    {{ $isMine ? 'rounded-br-sm text-black' : 'bg-gray-100 text-gray-800 rounded-bl-sm' }}"
                    style="{{ $isMine ? 'background:#FCB315;' : '' }}">
                    {{ $message->content }}
                </div>
                <p class="text-xs text-gray-400 mt-1 {{ $isMine ? 'text-right' : 'text-left' }}">
                    {{ \Carbon\Carbon::parse($message->created_at)->format('H:i') }}
                    @if($isMine)
                    ·
                    @if($message->is_read)
                    <span class="text-green-500">Lu</span>
                    @else
                    Envoyé
                    @endif
                    @endif
                </p>
            </div>

            {{-- Avatar expéditeur (côté droit si moi) --}}
            @if($isMine)
            <x-avatar :user="$sender" size="7" rounded="full"/>
            @endif

        </div>
        @endforeach
        @endif
    </div>

    {{-- Formulaire envoi --}}
    <div class="px-6 py-4 border-t border-gray-100">
        <form action="{{ route('messages.send', $contact->id) }}" method="POST"
              class="flex items-end gap-3">
            @csrf
            <textarea name="content" rows="1"
                placeholder="Écrire un message..."
                class="flex-1 border-2 border-gray-100 rounded-2xl px-4 py-3 text-sm outline-none focus:border-yellow-400 transition bg-gray-50 resize-none"
                id="message-input"
                onkeydown="if(event.key==='Enter' && !event.shiftKey){ event.preventDefault(); this.form.submit(); }"></textarea>
            <button type="submit"
                class="w-11 h-11 rounded-2xl flex items-center justify-center text-black transition hover:opacity-90 shrink-0"
                style="background:#FCB315;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </form>
    </div>
</div>

<script>
    const container = document.getElementById('messages-container');
    container.scrollTop = container.scrollHeight;
</script>

@endsection
