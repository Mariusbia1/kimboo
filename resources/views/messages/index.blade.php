@extends('layouts.dashboard')

@section('title', 'Messages')
@section('page-title', 'Messages')
@section('page-subtitle', 'Vos conversations')

@section('content')

<div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 4px 12px rgba(0,0,0,0.06); min-height:500px;">

    @if($conversations->isEmpty())
    <div class="text-center py-20">
        <p class="text-4xl mb-4">💬</p>
        <p class="text-gray-400 text-sm">Aucune conversation pour le moment.</p>
        @if(auth()->user()->role === 'eleve')
        <a href="{{ route('cours.index') }}"
           class="inline-block mt-4 px-6 py-2.5 rounded-xl text-black text-sm font-semibold transition hover:opacity-90"
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

            <!-- Avatar -->
            <div class="w-12 h-12 rounded-full flex items-center justify-center text-lg font-bold text-black shrink-0" style="background:#FCB315;">
                {{ strtoupper(substr($contact->name, 0, 1)) }}
            </div>

            <!-- Infos -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-1">
                    <p class="font-semibold text-black text-sm">{{ $contact->name }}</p>
                    <p class="text-xs text-gray-400">
                        {{ \Carbon\Carbon::parse($message->created_at)->diffForHumans() }}
                    </p>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-xs text-gray-500 truncate">
                        {{ $message->sender_id === auth()->id() ? 'Vous : ' : '' }}
                        {{ Str::limit($message->content, 50) }}
                    </p>
                    @if($unread > 0)
                    <span class="ml-2 w-5 h-5 rounded-full text-white text-xs flex items-center justify-center shrink-0 font-bold" style="background:#FCB315;">
                        {{ $unread }}
                    </span>
                    @endif
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @endif
</div>

@endsection
