@extends('layouts.dashboard')

@section('title', 'Conversation avec ' . $contact->name)
@section('page-title', $contact->name)
@section('page-subtitle', ucfirst($contact->role))

@section('content')

<div class="bg-white rounded-2xl overflow-hidden flex flex-col" style="box-shadow:0 4px 12px rgba(0,0,0,0.06); height:calc(100vh - 180px);">

    <!-- Header conversation -->
    <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-100">
        <a href="{{ route('messages.index') }}" class="text-gray-400 hover:text-black transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold text-black shrink-0" style="background:#FCB315;">
            {{ strtoupper(substr($contact->name, 0, 1)) }}
        </div>
        <div>
            <p class="font-semibold text-black text-sm">{{ $contact->name }}</p>
            <p class="text-xs text-gray-400 capitalize">{{ $contact->role }}</p>
        </div>
    </div>

    @if(session('error'))
    <div class="px-6 py-3 text-sm font-medium text-red-700 bg-red-50 border-b border-red-100 flex items-center gap-2">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    <!-- Messages -->
    <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4" id="messages-container">

        @if($messages->isEmpty())
        <div class="text-center py-10">
            <p class="text-gray-400 text-sm">Démarrez la conversation avec {{ $contact->name }}</p>
        </div>
        @else
        @foreach($messages as $message)
        @php $isMine = $message->sender_id === auth()->id(); @endphp
        <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
            <div class="max-w-xs lg:max-w-md">
                <!-- Bulle message -->
                <div class="px-4 py-3 rounded-2xl text-sm leading-relaxed
                    {{ $isMine ? 'text-black rounded-br-sm' : 'bg-gray-100 text-gray-800 rounded-bl-sm' }}"
                    style="{{ $isMine ? 'background:#FCB315;' : '' }}">
                    {{ $message->content }}
                </div>
                <!-- Heure -->
                <p class="text-xs text-gray-400 mt-1 {{ $isMine ? 'text-right' : 'text-left' }}">
                    {{ \Carbon\Carbon::parse($message->created_at)->format('H:i') }}
                    @if($isMine)
                    · {{ $message->is_read ? 'Lu' : 'Envoyé' }}
                    @endif
                </p>
            </div>
        </div>
        @endforeach
        @endif
    </div>

    <!-- Formulaire envoi -->
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
    // Scroll automatique en bas
    const container = document.getElementById('messages-container');
    container.scrollTop = container.scrollHeight;
</script>

@endsection
