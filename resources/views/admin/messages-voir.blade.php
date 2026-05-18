@extends('layouts.dashboard')

@section('title', 'Conversation')
@section('page-title', 'Conversation')
@section('page-subtitle', $user1->name . ' — ' . $user2->name)

@section('content')

<div class="bg-white rounded-2xl overflow-hidden flex flex-col"
     style="box-shadow:0 4px 12px rgba(0,0,0,0.06); height:calc(100vh - 200px);">

    {{-- Header --}}
    <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-100">
        <a href="{{ route('admin.messages.conversations') }}" class="text-gray-400 hover:text-black transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>

        <div class="flex items-center -space-x-2">
            <x-avatar :user="$user1" size="10" rounded="full"/>
            <x-avatar :user="$user2" size="10" rounded="full"/>
        </div>

        <div class="flex-1">
            <p class="font-semibold text-black text-sm">{{ $user1->name }} — {{ $user2->name }}</p>
            <p class="text-sm text-gray-400">{{ $messages->count() }} messages</p>
        </div>

        {{-- Badge admin --}}
        <span class="px-3 py-1.5 rounded-xl text-sm font-semibold bg-gray-100 text-gray-600 flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Mode surveillance
        </span>
    </div>

    {{-- Messages --}}
    <div class="flex-1 overflow-y-auto px-6 py-4" id="messages-container">

        @if($messages->isEmpty())
        <div class="text-center py-10">
            <p class="text-gray-400 text-sm">Aucun message dans cette conversation.</p>
        </div>
        @else

        @php $lastDate = null; @endphp
        @foreach($messages as $message)
        @php
            $isUser1 = $message->sender_id === $user1->id;
            $sender  = $isUser1 ? $user1 : $user2;
            $msgDate = \Carbon\Carbon::parse($message->created_at)->format('d/m/Y');

            $hasAlert = \App\Models\MessageAlert::where('message_id', $message->id)->exists();
        @endphp

        {{-- Séparateur date --}}
        @if($msgDate !== $lastDate)
        <div class="flex items-center gap-3 my-4">
            <div class="flex-1 h-px bg-gray-100"></div>
            <span class="text-sm text-gray-400 shrink-0">
                {{ \Carbon\Carbon::parse($message->created_at)->isToday() ? "Aujourd'hui" :
                   (\Carbon\Carbon::parse($message->created_at)->isYesterday() ? 'Hier' : $msgDate) }}
            </span>
            <div class="flex-1 h-px bg-gray-100"></div>
        </div>
        @php $lastDate = $msgDate; @endphp
        @endif

        {{-- Bulle --}}
        <div class="flex {{ $isUser1 ? 'justify-start' : 'justify-end' }} items-end gap-2 mb-3">

            @if($isUser1)
            <x-avatar :user="$sender" size="7" rounded="full"/>
            @endif

            <div class="max-w-xs lg:max-w-md">
                {{-- Badge alerte si message suspect --}}
                @if($hasAlert)
                <div class="flex items-center gap-1 mb-1 {{ $isUser1 ? '' : 'justify-end' }}">
                    <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="text-sm text-red-500 font-medium">
                        {{ $message->is_blocked ? 'Message bloqué' : 'Signalé' }}
                    </span>
                </div>
                @endif

                <div class="px-4 py-3 rounded-2xl text-sm leading-relaxed
                    {{ $isUser1 ? 'bg-gray-100 text-gray-800 rounded-bl-sm' : 'text-black rounded-br-sm' }}
                    {{ $hasAlert ? 'border-2 border-red-300' : '' }}"
                    style="{{ !$isUser1 ? 'background:#FCB315;' : '' }}">
                    {{ $message->content }}
                </div>

                <p class="text-sm text-gray-400 mt-1 {{ $isUser1 ? 'text-left' : 'text-right' }}">
                    <span class="font-medium text-gray-500">{{ $sender->name }}</span>
                    · {{ \Carbon\Carbon::parse($message->created_at)->format('H:i') }}
                </p>
            </div>

            @if(!$isUser1)
            <x-avatar :user="$sender" size="7" rounded="full"/>
            @endif

        </div>
        @endforeach
        @endif
    </div>
</div>

<script>
    const container = document.getElementById('messages-container');
    container.scrollTop = container.scrollHeight;
</script>

@endsection
