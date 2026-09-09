@extends('layouts.dashboard')

@section('title', 'Messages')
@section('page-title', 'Messages')
@section('page-subtitle', 'Vos conversations et échanges')

@section('content')

{{-- Carte d'accès prioritaire à l'Assistance Kimboo --}}
<div class="mb-6 rounded-2xl p-5 sm:p-6 bg-gradient-to-r from-amber-50 via-yellow-50 to-orange-50 border border-amber-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-sm" style="background:#FCB315; color:#000;">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </div>
        <div>
            <div class="flex items-center gap-2">
                <h3 class="font-bold text-black text-sm sm:text-base" style="font-family:'Poppins',sans-serif;">Assistance Officielle Kimboo</h3>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 uppercase tracking-wider">Support actif</span>
            </div>
            <p class="text-xs text-gray-600 mt-0.5 max-w-xl">
                Une question sur un cours, une réservation ou besoin d'aide ? Écrivez à l'assistance Kimboo ou joignez-nous directement sur WhatsApp en cas d'urgence.
            </p>
        </div>
    </div>

    <div class="flex items-center gap-2.5 shrink-0 flex-wrap sm:flex-nowrap">
        <a href="{{ route('messages.assistance') }}"
           class="flex-1 sm:flex-initial px-4 py-2.5 rounded-xl text-xs font-bold text-black transition hover:opacity-90 shadow-xs flex items-center justify-center gap-1.5"
           style="background:#FCB315;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <span>Écrire à l'assistance</span>
        </a>

        @if(!empty($whatsappLink))
        <a href="{{ $whatsappLink }}"
           target="_blank" rel="noopener"
           class="flex-1 sm:flex-initial px-4 py-2.5 rounded-xl text-xs font-bold text-white transition hover:opacity-90 shadow-xs flex items-center justify-center gap-1.5 bg-[#25D366]">
            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
            </svg>
            <span>WhatsApp direct</span>
        </a>
        @endif
    </div>
</div>

<div class="bg-white rounded-2xl overflow-hidden border border-gray-100" style="box-shadow:0 4px 12px rgba(0,0,0,0.06); min-height:480px;">

    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h2 class="font-bold text-black text-sm" style="font-family:'Poppins',sans-serif;">Toutes vos conversations</h2>
            <p class="text-xs text-gray-400 mt-0.5">{{ $conversations->count() }} fil(s) de discussion</p>
        </div>
    </div>

    @if($conversations->isEmpty())
    <div class="text-center py-16 px-4">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-gray-100 text-gray-400">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
        </div>
        <p class="font-bold text-black text-base mb-1">Aucune conversation</p>
        <p class="text-gray-400 text-xs mb-4">Vous n'avez pas encore d'échanges enregistrés.</p>
        <div class="flex items-center justify-center gap-3">
            @if(auth()->user()->role === 'eleve')
            <a href="{{ route('cours.index') }}"
               class="px-5 py-2.5 rounded-xl text-black text-xs font-bold transition hover:opacity-90 shadow-xs"
               style="background:#FCB315;">
                Trouver un professeur
            </a>
            @endif
            <a href="{{ route('messages.assistance') }}"
               class="px-5 py-2.5 rounded-xl text-gray-700 bg-gray-100 hover:bg-gray-200 text-xs font-bold transition">
                Contacter l'assistance
            </a>
        </div>
    </div>

    @else
    <div class="divide-y divide-gray-100">
        @foreach($conversations as $userId => $message)
        @php
            $contact = $message->sender_id === auth()->id() ? $message->receiver : $message->sender;
            if (!$contact) continue;
            
            $isAssistance = $contact->role === 'admin';
            $unread = \App\Models\Message::where('sender_id', $contact->id)
                ->where('receiver_id', auth()->id())
                ->where('is_read', false)
                ->count();
        @endphp
        <a href="{{ route('messages.show', $contact->id) }}"
           class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50/80 transition {{ $isAssistance ? 'bg-amber-50/30' : '' }}">

            {{-- Avatar avec photo --}}
            <div class="relative shrink-0">
                <x-avatar :user="$contact" size="12" rounded="full"/>
            </div>

            {{-- Infos --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-2">
                        <p class="font-bold text-black text-xs sm:text-sm {{ $isAssistance ? 'text-amber-900' : '' }}">
                            {{ $contact->name }}
                        </p>
                        @if(!$isAssistance)
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full
                            {{ $contact->role === 'professeur' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($contact->role) }}
                        </span>
                        @endif
                    </div>
                    <p class="text-[11px] text-gray-400 shrink-0 ml-2">
                        {{ \Carbon\Carbon::parse($message->created_at)->diffForHumans() }}
                    </p>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-xs text-gray-500 truncate">
                        @if($message->sender_id === auth()->id())
                        <span class="text-gray-400 font-medium">Vous : </span>
                        @endif
                        {{ Str::limit($message->content, 65) }}
                    </p>
                    @if($unread > 0)
                    <span class="ml-2 w-5 h-5 rounded-full text-black text-[11px] flex items-center justify-center shrink-0 font-extrabold shadow-xs"
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
