@extends('layouts.dashboard')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Bienvenue, ' . auth()->user()->name)

@section('content')

@if(session('success'))
<div class="mb-6 px-4 py-3 rounded-xl text-sm font-medium text-green-700 bg-green-100 flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
</div>
@endif

{{-- Stats --}}
<p class="text-sm font-semibold text-gray-400 uppercase tracking-widest mb-3">Aperçu</p>
<div class="grid grid-cols-1 gap-4 mb-8 sm:grid-cols-2 xl:grid-cols-5">

    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm text-gray-400">Cours publiés</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#FCB31520;">
                <svg class="w-4 h-4" style="color:#FCB315;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">{{ $courses->count() }}</p>
        <p class="text-sm text-gray-400 mt-1">cours au total</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm text-gray-400">Réservations</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#6366f120;">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">{{ $reservationsCount }}</p>
        <p class="text-sm text-gray-400 mt-1">réservations</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm text-gray-400">Élèves</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#10b98120;">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4a4 4 0 11-8 0 4 4 0 018 0zm6 4a2 2 0 100-4 2 2 0 000 4zM3 16a2 2 0 100-4 2 2 0 000 4z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">{{ $nombreEleves }}</p>
        <p class="text-sm text-gray-400 mt-1">élèves uniques</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm text-gray-400">Cours donnés</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#1A2B3C20;">
                <svg class="w-4 h-4" style="color:#1A2B3C;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-black" style="font-family:'Poppins',sans-serif;">{{ $totalCoursDonnes }}</p>
        <p class="text-sm text-gray-400 mt-1">terminés</p>
    </div>

    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm text-gray-400">Cagnotte ce mois</span>
            <span class="w-8 h-8 rounded-xl flex items-center justify-center bg-green-50">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </span>
        </div>
        <p class="text-3xl font-bold text-green-500" style="font-family:'Poppins',sans-serif;">{{ number_format($cagnotteMensuelle, 0, ',', ' ') }}</p>
        <p class="text-sm text-gray-400 mt-1">FCFA</p>
    </div>

</div>

{{-- Calendrier + Cours --}}
<div class="grid grid-cols-1 gap-6 mb-8 xl:grid-cols-2">

    {{-- Calendrier --}}
    <div class="bg-white rounded-2xl p-4 sm:p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <h2 class="font-semibold text-black mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Calendrier — Cette semaine & la suivante
        </h2>

        @if($reservationsSemaine->isEmpty())
        <div class="text-center py-10">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3 bg-gray-100">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-sm text-gray-400">Aucun cours prévu cette semaine.</p>
        </div>
        @else
        <div class="space-y-3">
            @foreach($reservationsSemaine as $booking)
            <div class="flex flex-col gap-3 p-3 rounded-xl bg-gray-50 sm:flex-row sm:items-center sm:gap-4">
                <x-avatar :user="$booking->user" size="10" rounded="full"/>
                <div class="flex-1">
                    <p class="font-semibold text-black text-sm">{{ $booking->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $booking->course->title }}</p>
                </div>
                <div class="text-left sm:text-right">
                    <p class="text-sm font-bold text-black">
                        {{ \Carbon\Carbon::parse($booking->scheduled_at)->format('d/m') }}
                    </p>
                    <p class="text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($booking->scheduled_at)->format('H:i') }}
                    </p>
                </div>
                <div>
                    @if($booking->status === 'confirmé')
                    <span class="text-sm px-2 py-1 rounded-full bg-green-100 text-green-700">Confirmé</span>
                    @elseif($booking->status === 'en_attente')
                    <span class="text-sm px-2 py-1 rounded-full bg-amber-100 text-amber-700">En attente</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Mes cours --}}
    <div class="bg-white rounded-2xl p-4 sm:p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <div class="flex flex-col gap-3 mb-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-black flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Mes cours
            </h2>
            <a href="{{ route('professeur.create-cours') }}"
               class="text-sm font-semibold text-black px-4 py-2 rounded-xl flex items-center gap-1.5 transition hover:opacity-90"
               style="background:#FCB315;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Ajouter
            </a>
        </div>

        @if($courses->isEmpty())
        <div class="text-center py-10">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3 bg-gray-100">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <p class="text-sm text-gray-400">Vous n'avez pas encore publié de cours.</p>
        </div>
        @else
        <div class="space-y-3">
            @foreach($courses as $course)
            <div class="flex flex-col gap-3 p-4 rounded-xl border border-gray-100 bg-gray-50 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <p class="font-medium text-black text-sm">{{ $course->title }}</p>
                        @if($course->is_group)
                        <span class="text-sm px-2 py-0.5 rounded-full font-medium" style="background:#FFF8E7; color:#FCB315;">
                            Groupe · {{ $course->max_students }} max
                        </span>
                        @endif
                        @if($course->status === 'pending')
                        <span class="text-sm px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">En attente</span>
                        @elseif($course->status === 'approved')
                        <span class="text-sm px-2 py-0.5 rounded-full bg-green-100 text-green-700">Approuvé</span>
                        @elseif($course->status === 'rejected')
                        <span class="text-sm px-2 py-0.5 rounded-full bg-red-100 text-red-700">Refusé</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500">{{ $course->category }} · {{ $course->level }} · {{ $course->format }}</p>
                </div>
                <div class="text-left sm:text-right shrink-0">
                    <p class="font-bold text-sm text-black">{{ number_format($course->price_per_hour, 0, ',', ' ') }} FCFA/h</p>
                    <span class="text-sm px-2 py-0.5 rounded-full {{ $course->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $course->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

