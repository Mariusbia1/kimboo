@extends('layouts.dashboard')

@section('title', 'Gestion des cours')
@section('page-title', 'Cours')
@section('page-subtitle', 'Validez et gérez les cours soumis par les professeurs')

@section('content')

@if(session('success'))
<div class="mb-6 px-4 py-3 rounded-xl text-sm font-medium text-green-700 bg-green-100 flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
</div>
@endif

@if(session('info'))
<div class="mb-6 px-4 py-3 rounded-xl text-sm font-medium text-blue-700 bg-blue-100 flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('info') }}
</div>
@endif

{{-- Tabs --}}
<div class="flex gap-2 mb-6">
    <button onclick="showTab('pending')" id="tab-pending"
        class="tab-btn px-5 py-2.5 rounded-xl text-sm font-semibold transition flex items-center gap-2"
        style="background:#FCB315; color:#000;">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        En attente
        @if($coursPending->count() > 0)
        <span class="w-5 h-5 rounded-full bg-black text-white text-sm flex items-center justify-center font-bold">
            {{ $coursPending->count() }}
        </span>
        @endif
    </button>

    <button onclick="showTab('approved')" id="tab-approved"
        class="tab-btn px-5 py-2.5 rounded-xl text-sm font-semibold transition flex items-center gap-2"
        style="background:#fff; color:#666; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Approuvés ({{ $coursApproved->count() }})
    </button>

    <button onclick="showTab('rejected')" id="tab-rejected"
        class="tab-btn px-5 py-2.5 rounded-xl text-sm font-semibold transition flex items-center gap-2"
        style="background:#fff; color:#666; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        Refusés ({{ $coursRejected->count() }})
    </button>
</div>

{{-- TAB : En attente --}}
<div id="tab-content-pending">
    @if($coursPending->isEmpty())
    <div class="bg-white rounded-2xl p-12 text-center" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-gray-100">
            <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="font-semibold text-black mb-1">Aucun cours en attente</p>
        <p class="text-sm text-gray-400">Tous les cours ont été traités.</p>
    </div>
    @else
    <div class="space-y-4">
        @foreach($coursPending as $cours)
        <div onclick='openAdminCourseModal(@json($cours))'
             class="bg-white rounded-2xl p-6 border border-gray-100 hover:border-amber-300 hover:shadow-lg transition-all duration-200 cursor-pointer group"
             style="box-shadow:0 4px 12px rgba(0,0,0,0.04);">
            <div class="flex items-start justify-between gap-4">

                {{-- Infos cours --}}
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-100 text-amber-800 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            En attente
                        </span>
                        <span class="text-xs text-gray-400">{{ $cours->created_at->diffForHumans() }}</span>
                    </div>

                    <h3 class="font-bold text-black text-lg mb-1 group-hover:text-amber-900 transition-colors flex items-center gap-2">
                        <span>{{ $cours->title }}</span>
                        <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </h3>
                    <p class="text-sm text-gray-600 mb-3 leading-relaxed">{{ Str::limit($cours->description, 150) }}</p>

                    <div class="flex flex-wrap gap-3 text-xs text-gray-500">
                        {{-- Nom prof cliquable --}}
                        <span class="flex items-center gap-1.5 font-bold text-gray-800 bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-100">
                            <x-avatar :user="$cours->teacherProfile->user" size="5" rounded="full"/>
                            <span>{{ $cours->teacherProfile->user->name }}</span>
                        </span>

                        <span class="flex items-center gap-1 bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-100">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            {{ $cours->category }}
                        </span>

                        <span class="flex items-center gap-1 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200/50 font-bold text-amber-900">
                            {{ number_format($cours->price_per_hour, 0, ',', ' ') }} FCFA/h
                        </span>

                        <span class="flex items-center gap-1 bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-100">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            {{ $cours->level }}
                        </span>

                        <span class="flex items-center gap-1 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-100 text-blue-800 font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                            </svg>
                            {{ $cours->formatted_format }}
                        </span>
                    </div>
                </div>

                {{-- Actions rapides --}}
                <div class="flex flex-col gap-2 shrink-0 min-w-36" onclick="event.stopPropagation();">
                    {{-- Voir détails --}}
                    <button type="button"
                        onclick='openAdminCourseModal(@json($cours))'
                        class="w-full text-xs px-3.5 py-2 rounded-xl text-black font-bold flex items-center justify-center gap-1.5 transition hover:opacity-90 bg-[#FCB315] shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <span>Voir détails</span>
                    </button>

                    {{-- Approuver --}}
                    <form method="POST" action="{{ route('admin.cours.approuver', $cours->id) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                            class="w-full text-xs px-3.5 py-2 rounded-xl text-white font-bold flex items-center justify-center gap-1.5 transition hover:bg-emerald-700 bg-emerald-600 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Approuver</span>
                        </button>
                    </form>

                    {{-- Refuser --}}
                    <button onclick="toggleRefus({{ $cours->id }})"
                        class="w-full text-xs px-3.5 py-2 rounded-xl text-red-700 font-bold flex items-center justify-center gap-1.5 transition hover:bg-red-100 bg-red-50 border border-red-200/60">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span>Refuser</span>
                    </button>
                </div>
            </div>

            {{-- Formulaire refus --}}
            <div id="refus-{{ $cours->id }}" class="hidden mt-4 pt-4 border-t border-gray-100" onclick="event.stopPropagation();">
                <form method="POST" action="{{ route('admin.cours.refuser', $cours->id) }}">
                    @csrf @method('PATCH')
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Raison du refus (optionnelle)</label>
                    <textarea name="reason" rows="2"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-xs outline-none focus:border-[#FCB315] transition resize-none"
                        placeholder="Expliquez pourquoi ce cours est refusé..."></textarea>
                    <div class="flex gap-2 mt-2">
                        <button type="submit"
                            class="text-xs px-4 py-2 rounded-xl text-white font-bold bg-red-600 transition hover:bg-red-700 shadow-sm">
                            Confirmer le refus
                        </button>
                        <button type="button" onclick="toggleRefus({{ $cours->id }})"
                            class="text-xs px-4 py-2 rounded-xl font-bold transition hover:bg-gray-100 text-gray-600">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- TAB : Approuvés --}}
