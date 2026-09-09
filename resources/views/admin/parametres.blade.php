@extends('layouts.dashboard')

@section('title', 'Paramètres du site')
@section('page-title', 'Paramètres')
@section('page-subtitle', 'Personnalisez le contenu, les coordonnées, les tarifs et le référencement de Kimboo')

@section('content')

@if(session('success'))
<div class="mb-6 px-4 py-3 rounded-2xl text-sm font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200/70 flex items-center gap-2.5">
    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>{{ session('success') }}</span>
</div>
@endif

<div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100" style="box-shadow:0 4px 20px rgba(0,0,0,0.03);">

    {{-- En-tête des paramètres --}}
    <div class="flex flex-col gap-2 mb-8 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">Configuration de la plateforme</h1>
            <p class="text-xs sm:text-sm text-gray-400 mt-1">Gérez facilement tous les éléments visibles et les options clés du site</p>
        </div>
        <div class="flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200/60 self-start sm:self-auto">
            <span class="w-2 h-2 rounded-full bg-[#FCB315] animate-pulse"></span>
            <span>Mises à jour en direct</span>
        </div>
    </div>

    {{-- Barre des onglets --}}
    <div class="flex items-center gap-2 mb-8 border-b border-gray-100 pb-4 overflow-x-auto">
        <button type="button" onclick="switchSettingsTab('general')" id="tab-btn-general"
                class="settings-tab-btn px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2 transition-all shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span>Général & Coordonnées</span>
        </button>

        <button type="button" onclick="switchSettingsTab('homepage')" id="tab-btn-homepage"
                class="settings-tab-btn px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2 transition-all shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Accueil & Textes</span>
        </button>

        <button type="button" onclick="switchSettingsTab('social')" id="tab-btn-social"
                class="settings-tab-btn px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2 transition-all shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
            </svg>
            <span>Réseaux Sociaux</span>
        </button>

        <button type="button" onclick="switchSettingsTab('tarifs')" id="tab-btn-tarifs"
                class="settings-tab-btn px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2 transition-all shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Tarifs & Commission</span>
        </button>

        <button type="button" onclick="switchSettingsTab('seo')" id="tab-btn-seo"
                class="settings-tab-btn px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2 transition-all shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
            <span>Référencement (SEO)</span>
        </button>
    </div>

    {{-- Formulaire global --}}
    <form method="POST" action="{{ route('admin.parametres.update') }}">
        @csrf
        <input type="hidden" name="active_tab" id="active_tab_input" value="{{ $activeTab ?? 'general' }}"/>

        {{-- ==================== ONGLET 1 : GÉNÉRAL & COORDONNÉES ==================== --}}
        <div id="tab-panel-general" class="settings-panel space-y-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Nom de la plateforme
                    </label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'Kimboo' }}" required
                           class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition"/>
                    <p class="text-[11px] text-gray-400 mt-1">Nom officiel de la marque affiché sur l'ensemble du site</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Devise de paiement
                    </label>
                    <input type="text" name="site_currency" value="{{ $settings['site_currency'] ?? 'FCFA' }}" required
                           class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition"/>
                    <p class="text-[11px] text-gray-400 mt-1">Ex: FCFA, EUR, USD</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Slogan / Tagline
                    </label>
                    <input type="text" name="site_tagline" value="{{ $settings['site_tagline'] ?? '' }}"
                           class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition"/>
                    <p class="text-[11px] text-gray-400 mt-1">Phrase d'accroche principale de la plateforme</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Email de contact & assistance
                    </label>
                    <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? '' }}"
                           class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition"/>
                    <p class="text-[11px] text-gray-400 mt-1">Affiché dans le pied de page et utilisé pour le support</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Téléphone d'assistance
                    </label>
                    <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] ?? '' }}"
                           class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition"/>
                    <p class="text-[11px] text-gray-400 mt-1">Numéro d'appel pour joindre l'équipe Kimboo</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Numéro WhatsApp direct
                    </label>
                    <input type="text" name="contact_whatsapp" value="{{ $settings['contact_whatsapp'] ?? '' }}"
                           class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition"/>
                    <p class="text-[11px] text-gray-400 mt-1">Format international (ex: +225 07 00 00 00 00)</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Adresse / Localisation
                    </label>
                    <input type="text" name="contact_address" value="{{ $settings['contact_address'] ?? '' }}"
                           class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition"/>
                    <p class="text-[11px] text-gray-400 mt-1">Siège social ou zone principale (ex: Abidjan, Côte d'Ivoire)</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Horaires d'ouverture / Disponibilité du service client
                    </label>
                    <input type="text" name="contact_hours" value="{{ $settings['contact_hours'] ?? '' }}"
                           class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition"/>
                    <p class="text-[11px] text-gray-400 mt-1">Ex: Lundi au Samedi de 08h00 à 19h00</p>
                </div>

            </div>
        </div>

        {{-- ==================== ONGLET 2 : PAGE D'ACCUEIL & TEXTES ==================== --}}
        <div id="tab-panel-homepage" class="settings-panel space-y-6 hidden">

            {{-- Bandeau d'annonce --}}
            <div class="p-5 rounded-2xl bg-amber-50/50 border border-amber-200/60">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#FCB315]"></span>
                        <h3 class="text-sm font-bold text-gray-900">Bandeau d'annonce en haut du site</h3>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="banner_active" value="1" {{ ($settings['banner_active'] ?? '0') === '1' ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#FCB315]"></div>
                        <span class="ml-3 text-xs font-semibold text-gray-700">Activer le bandeau</span>
                    </label>
                </div>
                <input type="text" name="banner_text" value="{{ $settings['banner_text'] ?? '' }}"
                       placeholder="Ex: Rentrée scolaire : réservez vos cours dès aujourd'hui !"
                       class="w-full px-4 py-2.5 rounded-xl text-sm bg-white border border-amber-200/80 outline-none focus:border-[#FCB315] transition"/>
                <p class="text-[11px] text-gray-500 mt-1.5">S'affiche en tout début de page pour informer les visiteurs d'une actualité importante.</p>
            </div>

            <div class="space-y-6">

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Titre principal (Hero)
                    </label>
                    <input type="text" name="hero_title" value="{{ $settings['hero_title'] ?? '' }}" required
                           class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition"/>
                    <p class="text-[11px] text-gray-400 mt-1">Titre principal affiché en grand sur la bannière de la page d'accueil</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Sous-titre explicatif (Hero)
                    </label>
                    <textarea name="hero_subtitle" rows="3"
                              class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition">{{ $settings['hero_subtitle'] ?? '' }}</textarea>
                    <p class="text-[11px] text-gray-400 mt-1">Texte de présentation sous le titre principal</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Texte du bouton d'action Hero
                    </label>
                    <input type="text" name="hero_cta_text" value="{{ $settings['hero_cta_text'] ?? 'Trouver un professeur' }}" required
                           class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition"/>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 pt-4 border-t border-gray-100">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Titre section des professeurs
                        </label>
                        <input type="text" name="home_teachers_title" value="{{ $settings['home_teachers_title'] ?? '' }}"
                               class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition"/>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Sous-titre section des professeurs
                        </label>
                        <input type="text" name="home_teachers_subtitle" value="{{ $settings['home_teachers_subtitle'] ?? '' }}"
                               class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition"/>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Titre d'appel « Devenir professeur »
                    </label>
                    <input type="text" name="become_teacher_title" value="{{ $settings['become_teacher_title'] ?? '' }}"
                           class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition"/>
                    <p class="text-[11px] text-gray-400 mt-1">Texte d'invitation pour encourager de nouveaux enseignants à s'inscrire</p>
                </div>

            </div>
        </div>

        {{-- ==================== ONGLET 3 : RÉSEAUX SOCIAUX ==================== --}}
        <div id="tab-panel-social" class="settings-panel space-y-5 hidden">
            <p class="text-xs text-gray-500 mb-4">Ces liens s'affichent avec leurs logos officiels dans le pied de page du site.</p>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <!-- Facebook -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-lg flex items-center justify-center text-white text-xs font-bold" style="background:#1877F2;">f</span>
                        <span>Facebook</span>
                    </label>
                    <input type="url" name="facebook" value="{{ $settings['facebook'] ?? '' }}"
                           placeholder="https://facebook.com/kimboo"
                           class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition"/>
                </div>

                <!-- Instagram -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-lg flex items-center justify-center text-white text-xs font-bold" style="background:linear-gradient(135deg,#f09433,#dc2743,#bc1888);">ig</span>
                        <span>Instagram</span>
                    </label>
                    <input type="url" name="instagram" value="{{ $settings['instagram'] ?? '' }}"
                           placeholder="https://instagram.com/kimboocotedivoire"
                           class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition"/>
                </div>

                <!-- TikTok -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-lg flex items-center justify-center text-white text-xs font-bold bg-black">tt</span>
                        <span>TikTok</span>
                    </label>
                    <input type="url" name="tiktok" value="{{ $settings['tiktok'] ?? '' }}"
                           placeholder="https://tiktok.com/@kimboo.cte.divoir"
                           class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition"/>
                </div>

                <!-- WhatsApp -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-lg flex items-center justify-center text-white text-xs font-bold" style="background:#25D366;">w</span>
                        <span>Lien WhatsApp direct</span>
                    </label>
                    <input type="url" name="whatsapp" value="{{ $settings['whatsapp'] ?? '' }}"
                           placeholder="https://wa.me/2250700000000"
                           class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition"/>
                </div>

                <!-- YouTube -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-lg flex items-center justify-center text-white text-xs font-bold" style="background:#FF0000;">yt</span>
                        <span>Chaîne YouTube</span>
                    </label>
                    <input type="url" name="youtube" value="{{ $settings['youtube'] ?? '' }}"
                           placeholder="https://youtube.com/@kimboo"
                           class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition"/>
                </div>

                <!-- LinkedIn -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-lg flex items-center justify-center text-white text-xs font-bold" style="background:#0A66C2;">in</span>
                        <span>Page LinkedIn</span>
                    </label>
                    <input type="url" name="linkedin" value="{{ $settings['linkedin'] ?? '' }}"
                           placeholder="https://linkedin.com/company/kimboo"
                           class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition"/>
                </div>

                <!-- Twitter / X -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-lg flex items-center justify-center text-white text-xs font-bold bg-black">X</span>
                        <span>X (ex-Twitter)</span>
                    </label>
                    <input type="url" name="twitter" value="{{ $settings['twitter'] ?? '' }}"
                           placeholder="https://x.com/kimboo"
                           class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition"/>
                </div>

            </div>
        </div>

        {{-- ==================== ONGLET 4 : TARIFS & COMMISSION ==================== --}}
        <div id="tab-panel-tarifs" class="settings-panel space-y-6 hidden">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Commission plateforme (%)
                    </label>
                    <div class="relative">
                        <input type="number" step="0.1" min="0" max="100" name="commission_percent" value="{{ $settings['commission_percent'] ?? '0' }}"
                               class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition pr-10"/>
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">%</span>
                    </div>
                    <p class="text-[11px] text-gray-400 mt-1">Pourcentage de frais de service prélevé sur les réservations</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Rayon de recherche par défaut (km)
                    </label>
                    <div class="relative">
                        <input type="number" min="1" max="100" name="default_search_radius" value="{{ $settings['default_search_radius'] ?? '15' }}"
                               class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition pr-10"/>
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">km</span>
                    </div>
                    <p class="text-[11px] text-gray-400 mt-1">Distance de recherche initiale pour le filtre « Autour de moi »</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Tarif horaire minimum recommandé (FCFA)
                    </label>
                    <input type="number" min="0" step="500" name="min_hourly_rate" value="{{ $settings['min_hourly_rate'] ?? '2000' }}"
                           class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition"/>
                    <p class="text-[11px] text-gray-400 mt-1">Limite basse du curseur de prix dans les filtres</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Tarif horaire maximum recommandé (FCFA)
                    </label>
                    <input type="number" min="1000" step="1000" name="max_hourly_rate" value="{{ $settings['max_hourly_rate'] ?? '70000' }}"
                           class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition"/>
                    <p class="text-[11px] text-gray-400 mt-1">Limite haute du curseur de prix dans les filtres</p>
                </div>

            </div>
        </div>

        {{-- ==================== ONGLET 5 : RÉFÉRENCEMENT (SEO) ==================== --}}
        <div id="tab-panel-seo" class="settings-panel space-y-6 hidden">
            <p class="text-xs text-gray-500 mb-4">Optimisez la visibilité de Kimboo sur Google et les moteurs de recherche.</p>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    Titre SEO par défaut (Meta Title)
                </label>
                <input type="text" name="seo_title" value="{{ $settings['seo_title'] ?? '' }}"
                       class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition"/>
                <p class="text-[11px] text-gray-400 mt-1">Apparaît dans l'onglet du navigateur et dans les résultats Google (idéal : 50–60 caractères)</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    Description SEO par défaut (Meta Description)
                </label>
                <textarea name="seo_description" rows="3"
                          class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition">{{ $settings['seo_description'] ?? '' }}</textarea>
                <p class="text-[11px] text-gray-400 mt-1">Extrait affiché sous le lien dans les recherches Google (idéal : 140–160 caractères)</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    Mots-clés SEO (Meta Keywords)
                </label>
                <input type="text" name="seo_keywords" value="{{ $settings['seo_keywords'] ?? '' }}"
                       class="w-full px-4 py-3 rounded-2xl text-sm border border-gray-200 outline-none focus:border-[#FCB315] focus:ring-2 focus:ring-[#FCB315]/20 transition"/>
                <p class="text-[11px] text-gray-400 mt-1">Mots-clés séparés par des virgules</p>
            </div>
        </div>

        {{-- Bouton d'action fixe / bas de page --}}
        <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between">
            <p class="text-xs text-gray-400">Toutes les modifications prennent effet immédiatement sur le site.</p>
            <button type="submit"
                    class="px-6 py-3 rounded-2xl font-bold text-xs sm:text-sm text-black transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 flex items-center gap-2"
                    style="background:#FCB315;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Enregistrer les modifications</span>
            </button>
        </div>

    </form>

</div>

@endsection

@push('scripts')
<script>
function switchSettingsTab(tabName) {
    // Masquer tous les panneaux
    document.querySelectorAll('.settings-panel').forEach(panel => {
        panel.classList.add('hidden');
    });

    // Réinitialiser le style de tous les boutons
    document.querySelectorAll('.settings-tab-btn').forEach(btn => {
        btn.classList.remove('text-black');
        btn.classList.add('text-gray-500', 'hover:text-black', 'bg-gray-50');
        btn.style.background = '#f9fafb';
        btn.style.color = '#6b7280';
    });

    // Afficher le panneau actif
    const activePanel = document.getElementById('tab-panel-' + tabName);
    if (activePanel) {
        activePanel.classList.remove('hidden');
    }

    // Activer le bouton
    const activeBtn = document.getElementById('tab-btn-' + tabName);
    if (activeBtn) {
        activeBtn.style.background = '#FCB315';
        activeBtn.style.color = '#000000';
    }

    // Mettre à jour l'input caché
    const activeInput = document.getElementById('active_tab_input');
    if (activeInput) {
        activeInput.value = tabName;
    }

    // Mettre à jour l'URL sans recharger
    if (history.pushState) {
        const newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?tab=' + tabName;
        window.history.pushState({path:newurl}, '', newurl);
    }
}

// Initialiser l'onglet actif au chargement
document.addEventListener('DOMContentLoaded', function() {
    const initialTab = '{{ $activeTab ?? "general" }}';
    switchSettingsTab(initialTab);
});
</script>
@endpush