{{-- Avis reçus --}}
<p class="text-sm font-semibold text-gray-400 uppercase tracking-widest mb-3">Avis reçus</p>
<div class="bg-white rounded-2xl p-4 sm:p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">

    @if($avisParCours->isEmpty())
    <div class="text-center py-10">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3 bg-gray-100">
            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
            </svg>
        </div>
        <p class="font-semibold text-black mb-1">Aucun avis pour le moment</p>
        <p class="text-sm text-gray-400">Les avis de vos élèves apparaîtront ici après chaque cours terminé.</p>
    </div>
    @else

    {{-- Tabs par cours --}}
    <div class="flex gap-2 mb-6 flex-wrap">
        @foreach($avisParCours as $courseId => $avis)
        @php $cours = $avis->first()->course; @endphp
        <button onclick="showAvis('cours-{{ $courseId }}')"
            id="tab-avis-{{ $courseId }}"
            class="avis-tab px-4 py-2 rounded-xl text-sm font-semibold transition"
            style="{{ $loop->first ? 'background:#FCB315; color:#000;' : 'background:#f3f4f6; color:#666;' }}">
            {{ $cours->title }}
            <span class="ml-1 text-sm opacity-70">({{ $avis->count() }})</span>
        </button>
        @endforeach
    </div>

    {{-- Contenu avis par cours --}}
    @foreach($avisParCours as $courseId => $avis)
    @php
        $cours        = $avis->first()->course;
        $moyenneAvis  = round($avis->avg('rating'), 1);
        $totalAvis    = $avis->count();
    @endphp
    <div id="cours-{{ $courseId }}" class="avis-content {{ $loop->first ? '' : 'hidden' }}">

        {{-- En-tête cours --}}
        <div class="flex flex-col gap-3 mb-4 p-4 rounded-xl bg-gray-50 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="font-semibold text-black">{{ $cours->title }}</p>
                <p class="text-sm text-gray-500">{{ $cours->category }} · {{ $totalAvis }} avis</p>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex gap-0.5">
                    @for($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4" fill="{{ $i <= round($moyenneAvis) ? '#FCB315' : '#E5E7EB' }}" viewBox="0 0 24 24">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                    @endfor
                </div>
                <span class="font-bold text-black">{{ $moyenneAvis }}</span>
                <span class="text-sm text-gray-400">/ 5</span>
            </div>
        </div>

        {{-- Liste avis --}}
        <div id="avis-list-{{ $courseId }}">
            @foreach($avis as $index => $review)
            <div class="avis-item-{{ $courseId }} {{ $index >= 3 ? 'hidden' : '' }} mb-4">
                <div class="flex flex-col gap-3 p-4 rounded-xl border border-gray-100 sm:flex-row sm:items-start sm:gap-4">
                    <x-avatar :user="$review->user" size="10" rounded="full"/>
                    <div class="flex-1">
                        <div class="flex flex-col gap-1 mb-1 sm:flex-row sm:items-center sm:justify-between">
                            <p class="font-semibold text-black text-sm">{{ $review->user->name }}</p>
                            <span class="text-sm text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex gap-0.5 mb-2">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-3.5 h-3.5" fill="{{ $i <= $review->rating ? '#FCB315' : '#E5E7EB' }}" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                            </svg>
                            @endfor
                            <span class="text-sm text-gray-500 ml-1">{{ $review->rating }}/5</span>
                        </div>
                        @if($review->comment)
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $review->comment }}</p>
                        @else
                        <p class="text-sm text-gray-400 italic">Aucun commentaire laissé.</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination avis --}}
        @if($totalAvis > 3)
        <div class="flex flex-col gap-3 mt-4 pt-4 border-t border-gray-100 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-gray-400">
                <span id="avis-showing-{{ $courseId }}">1–3</span> sur {{ $totalAvis }} avis
            </p>
            <div class="flex items-center gap-2">
                <button onclick="prevAvis('{{ $courseId }}', {{ $totalAvis }})"
                    id="prev-{{ $courseId }}"
                    disabled
                    class="w-8 h-8 rounded-xl flex items-center justify-center transition bg-gray-100 hover:bg-gray-200 disabled:opacity-30">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <span class="text-sm text-gray-500" id="page-info-{{ $courseId }}">
                    Page 1 / {{ ceil($totalAvis / 3) }}
                </span>
                <button onclick="nextAvis('{{ $courseId }}', {{ $totalAvis }})"
                    id="next-{{ $courseId }}"
                    class="w-8 h-8 rounded-xl flex items-center justify-center transition bg-gray-100 hover:bg-gray-200 disabled:opacity-30">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
        @endif

    </div>
    @endforeach

    @endif