<div id="tab-content-approved" class="hidden">
    @if($coursApproved->isEmpty())
    <div class="bg-white rounded-2xl p-12 text-center" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
        <p class="text-sm text-gray-400">Aucun cours approuvé pour l'instant.</p>
    </div>
    @else
    <div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
        <table class="w-full min-w-[820px] text-sm">
            <thead>
                <tr class="border-b border-gray-100 text-left text-xs font-bold uppercase tracking-wider text-gray-400">
                    <th class="py-3 px-4">Cours</th>
                    <th class="py-3 px-4">Professeur</th>
                    <th class="py-3 px-4">Catégorie</th>
                    <th class="py-3 px-4">Prix</th>
                    <th class="py-3 px-4">Statut</th>
                    <th class="py-3 px-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($coursApproved as $cours)
                <tr onclick='openAdminCourseModal(@json($cours))'
                    class="hover:bg-amber-50/40 transition-colors cursor-pointer group">
                    <td class="py-3.5 px-4 font-bold text-gray-900 group-hover:text-amber-900 transition-colors flex items-center gap-1.5">
                        <span>{{ $cours->title }}</span>
                        <svg class="w-3.5 h-3.5 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </td>
                    <td class="py-3.5 px-4">
                        <div class="flex items-center gap-2 font-medium text-gray-800">
                            <x-avatar :user="$cours->teacherProfile->user" size="6" rounded="full"/>
                            <span>{{ $cours->teacherProfile->user->name }}</span>
                        </div>
                    </td>
                    <td class="py-3.5 px-4 text-gray-600 text-xs">{{ $cours->category }}</td>
                    <td class="py-3.5 px-4 font-extrabold text-gray-900 text-xs">{{ number_format($cours->price_per_hour, 0, ',', ' ') }} FCFA/h</td>
                    <td class="py-3.5 px-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">Approuvé</span>
                    </td>
                    <td class="py-3.5 px-4 text-right">
                        <div class="flex items-center justify-end gap-2" onclick="event.stopPropagation();">
                            <button type="button"
                                onclick='openAdminCourseModal(@json($cours))'
                                class="text-xs font-bold px-3 py-1.5 rounded-xl text-black bg-[#FCB315] hover:opacity-90 transition-all inline-flex items-center gap-1 shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>Détails</span>
                            </button>

                            <form method="POST" action="{{ route('admin.cours.supprimer', $cours->id) }}"
                                  onsubmit="return confirm('Supprimer définitivement ce cours ?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="text-xs px-2.5 py-1.5 rounded-xl font-semibold inline-flex items-center gap-1 transition-colors bg-red-50 text-red-600 hover:bg-red-100 border border-red-200/60"
                                    title="Supprimer définitivement">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- TAB : Refusés --}}
