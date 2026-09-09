{{-- Modal Détails Utilisateur (Admin) --}}
<div id="admin-user-detail-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6 overflow-y-auto"
     aria-labelledby="modal-title" role="dialog" aria-modal="true">

    {{-- Backdrop avec flou --}}
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"
         id="admin-user-modal-backdrop"
         onclick="closeAdminUserModal()"></div>

    {{-- Conteneur du modal --}}
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl my-8 overflow-hidden transform transition-all duration-300 scale-95 opacity-0 border border-gray-100"
         id="admin-user-modal-panel">

        {{-- Header avec dégradé subtil --}}
        <div class="relative px-6 pt-6 pb-5 bg-gradient-to-r from-gray-900 via-slate-900 to-gray-900 text-white">
            <button onclick="closeAdminUserModal()"
                    class="absolute top-5 right-5 w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="flex items-start gap-4">
                {{-- Avatar --}}
                <div id="user-modal-avatar-container" class="shrink-0 w-16 h-16 rounded-2xl bg-amber-400 text-slate-950 font-bold text-xl flex items-center justify-center shadow-lg border-2 border-white/20 overflow-hidden">
                    <span id="user-modal-avatar-text">U</span>
                </div>

                <div class="flex-1 min-w-0 pr-8">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h3 id="user-modal-name" class="text-xl font-extrabold text-white truncate">Nom Utilisateur</h3>
                        <span id="user-modal-role-badge" class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-400 text-black">
                            Élève
                        </span>
                        <span id="user-modal-suspended-badge" class="hidden items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-red-500/20 text-red-300 border border-red-500/40">
                            <svg class="w-3 h-3 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                            <span>Suspendu</span>
                        </span>
                        <span id="user-modal-verified-badge" class="hidden items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Certifié
                        </span>
                        <span id="user-modal-featured-badge" class="hidden items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                            <svg class="w-3 h-3 text-amber-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span>En avant</span>
                        </span>
                    </div>
                    <p id="user-modal-email" class="text-sm text-gray-300 truncate">email@example.com</p>
                    <p class="text-xs text-gray-400 mt-1">ID Utilisateur : #<span id="user-modal-id">0</span> • Inscrit le <span id="user-modal-created-at">—</span></p>
                </div>
            </div>
        </div>

        {{-- Corps du modal --}}
        <div class="p-6 max-h-[calc(85vh-200px)] overflow-y-auto space-y-6">

            {{-- Bannière d'alerte si compte suspendu --}}
            <div id="user-modal-suspension-banner" class="hidden p-4 rounded-2xl bg-red-50 border border-red-200">
                <div class="flex items-start gap-3">
                    <div class="shrink-0 w-8 h-8 rounded-xl bg-red-100 text-red-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs font-bold text-red-900 uppercase tracking-wider">Ce compte est actuellement suspendu</h4>
                        <p class="text-xs text-red-800 mt-1">
                            <span class="font-bold">Motif : </span>
                            <span id="user-modal-suspension-reason" class="italic">Non précisé</span>
                        </p>
                        <p class="text-[11px] text-red-600 mt-0.5">
                            Suspendu le <span id="user-modal-suspended-at" class="font-medium">—</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Grille Informations Générales --}}
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">Informations générales</p>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <div class="bg-gray-50 rounded-2xl p-3.5 border border-gray-100">
                        <span class="text-[11px] text-gray-400 font-medium block mb-0.5">Téléphone</span>
                        <span id="user-modal-phone" class="text-xs font-bold text-gray-900 block truncate">—</span>
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-3.5 border border-gray-100">
                        <span class="text-[11px] text-gray-400 font-medium block mb-0.5">Ville</span>
                        <span id="user-modal-ville" class="text-xs font-bold text-gray-900 block truncate">—</span>
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-3.5 border border-gray-100">
                        <span class="text-[11px] text-gray-400 font-medium block mb-0.5">Email vérifié</span>
                        <span id="user-modal-email-verified" class="text-xs font-bold text-emerald-600 block">Oui</span>
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-3.5 border border-gray-100">
                        <span class="text-[11px] text-gray-400 font-medium block mb-0.5">Dernière connexion</span>
                        <span id="user-modal-last-login" class="text-xs font-bold text-gray-900 block truncate">—</span>
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-3.5 border border-gray-100">
                        <span class="text-[11px] text-gray-400 font-medium block mb-0.5">Temps passé</span>
                        <span id="user-modal-time-spent" class="text-xs font-bold text-amber-700 block">—</span>
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-3.5 border border-gray-100">
                        <span class="text-[11px] text-gray-400 font-medium block mb-0.5">Statut compte</span>
                        <span id="user-modal-status-text" class="text-xs font-bold text-emerald-600 block">Actif</span>
                    </div>
                </div>
            </div>

            {{-- SECTION SPÉCIFIQUE PROFESSEUR --}}
            <div id="user-modal-prof-section" class="hidden space-y-4 pt-2 border-t border-gray-100">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Profil & Activité Enseignant</p>

                {{-- Statistiques Professeur --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="bg-amber-50/60 rounded-2xl p-3 border border-amber-200/50 text-center">
                        <span class="text-[11px] text-amber-800 font-semibold block mb-0.5">Note globale</span>
                        <span class="text-sm font-extrabold text-amber-900 inline-flex items-center gap-1 justify-center">
                            <svg class="w-3.5 h-3.5 text-amber-500 fill-amber-400 shrink-0" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span id="user-modal-prof-rating">0.0</span>/5
                        </span>
                        <span id="user-modal-prof-reviews-count" class="text-[10px] text-amber-700 block mt-0.5">(0 avis)</span>
                    </div>

                    <div class="bg-blue-50/60 rounded-2xl p-3 border border-blue-200/50 text-center">
                        <span class="text-[11px] text-blue-800 font-semibold block mb-0.5">Tarif indicatif</span>
                        <span id="user-modal-prof-rate" class="text-sm font-extrabold text-blue-900 block">0 FCFA/h</span>
                    </div>

                    <div class="bg-emerald-50/60 rounded-2xl p-3 border border-emerald-200/50 text-center">
                        <span class="text-[11px] text-emerald-800 font-semibold block mb-0.5">Cours créés</span>
                        <span id="user-modal-prof-courses-count" class="text-sm font-extrabold text-emerald-900 block">0</span>
                    </div>

                    <div class="bg-purple-50/60 rounded-2xl p-3 border border-purple-200/50 text-center">
                        <span class="text-[11px] text-purple-800 font-semibold block mb-0.5">Temps réponse</span>
                        <span id="user-modal-prof-response-time" class="text-xs font-extrabold text-purple-900 block truncate">—</span>
                    </div>
                </div>

                {{-- Bio & Présentation --}}
                <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                    <h4 class="text-xs font-bold text-gray-800 mb-1">Biographie / Présentation</h4>
                    <p id="user-modal-prof-bio" class="text-xs text-gray-600 leading-relaxed italic whitespace-pre-line">Aucune biographie rédigée.</p>
                </div>

                {{-- Lieux et Zone de déplacement --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="bg-gray-50 rounded-2xl p-3.5 border border-gray-100">
                        <span class="text-gray-400 font-medium block mb-1">Lieux de cours acceptés :</span>
                        <span id="user-modal-prof-lieux" class="font-semibold text-gray-800">—</span>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-3.5 border border-gray-100">
                        <span class="text-gray-400 font-medium block mb-1">Rayon de déplacement :</span>
                        <span id="user-modal-prof-zone" class="font-semibold text-gray-800">—</span>
                    </div>
                </div>

                {{-- Liste des cours créés --}}
                <div>
                    <h4 class="text-xs font-bold text-gray-800 mb-2">Cours enregistrés par ce professeur</h4>
                    <div id="user-modal-courses-list" class="space-y-2">
                        {{-- Rempli dynamiquement en JS --}}
                    </div>
                </div>
            </div>

            {{-- SECTION SPÉCIFIQUE ÉLÈVE --}}
            <div id="user-modal-eleve-section" class="hidden space-y-3 pt-2 border-t border-gray-100">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Activité Apprenant</p>
                <div class="grid grid-cols-2 gap-3 text-center">
                    <div class="bg-indigo-50/60 rounded-2xl p-3.5 border border-indigo-200/50">
                        <span class="text-[11px] text-indigo-700 font-medium block mb-0.5">Réservations demandées</span>
                        <span id="user-modal-eleve-bookings" class="text-base font-extrabold text-indigo-900 block">0</span>
                    </div>
                    <div class="bg-emerald-50/60 rounded-2xl p-3.5 border border-emerald-200/50">
                        <span class="text-[11px] text-emerald-700 font-medium block mb-0.5">Messages envoyés</span>
                        <span id="user-modal-eleve-messages" class="text-base font-extrabold text-emerald-900 block">0</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- Actions d'administration en bas --}}
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2" id="user-modal-prof-actions">
                {{-- Formulaire Certifier / Décertifier --}}
                <form id="user-modal-certify-form" method="POST" action="" class="inline-block">
                    @csrf @method('PATCH')
                    <button type="submit" id="user-modal-certify-btn"
                            class="text-xs font-bold px-3.5 py-2 rounded-xl text-white bg-slate-900 hover:bg-black transition-colors flex items-center gap-1.5 shadow-sm">
                        <svg class="w-3.5 h-3.5 text-[#FCB315]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Certifier</span>
                    </button>
                </form>

                {{-- Formulaire Mettre en avant / Retirer --}}
                <form id="user-modal-feature-form" method="POST" action="" class="inline-block">
                    @csrf @method('PATCH')
                    <button type="submit" id="user-modal-feature-btn"
                            class="text-xs font-bold px-3.5 py-2 rounded-xl text-black bg-[#FCB315] hover:opacity-95 transition-all flex items-center gap-1.5 shadow-sm">
                        <svg class="w-3.5 h-3.5 fill-black" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span>Mettre en avant</span>
                    </button>
                </form>

                {{-- Lien profil public prof --}}
                <a id="user-modal-public-profile-link" href="javascript:void(0);" target="_blank"
                   class="text-xs font-semibold px-3 py-2 rounded-xl text-gray-700 bg-white hover:bg-gray-100 transition-colors border border-gray-200 inline-flex items-center gap-1">
                    <span>Profil public</span>
                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>

            <div class="flex flex-wrap items-center gap-2 ml-auto">

                {{-- Formulaire Lever la suspension (si suspendu) --}}
                <form id="user-modal-reactivate-form" method="POST" action="" class="hidden">
                    @csrf @method('PATCH')
                    <button type="submit"
                            onclick="return confirm('Confirmer la levée de la suspension pour cet utilisateur ? Son accès à la plateforme sera immédiatement rétabli.')"
                            class="text-xs font-bold px-3.5 py-2 rounded-xl text-emerald-800 bg-emerald-100 hover:bg-emerald-200 transition-colors border border-emerald-300 inline-flex items-center gap-1.5 shadow-sm">
                        <svg class="w-3.5 h-3.5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                        </svg>
                        <span>Lever la suspension</span>
                    </button>
                </form>

                {{-- Formulaire Suspendre (si actif) --}}
                <div id="user-modal-suspend-wrapper" class="relative inline-block">
                    <button type="button" id="user-modal-suspend-toggle-btn"
                            onclick="toggleAdminSuspendBox()"
                            class="text-xs font-bold px-3.5 py-2 rounded-xl text-amber-900 bg-amber-100 hover:bg-amber-200 transition-colors border border-amber-300 inline-flex items-center gap-1.5 shadow-sm">
                        <svg class="w-3.5 h-3.5 text-amber-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        <span>Suspendre</span>
                    </button>

                    {{-- Popover confirmation avec raison --}}
                    <div id="user-modal-suspend-popover" class="hidden absolute right-0 bottom-full mb-2 w-72 sm:w-80 bg-white rounded-2xl shadow-2xl border border-gray-200 p-4 z-30 text-left">
                        <form id="user-modal-suspend-form" method="POST" action="">
                            @csrf @method('PATCH')
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-gray-900 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    Suspendre le compte
                                </span>
                                <button type="button" onclick="toggleAdminSuspendBox(false)" class="text-gray-400 hover:text-gray-700 text-sm font-bold leading-none p-1">✕</button>
                            </div>
                            <p class="text-[11px] text-gray-500 mb-2 leading-tight">
                                L'utilisateur sera immédiatement déconnecté et recevra une notification par email.
                            </p>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1">Motif de la suspension (optionnel) :</label>
                            <textarea name="reason" id="user-modal-suspend-reason-input" rows="2"
                                      placeholder="Ex: Signalement pour comportement inapproprié, vérification requise..."
                                      class="w-full text-xs rounded-xl border-gray-300 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 p-2.5 resize-none text-gray-900 bg-gray-50"></textarea>
                            <div class="mt-3 flex items-center justify-end gap-2">
                                <button type="button" onclick="toggleAdminSuspendBox(false)" class="px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-100 rounded-lg">
                                    Annuler
                                </button>
                                <button type="submit" class="px-3.5 py-1.5 text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 rounded-lg shadow-sm transition-colors">
                                    Confirmer la suspension
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Formulaire suppression compte --}}
                <form id="user-modal-delete-form" method="POST" action="" class="inline-block">
                    @csrf @method('DELETE')
                    <button type="submit" id="user-modal-delete-btn"
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cet utilisateur ?')"
                            class="text-xs font-semibold px-3 py-2 rounded-xl text-red-600 bg-red-50 hover:bg-red-100 transition-colors border border-red-200/60 inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Supprimer</span>
                    </button>
                </form>

                <button type="button" onclick="closeAdminUserModal()"
                        class="text-xs font-bold px-4 py-2 rounded-xl text-gray-700 bg-gray-200 hover:bg-gray-300 transition-colors">
                    Fermer
                </button>
            </div>
        </div>

    </div>
</div>

<script>
window.toggleAdminSuspendBox = function(forceState) {
    const popover = document.getElementById('user-modal-suspend-popover');
    if (!popover) return;
    if (typeof forceState === 'boolean') {
        if (forceState) popover.classList.remove('hidden');
        else popover.classList.add('hidden');
    } else {
        popover.classList.toggle('hidden');
    }
};

window.openAdminUserModal = function(user) {
    if (!user) return;

    // Fermer le popover de suspension s'il était ouvert
    toggleAdminSuspendBox(false);

    // Éléments de base
    document.getElementById('user-modal-id').textContent = user.id || '—';
    document.getElementById('user-modal-name').textContent = user.name || 'Utilisateur';
    document.getElementById('user-modal-email').textContent = user.email || '—';
    document.getElementById('user-modal-phone').textContent = user.phone || 'Non renseigné';
    document.getElementById('user-modal-ville').textContent = user.ville || 'Non renseignée';
    document.getElementById('user-modal-created-at').textContent = user.created_at ? new Date(user.created_at).toLocaleDateString('fr-FR') : '—';
    document.getElementById('user-modal-last-login').textContent = user.last_login_at ? new Date(user.last_login_at).toLocaleString('fr-FR', {dateStyle:'short', timeStyle:'short'}) : 'Jamais';
    document.getElementById('user-modal-time-spent').textContent = user.formatted_time_spent || '0 min';

    // Avatar
    const avatarContainer = document.getElementById('user-modal-avatar-container');
    if (user.avatar) {
        avatarContainer.innerHTML = `<img src="/storage/${user.avatar}" class="w-full h-full object-cover" alt="${user.name}">`;
    } else {
        const initials = user.name ? user.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase() : 'U';
        avatarContainer.innerHTML = `<span class="font-extrabold text-xl text-slate-900">${initials}</span>`;
    }

    // Role badge
    const roleBadge = document.getElementById('user-modal-role-badge');
    if (user.role === 'admin') {
        roleBadge.textContent = 'Admin';
        roleBadge.className = 'px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-purple-500/20 text-purple-300 border border-purple-500/30';
    } else if (user.role === 'professeur') {
        roleBadge.textContent = 'Professeur';
        roleBadge.className = 'px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-500/20 text-blue-300 border border-blue-500/30';
    } else {
        roleBadge.textContent = 'Élève';
        roleBadge.className = 'px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/30';
    }

    // Gestion de la suspension
    const suspendedBadge = document.getElementById('user-modal-suspended-badge');
    const suspensionBanner = document.getElementById('user-modal-suspension-banner');
    const statusTextEl = document.getElementById('user-modal-status-text');
    const reactivateForm = document.getElementById('user-modal-reactivate-form');
    const suspendWrapper = document.getElementById('user-modal-suspend-wrapper');
    const suspendForm = document.getElementById('user-modal-suspend-form');
    const suspendReasonInput = document.getElementById('user-modal-suspend-reason-input');

    if (suspendReasonInput) {
        suspendReasonInput.value = '';
    }

    const isSuspended = !!(user.is_suspended == true || user.is_suspended === 1 || user.is_suspended === '1');

    if (isSuspended) {
        suspendedBadge.classList.remove('hidden');
        suspendedBadge.classList.add('inline-flex');

        suspensionBanner.classList.remove('hidden');
        document.getElementById('user-modal-suspension-reason').textContent = user.suspension_reason || 'Aucun motif renseigné.';
        document.getElementById('user-modal-suspended-at').textContent = user.suspended_at ? new Date(user.suspended_at).toLocaleString('fr-FR', {dateStyle:'medium', timeStyle:'short'}) : 'Date inconnue';

        statusTextEl.textContent = 'Suspendu';
        statusTextEl.className = 'text-xs font-bold text-red-600 block';

        reactivateForm.classList.remove('hidden');
        reactivateForm.action = `/admin/utilisateurs/${user.id}/reactiver`;

        suspendWrapper.classList.add('hidden');
    } else {
        suspendedBadge.classList.add('hidden');
        suspendedBadge.classList.remove('inline-flex');

        suspensionBanner.classList.add('hidden');

        statusTextEl.textContent = 'Actif';
        statusTextEl.className = 'text-xs font-bold text-emerald-600 block';

        reactivateForm.classList.add('hidden');

        if (user.role === 'admin') {
            suspendWrapper.classList.add('hidden');
        } else {
            suspendWrapper.classList.remove('hidden');
            suspendForm.action = `/admin/utilisateurs/${user.id}/suspendre`;
        }
    }

    // Email verified badge
    const emailVerifiedEl = document.getElementById('user-modal-email-verified');
    if (user.email_verified_at) {
        emailVerifiedEl.textContent = 'Vérifié';
        emailVerifiedEl.className = 'text-xs font-bold text-emerald-600 block';
    } else {
        emailVerifiedEl.textContent = 'Non vérifié';
        emailVerifiedEl.className = 'text-xs font-bold text-amber-600 block';
    }

    // Section Professeur
    const profSection = document.getElementById('user-modal-prof-section');
    const profActions = document.getElementById('user-modal-prof-actions');
    const eleveSection = document.getElementById('user-modal-eleve-section');
    const verifiedBadge = document.getElementById('user-modal-verified-badge');
    const featuredBadge = document.getElementById('user-modal-featured-badge');

    if (user.role === 'professeur' && user.teacher_profile) {
        const prof = user.teacher_profile;
        profSection.classList.remove('hidden');
        profActions.classList.remove('hidden');
        eleveSection.classList.add('hidden');

        // Badges certification & mise en avant
        if (prof.is_verified) {
            verifiedBadge.classList.remove('hidden');
            verifiedBadge.classList.add('inline-flex');
        } else {
            verifiedBadge.classList.add('hidden');
            verifiedBadge.classList.remove('inline-flex');
        }

        if (prof.is_featured) {
            featuredBadge.classList.remove('hidden');
            featuredBadge.classList.add('inline-flex');
        } else {
            featuredBadge.classList.add('hidden');
            featuredBadge.classList.remove('inline-flex');
        }

        document.getElementById('user-modal-prof-rating').textContent = prof.rating || '0.0';
        document.getElementById('user-modal-prof-reviews-count').textContent = `(${prof.reviews_count || 0} avis)`;
        document.getElementById('user-modal-prof-rate').textContent = prof.hourly_rate ? `${Number(prof.hourly_rate).toLocaleString('fr-FR')} FCFA/h` : 'Non défini';
        document.getElementById('user-modal-prof-response-time').textContent = prof.formatted_response_time || 'En quelques heures';
        document.getElementById('user-modal-prof-bio').textContent = prof.bio || 'Aucune biographie rédigée.';
        document.getElementById('user-modal-prof-zone').textContent = prof.formatted_zone_deplacement || (prof.zone_deplacement ? prof.zone_deplacement : 'Non précisée');

        // Lieux
        if (Array.isArray(prof.lieu_cours) && prof.lieu_cours.length > 0) {
            const mapLieux = { 'chez_prof': 'Chez le prof', 'chez_eleve': 'Chez l\'élève', 'webcam': 'En ligne / Webcam' };
            document.getElementById('user-modal-prof-lieux').textContent = prof.lieu_cours.map(l => mapLieux[l] || l).join(', ');
        } else {
            document.getElementById('user-modal-prof-lieux').textContent = 'En ligne & Présentiel';
        }

        // Cours list
        const courses = prof.courses || [];
        document.getElementById('user-modal-prof-courses-count').textContent = courses.length;
        const coursesList = document.getElementById('user-modal-courses-list');
        if (courses.length > 0) {
            coursesList.innerHTML = courses.map(c => `
                <div class="flex items-center justify-between p-2.5 bg-white rounded-xl border border-gray-200/80 text-xs">
                    <div>
                        <span class="font-bold text-gray-900 block">${c.title}</span>
                        <span class="text-gray-400 text-[11px]">${c.category} • ${c.format || 'En ligne'}</span>
                    </div>
                    <div class="text-right">
                        <span class="font-extrabold text-gray-900 block">${Number(c.price_per_hour).toLocaleString('fr-FR')} F/h</span>
                        <span class="inline-block px-1.5 py-0.2 rounded text-[10px] font-bold ${c.status === 'approved' ? 'bg-emerald-50 text-emerald-700' : (c.status === 'pending' ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700')}">
                            ${c.status === 'approved' ? 'Approuvé' : (c.status === 'pending' ? 'En attente' : 'Refusé')}
                        </span>
                    </div>
                </div>
            `).join('');
        } else {
            coursesList.innerHTML = '<p class="text-xs text-gray-400 italic">Aucun cours créé pour le moment.</p>';
        }

        // Actions certification form
        const certifyForm = document.getElementById('user-modal-certify-form');
        const certifyBtn = document.getElementById('user-modal-certify-btn');
        if (prof.is_verified) {
            certifyForm.action = `/admin/professeurs/${prof.id}/decertifier`;
            certifyBtn.innerHTML = '<svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg><span>Décertifier</span>';
            certifyBtn.className = 'text-xs font-bold px-3 py-2 rounded-xl text-red-600 bg-red-50 hover:bg-red-100 transition-colors border border-red-200/60 flex items-center gap-1.5';
        } else {
            certifyForm.action = `/admin/professeurs/${prof.id}/certifier`;
            certifyBtn.innerHTML = '<svg class="w-3.5 h-3.5 text-[#FCB315]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>Certifier</span>';
            certifyBtn.className = 'text-xs font-bold px-3.5 py-2 rounded-xl text-white bg-slate-900 hover:bg-black transition-colors flex items-center gap-1.5 shadow-sm';
        }

        // Feature form
        const featureForm = document.getElementById('user-modal-feature-form');
        const featureBtn = document.getElementById('user-modal-feature-btn');
        if (prof.is_featured) {
            featureForm.action = `/admin/professeurs/${prof.id}/unfeature`;
            featureBtn.innerHTML = '<svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg><span>Retirer de l\'accueil</span>';
            featureBtn.className = 'text-xs font-bold px-3 py-2 rounded-xl text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors border border-gray-200/60 flex items-center gap-1.5';
        } else {
            featureForm.action = `/admin/professeurs/${prof.id}/feature`;
            featureBtn.innerHTML = '<svg class="w-3.5 h-3.5 fill-black" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg><span>Mettre en avant</span>';
            featureBtn.className = 'text-xs font-bold px-3.5 py-2 rounded-xl text-black bg-[#FCB315] hover:opacity-95 transition-all flex items-center gap-1.5 shadow-sm';
        }

        // Public profile link
        document.getElementById('user-modal-public-profile-link').href = `/professeur/${prof.id}`;
    } else {
        profSection.classList.add('hidden');
        profActions.classList.add('hidden');
        verifiedBadge.classList.add('hidden');
        featuredBadge.classList.add('hidden');

        if (user.role === 'eleve') {
            eleveSection.classList.remove('hidden');
            document.getElementById('user-modal-eleve-bookings').textContent = user.bookings_count ?? (user.bookings ? user.bookings.length : 0);
            document.getElementById('user-modal-eleve-messages').textContent = user.sent_messages_count ?? 0;
        } else {
            eleveSection.classList.add('hidden');
        }
    }

    // Delete form
    const deleteForm = document.getElementById('user-modal-delete-form');
    const deleteBtn = document.getElementById('user-modal-delete-btn');
    if (user.role === 'admin') {
        deleteBtn.classList.add('hidden');
    } else {
        deleteBtn.classList.remove('hidden');
        deleteForm.action = `/admin/utilisateurs/${user.id}`;
    }

    // Show modal
    const modal = document.getElementById('admin-user-detail-modal');
    const backdrop = document.getElementById('admin-user-modal-backdrop');
    const panel = document.getElementById('admin-user-modal-panel');

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';

    setTimeout(() => {
        backdrop.classList.remove('opacity-0');
        backdrop.classList.add('opacity-100');
        panel.classList.remove('scale-95', 'opacity-0');
        panel.classList.add('scale-100', 'opacity-100');
    }, 20);
};

window.closeAdminUserModal = function() {
    const modal = document.getElementById('admin-user-detail-modal');
    const backdrop = document.getElementById('admin-user-modal-backdrop');
    const panel = document.getElementById('admin-user-modal-panel');

    toggleAdminSuspendBox(false);

    backdrop.classList.remove('opacity-100');
    backdrop.classList.add('opacity-0');
    panel.classList.remove('scale-100', 'opacity-100');
    panel.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 250);
};

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('admin-user-detail-modal');
        if (modal && !modal.classList.contains('hidden')) {
            closeAdminUserModal();
        }
    }
});
</script>
