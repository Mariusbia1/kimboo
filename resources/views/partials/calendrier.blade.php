{{-- Navigation mois --}}
<div class="flex items-center justify-between mb-6">
    <a href="?mois={{ $moisPrecedent->month }}&annee={{ $moisPrecedent->year }}"
       class="w-9 h-9 rounded-xl flex items-center justify-center bg-white transition hover:bg-gray-100"
       style="box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <h2 class="font-bold text-black text-lg" style="font-family:'Poppins',sans-serif;">
        {{ ucfirst($premierJourMois->locale('fr')->translatedFormat('F Y')) }}
    </h2>
    <a href="?mois={{ $moisSuivant->month }}&annee={{ $moisSuivant->year }}"
       class="w-9 h-9 rounded-xl flex items-center justify-center bg-white transition hover:bg-gray-100"
       style="box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
</div>

{{-- Grille mensuelle --}}
<div class="bg-white rounded-2xl p-6 mb-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">

    {{-- Jours de la semaine --}}
    <div style="display:grid; grid-template-columns: repeat(7, 1fr); gap:4px;" class="mb-2">
        @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $jour)
        <div class="text-center text-sm font-semibold text-gray-400 py-2">{{ $jour }}</div>
        @endforeach
    </div>

    {{-- Cases du mois --}}
    @php
        $debutGrille = $premierJourMois->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
        $finGrille   = $dernierJourMois->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);
        $aujourd_hui = now()->format('j-n-Y');
    @endphp

    <div style="display:grid; grid-template-columns: repeat(7, 1fr); gap:4px;">
        @for($jour = $debutGrille->copy(); $jour->lte($finGrille); $jour->addDay())
        @php
            $jourNum     = $jour->format('j');
            $estMoisActuel = $jour->month == $mois && $jour->year == $annee;
            $estAujourdhui = $jour->format('j-n-Y') === $aujourd_hui;
            $aDesCours   = $estMoisActuel && isset($coursParJour[$jourNum]);
            $nbCours     = $aDesCours ? $coursParJour[$jourNum]->count() : 0;
        @endphp

        <div class="relative rounded-xl p-1.5 min-h-16 flex flex-col items-center
            {{ !$estMoisActuel ? 'opacity-30' : '' }}
            {{ $estAujourdhui ? 'ring-2' : '' }}"
            style="{{ $estAujourdhui ? 'ring-color:#FCB315;' : '' }}
                   {{ $aDesCours ? 'background:#FFF8E7;' : ($estMoisActuel ? 'background:#F9FAFB;' : '') }}">

            <span class="text-sm font-semibold mb-1
                {{ $estAujourdhui ? 'w-6 h-6 rounded-full flex items-center justify-center text-black' : 'text-gray-700' }}"
                style="{{ $estAujourdhui ? 'background:#FCB315;' : '' }}">
                {{ $jourNum }}
            </span>

            @if($aDesCours)
            <div class="flex flex-col gap-0.5 w-full">
                @foreach($coursParJour[$jourNum]->take(2) as $booking)
                <div class="text-sm px-1 py-0.5 rounded text-center truncate font-medium"
                     style="background:#FCB315; color:#000; font-size:9px;">
                    {{ \Carbon\Carbon::parse($booking->scheduled_at)->format('H:i') }}
                </div>
                @endforeach
                @if($nbCours > 2)
                <div class="text-center" style="font-size:9px; color:#FCB315;">+{{ $nbCours - 2 }}</div>
                @endif
            </div>
            @endif
        </div>
        @endfor
    </div>
</div>

{{-- Vue liste --}}
<div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
    <h3 class="font-semibold text-black mb-4 flex items-center gap-2">
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
        </svg>
        Prochains cours
    </h3>

    @if($prochainsCoours->isEmpty())
    <div class="text-center py-8">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3 bg-gray-100">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <p class="text-sm text-gray-400">Aucun cours à venir.</p>
    </div>
    @else
    <div class="space-y-3">
        @foreach($prochainsCoours as $booking)
        @php
            $personne = isset($booking->course) ? $booking->course->teacherProfile->user : $booking->user;
            $titre    = isset($booking->course) ? $booking->course->title : '';
            $matiere  = isset($booking->course) ? $booking->course->category : '';
        @endphp
        <div class="flex items-center gap-4 p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition">

            {{-- Date badge --}}
            <div class="w-12 h-12 rounded-xl flex flex-col items-center justify-center shrink-0 text-black"
                 style="background:#FCB315;">
                <span class="text-sm font-bold leading-none">
                    {{ \Carbon\Carbon::parse($booking->scheduled_at)->format('d') }}
                </span>
                <span class="text-sm leading-none uppercase">
                    {{ \Carbon\Carbon::parse($booking->scheduled_at)->locale('fr')->translatedFormat('M') }}
                </span>
            </div>

            {{-- Avatar + infos --}}
            <x-avatar :user="$personne" size="10" rounded="full"/>

            <div class="flex-1 min-w-0">
                <p class="font-semibold text-black text-sm truncate">{{ $titre }}</p>
                <p class="text-sm text-gray-500">
                    {{ isset($booking->course) ? 'avec ' . $personne->name : $personne->name }}
                    · {{ $matiere }}
                </p>
            </div>

            {{-- Heure + montant --}}
            <div class="text-right shrink-0">
                <p class="font-bold text-black text-sm">
                    {{ \Carbon\Carbon::parse($booking->scheduled_at)->format('H:i') }}
                </p>
                <p class="text-sm font-semibold" style="color:#FCB315;">
                    {{ number_format($booking->total_price, 0, ',', ' ') }} FCFA
                </p>
            </div>

            {{-- Durée --}}
            <div class="text-sm text-gray-400 shrink-0 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $booking->duration_hours }}h
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
