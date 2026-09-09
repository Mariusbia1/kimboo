{{-- Modal Détails Cours (Admin) --}}
<div id="admin-course-detail-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6 overflow-y-auto"
     aria-labelledby="course-modal-title" role="dialog" aria-modal="true">

    {{-- Backdrop avec flou --}}
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"
         id="admin-course-modal-backdrop"
         onclick="closeAdminCourseModal()"></div>

    {{-- Conteneur du modal --}}
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl my-8 overflow-hidden transform transition-all duration-300 scale-95 opacity-0 border border-gray-100"
         id="admin-course-modal-panel">

        {{-- Header avec dégradé subtil --}}
        <div class="relative px-6 pt-6 pb-5 bg-gradient-to-r from-gray-900 via-slate-900 to-gray-900 text-white">
            <button onclick="closeAdminCourseModal()"
                    class="absolute top-5 right-5 w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="pr-8">
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <span id="course-modal-status-badge" class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-400 text-black">
                        En attente
                    </span>
                    <span id="course-modal-category-badge" class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/10 text-white border border-white/20">
                        Catégorie
                    </span>
                    <span id="course-modal-level-badge" class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/10 text-gray-300 border border-white/10">
                        Niveau
                    </span>
                </div>
                <h3 id="course-modal-title" class="text-xl font-extrabold text-white leading-tight">Titre du cours</h3>
                <p class="text-xs text-gray-400 mt-1.5">ID Cours : #<span id="course-modal-id">0</span> • Soumis le <span id="course-modal-created-at">—</span></p>
            </div>
        </div>

        {{-- Corps du modal --}}
        <div class="p-6 max-h-[calc(85vh-200px)] overflow-y-auto space-y-6">

            {{-- Grille des caractéristiques clés --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <div class="bg-amber-50/60 rounded-2xl p-3.5 border border-amber-200/50">
                    <span class="text-[11px] text-amber-800 font-medium block mb-0.5">Tarif horaire</span>
                    <span id="course-modal-price" class="text-base font-extrabold text-amber-950 block">0 FCFA/h</span>
                </div>

                <div class="bg-blue-50/60 rounded-2xl p-3.5 border border-blue-200/50">
                    <span class="text-[11px] text-blue-800 font-medium block mb-0.5">Format</span>
                    <span id="course-modal-format" class="text-xs font-bold text-blue-950 block truncate">En ligne & Présentiel</span>
                </div>

                <div class="bg-emerald-50/60 rounded-2xl p-3.5 border border-emerald-200/50">
                    <span class="text-[11px] text-emerald-800 font-medium block mb-0.5">1ère heure offerte</span>
                    <span id="course-modal-free-first" class="text-xs font-bold text-emerald-700 block">Oui (Gratuite)</span>
                </div>

                <div class="bg-purple-50/60 rounded-2xl p-3.5 border border-purple-200/50">
                    <span class="text-[11px] text-purple-800 font-medium block mb-0.5">Type de cours</span>
                    <span id="course-modal-group" class="text-xs font-bold text-purple-950 block truncate">Individuel</span>
                </div>

                <div class="bg-gray-50 rounded-2xl p-3.5 border border-gray-100 col-span-2">
                    <span class="text-[11px] text-gray-500 font-medium block mb-0.5">Lieux autorisés</span>
                    <span id="course-modal-lieux" class="text-xs font-bold text-gray-900 block truncate">—</span>
                </div>
            </div>

            {{-- Zone de déplacement si renseignée --}}
            <div id="course-modal-zone-box" class="bg-gray-50 rounded-2xl p-3.5 border border-gray-100 hidden">
                <span class="text-[11px] text-gray-500 font-medium block mb-0.5">Zone & Rayon d'intervention :</span>
                <span id="course-modal-zone" class="text-xs font-bold text-gray-900 block">—</span>
            </div>

            {{-- Description complète --}}
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Description du cours</p>
                <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                    <p id="course-modal-description" class="text-xs text-gray-700 leading-relaxed whitespace-pre-line">Aucune description détaillée fournie.</p>
                </div>
            </div>

            {{-- Motif de refus si rejeté --}}
            <div id="course-modal-rejection-box" class="bg-red-50 rounded-2xl p-4 border border-red-200/70 hidden">
                <div class="flex items-center gap-2 mb-1">
                    <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <h4 class="text-xs font-bold text-red-800">Motif du refus</h4>
                </div>
                <p id="course-modal-rejection-reason" class="text-xs text-red-700 leading-relaxed italic">—</p>
            </div>

            {{-- Carte Enseignant --}}
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Enseignant responsable</p>
                <div class="bg-white rounded-2xl p-4 border border-gray-200/80 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div id="course-modal-teacher-avatar" class="w-11 h-11 rounded-2xl bg-amber-400 text-black font-extrabold flex items-center justify-center shrink-0 overflow-hidden shadow-sm">
                            <span id="course-modal-teacher-avatar-text">P</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <h4 id="course-modal-teacher-name" class="font-bold text-gray-900 text-sm">Nom Professeur</h4>
                                <span id="course-modal-teacher-verified" class="hidden text-emerald-600" title="Professeur certifié">
                                    <svg class="w-4 h-4 fill-emerald-500 text-white" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                </span>
                            </div>
                            <p id="course-modal-teacher-email" class="text-xs text-gray-400">email@prof.com</p>
                            <p class="text-[11px] text-gray-500 mt-0.5">
                                <span id="course-modal-teacher-ville">—</span> • <span id="course-modal-teacher-phone">—</span>
                            </p>
                        </div>
                    </div>

                    <a id="course-modal-teacher-profile-link" href="javascript:void(0);" target="_blank"
                       class="text-xs font-bold px-3 py-2 rounded-xl text-black bg-[#FCB315] hover:opacity-90 transition-opacity shadow-sm whitespace-nowrap inline-flex items-center gap-1">
                        <span>Voir profil</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
            </div>

            {{-- Formulaire de refus dépliable --}}
            <div id="course-modal-reject-form-box" class="hidden bg-amber-50 rounded-2xl p-4 border border-amber-200">
                <form id="course-modal-reject-form" method="POST" action="">
                    @csrf @method('PATCH')
                    <label class="block text-xs font-bold text-gray-800 mb-1.5">
                        Indiquez la raison du refus (facultatif mais recommandé pour le professeur) :
                    </label>
                    <textarea name="reason" rows="2"
                              class="w-full text-xs rounded-xl border-gray-300 p-2.5 focus:ring-[#FCB315] focus:border-[#FCB315] mb-2.5 bg-white"
                              placeholder="Ex: Titre imprécis, description trop courte, tarif incohérent..."></textarea>
                    <div class="flex items-center justify-end gap-2">
                        <button type="button" onclick="document.getElementById('course-modal-reject-form-box').classList.add('hidden')"
                                class="text-xs px-3 py-1.5 rounded-lg text-gray-600 bg-gray-100 hover:bg-gray-200">
                            Annuler
                        </button>
                        <button type="submit"
                                class="text-xs font-bold px-4 py-1.5 rounded-lg text-white bg-red-600 hover:bg-red-700 transition-colors shadow-sm">
                            Confirmer le refus
                        </button>
                    </div>
                </form>
            </div>

        </div>

        {{-- Actions d'administration en bas --}}
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                {{-- Formulaire d'approbation --}}
                <form id="course-modal-approve-form" method="POST" action="" class="inline-block">
                    @csrf @method('PATCH')
                    <button type="submit" id="course-modal-approve-btn"
                            class="text-xs font-bold px-4 py-2 rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 transition-colors flex items-center gap-1.5 shadow-sm">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Approuver & Publier</span>
                    </button>
                </form>

                {{-- Bouton pour déclencher le refus --}}
                <button type="button" id="course-modal-reject-trigger-btn"
                        onclick="document.getElementById('course-modal-reject-form-box').classList.toggle('hidden')"
                        class="text-xs font-bold px-3.5 py-2 rounded-xl text-amber-800 bg-amber-100 hover:bg-amber-200 transition-colors border border-amber-200 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>Refuser le cours</span>
                </button>
            </div>

            <div class="flex items-center gap-2 ml-auto">
                {{-- Formulaire suppression --}}
                <form id="course-modal-delete-form" method="POST" action="" class="inline-block">
                    @csrf @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Confirmer la suppression définitive de ce cours ?')"
                            class="text-xs font-semibold px-3 py-2 rounded-xl text-red-600 bg-red-50 hover:bg-red-100 transition-colors border border-red-200/60 inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Supprimer</span>
                    </button>
                </form>

                <button type="button" onclick="closeAdminCourseModal()"
                        class="text-xs font-bold px-4 py-2 rounded-xl text-gray-700 bg-gray-200 hover:bg-gray-300 transition-colors">
                    Fermer
                </button>
            </div>
        </div>

    </div>
</div>

<script>
window.openAdminCourseModal = function(course) {
    if (!course) return;

    // Infos de base
    document.getElementById('course-modal-id').textContent = course.id || '—';
    document.getElementById('course-modal-title').textContent = course.title || 'Cours';
    document.getElementById('course-modal-category-badge').textContent = course.category || 'Matière';
    document.getElementById('course-modal-level-badge').textContent = course.level || 'Tous niveaux';
    document.getElementById('course-modal-created-at').textContent = course.created_at ? new Date(course.created_at).toLocaleDateString('fr-FR') : '—';
    document.getElementById('course-modal-price').textContent = `${Number(course.price_per_hour || 0).toLocaleString('fr-FR')} FCFA/h`;
    document.getElementById('course-modal-format').textContent = course.formatted_format || course.format || 'En ligne & Présentiel';
    document.getElementById('course-modal-description').textContent = course.description || 'Aucune description détaillée.';

    // 1ère heure offerte
    const freeFirstEl = document.getElementById('course-modal-free-first');
    if (course.first_course_free) {
        freeFirstEl.textContent = 'Oui (Gratuite)';
        freeFirstEl.className = 'text-xs font-bold text-emerald-700 block';
    } else {
        freeFirstEl.textContent = 'Non';
        freeFirstEl.className = 'text-xs font-bold text-gray-500 block';
    }

    // Type de cours (groupe ou individuel)
    const groupEl = document.getElementById('course-modal-group');
    if (course.is_group) {
        groupEl.textContent = `Groupe (max ${course.max_students || 10} élèves)`;
        groupEl.className = 'text-xs font-bold text-purple-900 block truncate';
    } else {
        groupEl.textContent = 'Individuel';
        groupEl.className = 'text-xs font-bold text-gray-700 block truncate';
    }

    // Lieux
    const lieuxEl = document.getElementById('course-modal-lieux');
    if (Array.isArray(course.lieu_cours) && course.lieu_cours.length > 0) {
        const mapLieux = { 'chez_prof': 'Chez le prof', 'chez_eleve': 'Chez l\'élève', 'webcam': 'En ligne / Webcam' };
        lieuxEl.textContent = course.lieu_cours.map(l => mapLieux[l] || l).join(', ');
    } else {
        lieuxEl.textContent = 'En ligne & Présentiel';
    }

    // Zone de déplacement
    const zoneBox = document.getElementById('course-modal-zone-box');
    const zoneEl = document.getElementById('course-modal-zone');
    const zoneVal = course.formatted_zone_deplacement || course.zone_deplacement;
    if (zoneVal) {
        zoneEl.textContent = zoneVal;
        zoneBox.classList.remove('hidden');
    } else {
        zoneBox.classList.add('hidden');
    }

    // Statut & Rejet
    const statusBadge = document.getElementById('course-modal-status-badge');
    const rejectionBox = document.getElementById('course-modal-rejection-box');
    const rejectionReason = document.getElementById('course-modal-rejection-reason');
    const approveBtn = document.getElementById('course-modal-approve-btn');

    if (course.status === 'approved') {
        statusBadge.textContent = 'Approuvé';
        statusBadge.className = 'px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-400 text-black';
        rejectionBox.classList.add('hidden');
        approveBtn.classList.add('hidden');
    } else if (course.status === 'rejected') {
        statusBadge.textContent = 'Refusé';
        statusBadge.className = 'px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-red-400 text-white';
        if (course.rejection_reason) {
            rejectionReason.textContent = course.rejection_reason;
            rejectionBox.classList.remove('hidden');
        } else {
            rejectionBox.classList.add('hidden');
        }
        approveBtn.classList.remove('hidden');
    } else {
        statusBadge.textContent = 'En attente';
        statusBadge.className = 'px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-400 text-black';
        rejectionBox.classList.add('hidden');
        approveBtn.classList.remove('hidden');
    }

    // Enseignant
    const teacher = (course.teacher_profile && course.teacher_profile.user) ? course.teacher_profile.user : (course.teacherProfile ? course.teacherProfile.user : null);
    const teacherProfile = course.teacher_profile || course.teacherProfile;

    if (teacher) {
        document.getElementById('course-modal-teacher-name').textContent = teacher.name || 'Professeur';
        document.getElementById('course-modal-teacher-email').textContent = teacher.email || '—';
        document.getElementById('course-modal-teacher-ville').textContent = teacher.ville || 'Ville non renseignée';
        document.getElementById('course-modal-teacher-phone').textContent = teacher.phone || 'Tél non renseigné';

        // Avatar
        const avatarContainer = document.getElementById('course-modal-teacher-avatar');
        avatarContainer.textContent = '';
        if (teacher.avatar) {
            const img = document.createElement('img');
            img.src = `/storage/${teacher.avatar}`;
            img.className = 'w-full h-full object-cover';
            img.alt = teacher.name || 'Professeur';
            avatarContainer.appendChild(img);
        } else {
            const initials = teacher.name ? teacher.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase() : 'P';
            const span = document.createElement('span');
            span.className = 'font-extrabold text-sm text-slate-900';
            span.textContent = initials;
            avatarContainer.appendChild(span);
        }

        // Verified
        const verifiedEl = document.getElementById('course-modal-teacher-verified');
        if (teacherProfile && teacherProfile.is_verified) {
            verifiedEl.classList.remove('hidden');
        } else {
            verifiedEl.classList.add('hidden');
        }

        // Profile link
        if (teacherProfile) {
            document.getElementById('course-modal-teacher-profile-link').href = `/professeur/${teacherProfile.id}`;
            document.getElementById('course-modal-teacher-profile-link').classList.remove('hidden');
        } else {
            document.getElementById('course-modal-teacher-profile-link').classList.add('hidden');
        }
    }

    // Actions form urls
    document.getElementById('course-modal-approve-form').action = `/admin/cours/${course.id}/approuver`;
    document.getElementById('course-modal-reject-form').action = `/admin/cours/${course.id}/refuser`;
    document.getElementById('course-modal-delete-form').action = `/admin/cours/${course.id}`;
    document.getElementById('course-modal-reject-form-box').classList.add('hidden');

    // Show modal
    const modal = document.getElementById('admin-course-detail-modal');
    const backdrop = document.getElementById('admin-course-modal-backdrop');
    const panel = document.getElementById('admin-course-modal-panel');

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

window.closeAdminCourseModal = function() {
    const modal = document.getElementById('admin-course-detail-modal');
    const backdrop = document.getElementById('admin-course-modal-backdrop');
    const panel = document.getElementById('admin-course-modal-panel');

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
        const modal = document.getElementById('admin-course-detail-modal');
        if (modal && !modal.classList.contains('hidden')) {
            closeAdminCourseModal();
        }
    }
});
</script>