<div id="tab-content-rejected" class="hidden">
    @if($coursRejected->isEmpty())
    <div class="bg-white rounded-2xl p-12 text-center" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
        <p class="text-sm text-gray-400">Aucun cours refusé.</p>
    </div>
    @else
    <div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
        <table class="w-full min-w-[820px] text-sm">
            <thead>
                <tr class="border-b border-gray-100 text-left text-xs font-bold uppercase tracking-wider text-gray-400">
                    <th class="py-3 px-4">Cours</th>
                    <th class="py-3 px-4">Professeur</th>
                    <th class="py-3 px-4">Raison</th>
                    <th class="py-3 px-4">Date</th>
                    <th class="py-3 px-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($coursRejected as $cours)
                <tr onclick='openAdminCourseModal(@json($cours))'
                    class="hover:bg-amber-50/40 transition-colors cursor-pointer group">
                    <td class="py-3.5 px-4 font-bold text-gray-900 group-hover:text-amber-900 transition-colors flex items-center gap-1.5">
                        <span>{{ $cours->title }}</span>
                        <svg class="w-3.5 h-3.5 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </td>
                    <td class="py-3.5 px-4">
                        <div class="flex items-center gap-2 font-medium text-gray-800">
                            <x-avatar :user="$cours->teacherProfile->user" size="6" rounded="full"/>
                            <span>{{ $cours->teacherProfile->user->name }}</span>
                        </div>
                    </td>
                    <td class="py-3.5 px-4 text-red-600 text-xs font-medium italic">{{ $cours->rejection_reason ?? '—' }}</td>
                    <td class="py-3.5 px-4 text-gray-400 text-xs">{{ $cours->updated_at->format('d/m/Y') }}</td>
                    <td class="py-3.5 px-4 text-right">
                        <div class="flex items-center justify-end gap-2" onclick="event.stopPropagation();">
                            <button type="button"
                                onclick='openAdminCourseModal(@json($cours))'
                                class="text-xs font-bold px-3 py-1.5 rounded-xl text-black bg-[#FCB315] hover:opacity-90 transition-all inline-flex items-center gap-1 shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>Détails</span>
                            </button>

                            <form method="POST" action="{{ route('admin.cours.supprimer', $cours->id) }}"
                                  onsubmit="return confirm('Supprimer définitivement ce cours ?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="text-xs px-2.5 py-1.5 rounded-xl font-semibold inline-flex items-center gap-1 transition-colors bg-red-50 text-red-600 hover:bg-red-100 border border-red-200/60"
                                    title="Supprimer définitivement">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- Composant Modal Détails Cours --}}
<x-admin-course-modal />

@endsection

@push('scripts')
<script>
function showTab(tab) {
    ['pending', 'approved', 'rejected'].forEach(t => {
        document.getElementById('tab-content-' + t).classList.add('hidden');
        const btn = document.getElementById('tab-' + t);
        btn.style.background = '#fff';
        btn.style.color = '#666';
        btn.style.boxShadow = '0 2px 8px rgba(0,0,0,0.08)';
    });
    document.getElementById('tab-content-' + tab).classList.remove('hidden');
    const activeBtn = document.getElementById('tab-' + tab);
    activeBtn.style.background = '#FCB315';
    activeBtn.style.color = '#000';
    activeBtn.style.boxShadow = 'none';
}

function toggleRefus(id) {
    document.getElementById('refus-' + id).classList.toggle('hidden');
}

document.addEventListener('DOMContentLoaded', function () {
    @if(session('active_tab'))
    showTab(@json(session('active_tab')));
    @endif
});
</script>
@endpush
