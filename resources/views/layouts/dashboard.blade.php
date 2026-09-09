<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kimboo — @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=3">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=3">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}?v=3">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=3">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=3">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-gray-50 h-screen overflow-hidden">

<div class="flex h-screen w-full overflow-hidden">

    <!-- SIDEBAR FIXE -->
    <aside class="hidden lg:flex flex-col w-64 h-screen shrink-0 border-r border-white/[0.08] overflow-y-auto z-30" style="background:#0B0F19;">

        <!-- Logo -->
        <div class="px-6 py-6 border-b border-white/[0.08]">
            <x-application-logo size="lg" />
            <div class="mt-2.5 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full" style="background:#FCB315;"></span>
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">
                    @if(auth()->user()->role === 'admin') Administration
                    @elseif(auth()->user()->role === 'professeur') Espace professeur
                    @else Espace élève
                    @endif
                </p>
            </div>
        </div>

        <!-- Avatar utilisateur -->
        <div class="p-3.5 m-3 rounded-2xl bg-white/[0.04] border border-white/[0.06]">
            <div class="flex items-center gap-3">
                <x-avatar :user="auth()->user()" size="10" rounded="xl"/>
                <div class="overflow-hidden min-w-0 flex-1">
                    <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-1">

            @if(auth()->user()->role === 'admin')
                @php
                $pendingCours   = \App\Models\Course::where('status', 'pending')->count();
                $pendingAlertes = \App\Models\MessageAlert::where('status', 'pending')->count();
                $unreadNotifs   = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                @endphp
                <x-sidebar-link href="{{ route('admin.dashboard') }}" icon="grid" label="Tableau de bord"/>
                <x-sidebar-link href="{{ route('admin.users') }}" icon="users" label="Utilisateurs"/>
                <x-sidebar-link href="{{ route('admin.cours') }}" icon="book" label="Cours" :badge="$pendingCours ?: null"/>
                <x-sidebar-link href="{{ route('admin.stats') }}" icon="bar-chart" label="Statistiques"/>
                <x-sidebar-link href="{{ route('admin.messages.conversations') }}" icon="message-square" label="Conversations" :badge="$pendingAlertes ?: null" :active="request()->routeIs('admin.messages.*')"/>
                <x-sidebar-link href="{{ route('admin.parametres') }}" icon="settings" label="Paramètres" :active="request()->routeIs('admin.parametres')" />
                <x-sidebar-link href="{{ route('admin.profil') }}" icon="user" label="Mon profil" :active="request()->routeIs('admin.profil')" />

            @elseif(auth()->user()->role === 'professeur')
                @php
                $unreadMessages = \App\Models\Message::where('receiver_id', auth()->id())->where('is_read', false)->count();
                $unreadNotifs   = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                @endphp
                <x-sidebar-link href="{{ route('professeur.dashboard') }}" icon="grid" label="Tableau de bord"/>
                <x-sidebar-link href="{{ route('professeur.create-cours') }}" icon="book" label="Mes cours"/>
                <x-sidebar-link href="{{ route('professeur.reservations') }}" icon="calendar" label="Réservations"/>
                <x-sidebar-link href="{{ route('professeur.calendrier') }}" icon="calendar" label="Calendrier"/>
                <x-sidebar-link href="{{ route('messages.index') }}" icon="message-circle" label="Messages" :badge="$unreadMessages > 0 ? $unreadMessages : null"/>
                <x-sidebar-link href="{{ route('messages.assistance') }}" icon="support" label="Assistance Kimboo"/>
                <x-sidebar-link href="{{ route('professeur.edit-profil') }}" icon="user" label="Mon profil"/>

            @else
                @php
                $unreadMessages = \App\Models\Message::where('receiver_id', auth()->id())->where('is_read', false)->count();
                $unreadNotifs   = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                @endphp
                <x-sidebar-link href="{{ route('eleve.dashboard') }}" icon="grid" label="Tableau de bord"/>
                <x-sidebar-link href="{{ route('cours.index') }}" icon="search" label="Trouver un prof"/>
                <x-sidebar-link href="{{ route('eleve.mes-cours') }}" icon="book" label="Mes cours"/>
                <x-sidebar-link href="{{ route('eleve.reservations') }}" icon="calendar" label="Mes réservations"/>
                <x-sidebar-link href="{{ route('eleve.calendrier') }}" icon="calendar" label="Calendrier"/>
                <x-sidebar-link href="{{ route('favoris.index') }}" icon="heart" label="Mes favoris"/>
                <x-sidebar-link href="{{ route('messages.index') }}" icon="message-circle" label="Messages" :badge="$unreadMessages > 0 ? $unreadMessages : null"/>
                <x-sidebar-link href="{{ route('messages.assistance') }}" icon="support" label="Assistance Kimboo"/>
                <x-sidebar-link href="{{ route('eleve.edit-profil') }}" icon="user" label="Mon profil"/>
            @endif
        </nav>

        <!-- Footer Sidebar (Retour site & Déconnexion) -->
        <div class="px-4 py-5 border-t border-white/10 space-y-1 mt-auto">
            <a href="{{ url('/') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-white/10 transition text-sm font-medium">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Retour au site</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 w-full px-3.5 py-2.5 rounded-xl text-red-400 hover:text-white hover:bg-red-500/20 transition text-sm font-medium text-left">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
                    </svg>
                    <span>Déconnexion</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- CONTENU PRINCIPAL (SEUL LE CONTENU SCROLLE) -->
    <div class="flex flex-col flex-1 min-w-0 h-screen overflow-y-auto overflow-x-hidden">

        <!-- Topbar -->
        <header class="sticky top-0 z-20 flex items-center justify-between gap-4 px-4 py-3.5 bg-white/95 backdrop-blur-md border-b border-gray-100 sm:px-6 lg:px-8 shrink-0">
            <div class="min-w-0">
                <h1 class="text-lg font-bold text-gray-900 tracking-tight" style="font-family:'Poppins',sans-serif;">@yield('page-title')</h1>
                <p class="text-xs text-gray-400">@yield('page-subtitle')</p>
            </div>
            <div class="flex items-center gap-3 shrink-0">

                <!-- Cloche notifications -->
                <div class="relative" id="notif-menu">
                    <button type="button" onclick="toggleNotifMenu(event)"
                        class="relative flex items-center justify-center transition bg-gray-100 w-9 h-9 rounded-xl hover:bg-gray-200">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if(isset($unreadNotifs) && $unreadNotifs > 0)
                        <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full flex items-center justify-center font-bold"
                              style="background:#FCB315; color:#000; font-size:9px;">
                            {{ $unreadNotifs > 9 ? '9+' : $unreadNotifs }}
                        </span>
                        @endif
                    </button>

                    <!-- Dropdown notifications -->
                    <div id="notif-dropdown"
                         class="absolute right-0 z-50 hidden mt-2 bg-white border border-gray-100 shadow-xl rounded-2xl w-[calc(100vw-2rem)] max-w-[340px]">

                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-semibold text-black">Notifications</p>
                            @if(isset($unreadNotifs) && $unreadNotifs > 0)
                            <form method="POST" action="{{ route('notifications.read-all') }}">
                                @csrf
                                <button type="submit" class="text-sm font-medium hover:underline" style="color:#FCB315;">
                                    Tout marquer comme lu
                                </button>
                            </form>
                            @endif
                        </div>

                        <div class="max-h-80 overflow-y-auto">
                            @php
                            $notifications = \App\Models\Notification::where('user_id', auth()->id())
                                ->orderByDesc('created_at')
                                ->limit(10)
                                ->get();
                            @endphp

                            @forelse($notifications as $notif)
                            <a href="{{ $notif->link ?? '#' }}"
                               onclick="markRead({{ $notif->id }}, this)"
                               class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition border-b border-gray-50 {{ $notif->is_read ? '' : 'bg-yellow-50' }}">

                                {{-- Couleur fond icône --}}
                                @php
                                $iconBg = match($notif->type) {
                                    'course_approved'   => 'bg-green-100',
                                    'course_rejected'   => 'bg-red-100',
                                    'message_alert'     => 'bg-red-100',
                                    'course_completed'  => 'bg-indigo-100',
                                    'new_review'        => 'bg-amber-100',
                                    default             => 'bg-amber-100',
                                };
                                @endphp

                                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 mt-0.5 {{ $iconBg }}">
                                    @if($notif->type === 'course_approved')
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    @elseif($notif->type === 'course_rejected')
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    @elseif($notif->type === 'message_alert')
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    @elseif($notif->type === 'course_completed')
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    @elseif($notif->type === 'new_review')
                                    <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                    </svg>
                                    @elseif($notif->type === 'course_pending')
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    @else
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-black">{{ $notif->title }}</p>
                                    <p class="text-sm text-gray-500 mt-0.5 leading-relaxed">{{ Str::limit($notif->body, 80) }}</p>
                                    <p class="text-sm text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                </div>

                                @if(!$notif->is_read)
                                <div class="w-2 h-2 rounded-full shrink-0 mt-2" style="background:#FCB315;"></div>
                                @endif
                            </a>
                            @empty
                            <div class="px-4 py-8 text-center">
                                <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <p class="text-sm text-gray-400">Aucune notification</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Dropdown profil -->
                <div class="relative" id="profile-menu">
                    <button type="button" onclick="toggleProfileMenu(event)"
                        class="flex items-center gap-2 px-3 py-2 transition rounded-xl hover:bg-gray-100">
                        <x-avatar :user="auth()->user()" size="8" rounded="full"/>
                        <div class="hidden text-left sm:block">
                            <p class="text-sm font-semibold leading-tight text-black">{{ auth()->user()->name }}</p>
                            <p class="text-sm leading-tight text-gray-400 capitalize">{{ auth()->user()->role }}</p>
                        </div>
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div id="profile-dropdown"
                        class="absolute right-0 z-50 hidden py-2 mt-2 bg-white border border-gray-100 shadow-lg w-52 rounded-2xl">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-semibold text-black">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-gray-400">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="py-2">
                            @php
                            $profilRoute = match(auth()->user()->role) {
                                'admin'      => route('admin.profil'),
                                'professeur' => route('professeur.edit-profil'),
                                'eleve'      => route('eleve.edit-profil'),
                                default      => url('/'),
                            };
                            @endphp
                            <a href="{{ $profilRoute }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-900 transition font-medium">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Mon profil
                            </a>
                        </div>
                        <div class="pt-2 border-t border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
                                    </svg>
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        <nav class="lg:hidden bg-white border-b border-gray-100 max-w-full">
            <div class="flex flex-wrap gap-2 px-4 py-3">
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-xl text-sm font-medium whitespace-nowrap {{ request()->routeIs('admin.dashboard') ? 'text-black' : 'text-gray-500' }}" style="{{ request()->routeIs('admin.dashboard') ? 'background:#FCB315;' : 'background:#F3F4F6;' }}">Tableau de bord</a>
                    <a href="{{ route('admin.users') }}" class="px-3 py-2 rounded-xl text-sm font-medium whitespace-nowrap {{ request()->routeIs('admin.users') ? 'text-black' : 'text-gray-500' }}" style="{{ request()->routeIs('admin.users') ? 'background:#FCB315;' : 'background:#F3F4F6;' }}">Utilisateurs</a>
                    <a href="{{ route('admin.cours') }}" class="px-3 py-2 rounded-xl text-sm font-medium whitespace-nowrap {{ request()->routeIs('admin.cours*') ? 'text-black' : 'text-gray-500' }}" style="{{ request()->routeIs('admin.cours*') ? 'background:#FCB315;' : 'background:#F3F4F6;' }}">Cours</a>
                    <a href="{{ route('admin.stats') }}" class="px-3 py-2 rounded-xl text-sm font-medium whitespace-nowrap {{ request()->routeIs('admin.stats') ? 'text-black' : 'text-gray-500' }}" style="{{ request()->routeIs('admin.stats') ? 'background:#FCB315;' : 'background:#F3F4F6;' }}">Stats</a>
                    <a href="{{ route('admin.parametres') }}" class="px-3 py-2 rounded-xl text-sm font-medium whitespace-nowrap {{ request()->routeIs('admin.parametres') ? 'text-black' : 'text-gray-500' }}" style="{{ request()->routeIs('admin.parametres') ? 'background:#FCB315;' : 'background:#F3F4F6;' }}">Paramètres</a>
                @elseif(auth()->user()->role === 'professeur')
                    <a href="{{ route('professeur.dashboard') }}" class="px-3 py-2 rounded-xl text-sm font-medium whitespace-nowrap {{ request()->routeIs('professeur.dashboard') ? 'text-black' : 'text-gray-500' }}" style="{{ request()->routeIs('professeur.dashboard') ? 'background:#FCB315;' : 'background:#F3F4F6;' }}">Tableau de bord</a>
                    <a href="{{ route('professeur.create-cours') }}" class="px-3 py-2 rounded-xl text-sm font-medium whitespace-nowrap {{ request()->routeIs('professeur.create-cours') ? 'text-black' : 'text-gray-500' }}" style="{{ request()->routeIs('professeur.create-cours') ? 'background:#FCB315;' : 'background:#F3F4F6;' }}">Mes cours</a>
                    <a href="{{ route('professeur.reservations') }}" class="px-3 py-2 rounded-xl text-sm font-medium whitespace-nowrap {{ request()->routeIs('professeur.reservations') ? 'text-black' : 'text-gray-500' }}" style="{{ request()->routeIs('professeur.reservations') ? 'background:#FCB315;' : 'background:#F3F4F6;' }}">Réservations</a>
                    <a href="{{ route('professeur.calendrier') }}" class="px-3 py-2 rounded-xl text-sm font-medium whitespace-nowrap {{ request()->routeIs('professeur.calendrier') ? 'text-black' : 'text-gray-500' }}" style="{{ request()->routeIs('professeur.calendrier') ? 'background:#FCB315;' : 'background:#F3F4F6;' }}">Calendrier</a>
                    <a href="{{ route('messages.index') }}" class="px-3 py-2 rounded-xl text-sm font-medium whitespace-nowrap {{ request()->routeIs('messages.*') ? 'text-black' : 'text-gray-500' }}" style="{{ request()->routeIs('messages.*') ? 'background:#FCB315;' : 'background:#F3F4F6;' }}">Messages</a>
                    <a href="{{ route('professeur.edit-profil') }}" class="px-3 py-2 rounded-xl text-sm font-medium whitespace-nowrap {{ request()->routeIs('professeur.edit-profil') ? 'text-black' : 'text-gray-500' }}" style="{{ request()->routeIs('professeur.edit-profil') ? 'background:#FCB315;' : 'background:#F3F4F6;' }}">Profil</a>
                @else
                    <a href="{{ route('eleve.dashboard') }}" class="px-3 py-2 rounded-xl text-sm font-medium whitespace-nowrap {{ request()->routeIs('eleve.dashboard') ? 'text-black' : 'text-gray-500' }}" style="{{ request()->routeIs('eleve.dashboard') ? 'background:#FCB315;' : 'background:#F3F4F6;' }}">Tableau de bord</a>
                    <a href="{{ route('cours.index') }}" class="px-3 py-2 rounded-xl text-sm font-medium whitespace-nowrap {{ request()->routeIs('cours.index') ? 'text-black' : 'text-gray-500' }}" style="{{ request()->routeIs('cours.index') ? 'background:#FCB315;' : 'background:#F3F4F6;' }}">Trouver un prof</a>
                    <a href="{{ route('eleve.mes-cours') }}" class="px-3 py-2 rounded-xl text-sm font-medium whitespace-nowrap {{ request()->routeIs('eleve.mes-cours') ? 'text-black' : 'text-gray-500' }}" style="{{ request()->routeIs('eleve.mes-cours') ? 'background:#FCB315;' : 'background:#F3F4F6;' }}">Mes cours</a>
                    <a href="{{ route('eleve.reservations') }}" class="px-3 py-2 rounded-xl text-sm font-medium whitespace-nowrap {{ request()->routeIs('eleve.reservations') ? 'text-black' : 'text-gray-500' }}" style="{{ request()->routeIs('eleve.reservations') ? 'background:#FCB315;' : 'background:#F3F4F6;' }}">Réservations</a>
                    <a href="{{ route('eleve.calendrier') }}" class="px-3 py-2 rounded-xl text-sm font-medium whitespace-nowrap {{ request()->routeIs('eleve.calendrier') ? 'text-black' : 'text-gray-500' }}" style="{{ request()->routeIs('eleve.calendrier') ? 'background:#FCB315;' : 'background:#F3F4F6;' }}">Calendrier</a>
                    <a href="{{ route('favoris.index') }}" class="px-3 py-2 rounded-xl text-sm font-medium whitespace-nowrap {{ request()->routeIs('favoris.index') ? 'text-black' : 'text-gray-500' }}" style="{{ request()->routeIs('favoris.index') ? 'background:#FCB315;' : 'background:#F3F4F6;' }}">Favoris</a>
                    <a href="{{ route('messages.index') }}" class="px-3 py-2 rounded-xl text-sm font-medium whitespace-nowrap {{ request()->routeIs('messages.*') ? 'text-black' : 'text-gray-500' }}" style="{{ request()->routeIs('messages.*') ? 'background:#FCB315;' : 'background:#F3F4F6;' }}">Messages</a>
                    <a href="{{ route('eleve.edit-profil') }}" class="px-3 py-2 rounded-xl text-sm font-medium whitespace-nowrap {{ request()->routeIs('eleve.edit-profil') ? 'text-black' : 'text-gray-500' }}" style="{{ request()->routeIs('eleve.edit-profil') ? 'background:#FCB315;' : 'background:#F3F4F6;' }}">Profil</a>
                @endif
                <a href="{{ url('/') }}"
                   class="px-3 py-2 rounded-xl text-sm font-semibold whitespace-nowrap text-white"
                   style="background:#1A2B3C;">
                    Retour au site
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-2 rounded-xl text-sm font-semibold whitespace-nowrap text-red-600 bg-red-50 hover:bg-red-100 transition">
                        Déconnexion
                    </button>
                </form>
            </div>
        </nav>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 min-w-0 break-words">
            @if(auth()->check() && auth()->user()->is_suspended)
            <div class="mb-6 p-4 sm:p-5 rounded-2xl bg-red-50 border border-red-200 text-red-950 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
                <div class="flex items-start gap-3.5">
                    <div class="p-2.5 rounded-xl bg-red-100 text-red-600 shrink-0 mt-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-red-900">Compte actuellement suspendu</h4>
                        <p class="text-xs text-red-800 mt-1 leading-relaxed">
                            Certaines fonctionnalités (réservations de cours, publication ou messagerie avec d'autres membres) sont temporairement désactivées.
                            @if(auth()->user()->suspension_reason)
                            <span class="block mt-1 font-semibold text-red-950">Motif : {{ auth()->user()->suspension_reason }}</span>
                            @endif
                        </p>
                    </div>
                </div>
                @php
                    $adminAssistance = \App\Models\User::where('role', 'admin')->first();
                @endphp
                @if($adminAssistance)
                <a href="{{ route('messages.show', $adminAssistance->id) }}"
                   class="shrink-0 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-red-600 hover:bg-red-700 transition shadow-sm inline-flex items-center gap-2 self-start sm:self-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span>Contacter l'Assistance</span>
                </a>
                @endif
            </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script>
function toggleProfileMenu(event) {
    if (event) event.stopPropagation();
    document.getElementById('profile-dropdown').classList.toggle('hidden');
    document.getElementById('notif-dropdown').classList.add('hidden');
}

function toggleNotifMenu(event) {
    if (event) event.stopPropagation();
    document.getElementById('notif-dropdown').classList.toggle('hidden');
    document.getElementById('profile-dropdown').classList.add('hidden');
}

document.addEventListener('click', function(e) {
    const profileMenu = document.getElementById('profile-menu');
    const notifMenu   = document.getElementById('notif-menu');
    if (profileMenu && !profileMenu.contains(e.target)) {
        document.getElementById('profile-dropdown')?.classList.add('hidden');
    }
    if (notifMenu && !notifMenu.contains(e.target)) {
        document.getElementById('notif-dropdown')?.classList.add('hidden');
    }
});

function markRead(id, el) {
    fetch('/notifications/' + id + '/read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Content-Type': 'application/json'
        }
    });
}

(function() {
    function sendHeartbeat() {
        if (document.visibilityState === 'visible') {
            fetch('{{ route("user.heartbeat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            }).catch(() => {});
        }
    }
    setInterval(sendHeartbeat, 60000);
})();
</script>
@stack('scripts')
</body>
</html>
