@extends('layouts.dashboard')

@section('title', 'Conversations')
@section('page-title', 'Surveillance des conversations')
@section('page-subtitle', 'Toutes les conversations entre utilisateurs')

@section('content')

<div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="font-semibold text-black">Toutes les conversations</h2>
            <p class="text-xs text-gray-400 mt-0.5">{{ $conversations->count() }} conversations actives</p>
        </div>
        <a href="{{ route('admin.messages.alertes') }}"
           class="text-sm font-semibold px-4 py-2 rounded-xl text-white transition hover:opacity-90 flex items-center gap-2 bg-red-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            Voir les alertes
        </a>
    </div>

    @if($conversations->isEmpty())
    <div class="text-center py-12">
        <p class="text-sm text-gray-400">Aucune conversation pour le moment.</p>
    </div>
    @else
    <div class="divide-y divide-gray-50">
        @foreach($conversations as $message)
        @php
            $user1 = $message->sender;
            $user2 = $message->receiver;
            $hasAlert = \App\Models\MessageAlert::where(function($q) use ($user1, $user2) {
                $q->where('sender_id', $user1->id)->where('receiver_id', $user2->id);
            })->orWhere(function($q) use ($user1, $user2) {
                $q->where('sender_id', $user2->id)->where('receiver_id', $user1->id);
            })->where('status', 'pending')->exists();
        @endphp
        <a href="{{ route('admin.messages.voir', [$user1->id, $user2->id]) }}"
           class="flex items-center gap-4 px-4 py-4 hover:bg-gray-50 transition rounded-xl">

            {{-- Avatars --}}
            <div class="flex items-center -space-x-2 shrink-0">
                <x-avatar :user="$user1" size="10" rounded="full"/>
                <x-avatar :user="$user2" size="10" rounded="full"/>
            </div>

            {{-- Infos --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <p class="font-semibold text-black text-sm">
                        {{ $user1->name }} — {{ $user2->name }}
                    </p>
                    @if($hasAlert)
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-600 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Alerte
                    </span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 truncate">{{ Str::limit($message->content, 60) }}</p>
            </div>

            {{-- Date + flèche --}}
            <div class="flex items-center gap-3 shrink-0">
                <span class="text-xs text-gray-400">{{ $message->created_at->diffForHumans() }}</span>
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>
        @endforeach
    </div>
    @endif
</div>

@endsection
