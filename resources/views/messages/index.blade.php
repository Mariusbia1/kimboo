@extends('layouts.dashboard')

@section('title', 'Messages')
@section('page-title', 'Messages')
@section('page-subtitle', 'Vos conversations')

@section('content')

<div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 4px 12px rgba(0,0,0,0.06); min-height:500px;">

    @if($conversations->isEmpty())
    <div class="text-center py-20">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-gray-100">
            <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
        </div>
        <p class="font-semibold text-black mb-1">Aucune conversation</p>
        <p class="text-gray-400 text-sm mb-4">Vous n'avez pas encore de messages.</p>
        @if(auth()->user()->role === 'eleve')
        <a href="{{ route('cours.index') }}"
           class="inline-block px-6 py-2.5 rounded-xl text-black text-sm font-semibold transition hover:opacity-90"
           style="background:#FCB315;">
            Trouver un professeur
        </a>
        @endif
    </div>

    @else
    <div class="divide-y divide-gray-50">
        @foreach($conversations as $userId => $message)
        @php
            $contact = $message->sender_id === auth()->id() ? $message->receiver : $message->sender;
            $unread = \App\Models\Message::where('sender_id', $contact->id)
                ->where('receiver_id', auth()->id())
                ->where('is_read', false)
                ->count();
        @endphp
        <a href="{{ route('messages.show', $contact->id) }}"
           class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition">

            {{-- Avatar avec photo --}}
            <div class="relative shrink-0">
                <x-avatar :user="$contact" size="12" rounded="full"/>
                {{-- Badge en ligne (optionnel) --}}
            </div>

            {{-- Infos --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-2">
                        <p class="font-semibold text-black text-sm">{{ $contact->name }}</p>
                        <span class="text-xs px-2 py-0.5 rounded-full
                            {{ $contact->role === 'professeur' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-500' }}">
                            {{ ucfirst($contact->role) }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-400 shrink-0 ml-2">
                        {{ \Carbon\Carbon::parse($message->created_at)->diffForHumans() }}
                    </p>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-xs text-gray-500 truncate">
                        @if($message->sender_id === auth()->id())
                        <span class="text-gray-400">Vous : </span>
                        @endif
                        {{ Str::limit($message->content, 55) }}
                    </p>
                    @if($unread > 0)
                    <span class="ml-2 w-5 h-5 rounded-full text-black text-xs flex items-center justify-center shrink-0 font-bold"
                          style="background:#FCB315;">
                        {{ $unread }}
                    </span>
                    @endif
                </div>
            </div>

            {{-- Flèche --}}
            <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @endforeach
    </div>
    @endif

</div>

@endsection