</div>

@endsection

@push('scripts')
<script>
// Tabs avis
function showAvis(id) {
    document.querySelectorAll('.avis-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.avis-tab').forEach(btn => {
        btn.style.background = '#f3f4f6';
        btn.style.color = '#666';
    });
    document.getElementById(id).classList.remove('hidden');
    const tabId = 'tab-avis-' + id.replace('cours-', '');
    document.getElementById(tabId).style.background = '#FCB315';
    document.getElementById(tabId).style.color = '#000';
}

// Pagination avis
const avisPages = {};
const PER_PAGE  = 3;

function goToAvisPage(courseId, page, total) {
    const totalPages = Math.ceil(total / PER_PAGE);
    avisPages[courseId] = Math.max(1, Math.min(page, totalPages));
    const current = avisPages[courseId];
    const start   = (current - 1) * PER_PAGE;
    const end     = start + PER_PAGE;

    document.querySelectorAll('.avis-item-' + courseId).forEach((item, index) => {
        item.classList.toggle('hidden', index < start || index >= end);
    });

    const showingStart = start + 1;
    const showingEnd   = Math.min(end, total);

    const showingEl = document.getElementById('avis-showing-' + courseId);
    const pageInfoEl = document.getElementById('page-info-' + courseId);
    const prevBtn   = document.getElementById('prev-' + courseId);
    const nextBtn   = document.getElementById('next-' + courseId);

    if (showingEl)  showingEl.textContent  = showingStart + '–' + showingEnd;
    if (pageInfoEl) pageInfoEl.textContent = 'Page ' + current + ' / ' + totalPages;
    if (prevBtn)    prevBtn.disabled = current <= 1;
    if (nextBtn)    nextBtn.disabled = current >= totalPages;
}

function nextAvis(courseId, total) {
    if (!avisPages[courseId]) avisPages[courseId] = 1;
    goToAvisPage(courseId, avisPages[courseId] + 1, total);
}

function prevAvis(courseId, total) {
    if (!avisPages[courseId]) avisPages[courseId] = 1;
    goToAvisPage(courseId, avisPages[courseId] - 1, total);
}
</script>
@endpush
