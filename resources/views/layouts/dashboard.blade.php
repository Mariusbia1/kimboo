<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kimboo — @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-gray-50">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="flex flex-col w-64 min-h-screen shrink-0" style="background:#0f0f0f;">

        <!-- Logo -->
        <div class="px-6 py-6 border-b border-white/10">
            <a href="{{ url('/') }}" class="text-2xl font-bold" style="color:#FCB315; font-family:'Poppins',sans-serif;">
                kimboo
            </a>
            <p class="mt-1 text-xs text-gray-500">
                @if(auth()->user()->role === 'admin') Administration
                @elseif(auth()->user()->role === 'professeur') Espace professeur
                @else Espace élève
                @endif
            </p>
        </div>

        <!-- Avatar utilisateur -->
        <div class="px-6 py-5 border-b border-white/10">
            <div class="flex items-center gap-3">
                <x-avatar :user="auth()->user()" size="10" rounded="full"/>
                <div class="overflow-hidden">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-1">

            @if(auth()->user()->role === 'admin')
                <x-sidebar-link href="{{ route('admin.dashboard') }}" icon="grid" label="Tableau de bord"/>
                <x-sidebar-link href="{{ route('admin.users') }}" icon="users" label="Utilisateurs"/>
                <x-sidebar-link href="{{ route('admin.dashboard') }}" icon="check-circle" label="Certifications"/>
                <x-sidebar-link href="#" icon="bar-chart" label="Statistiques"/>

            @elseif(auth()->user()->role === 'professeur')
                <x-sidebar-link href="{{ route('professeur.dashboard') }}" icon="grid" label="Tableau de bord"/>
                <x-sidebar-link href="{{ route('professeur.create-cours') }}" icon="book" label="Mes cours"/>
                <x-sidebar-link href="{{ route('professeur.reservations') }}" icon="calendar" label="Réservations"/>
                @php
                $unreadMessages = \App\Models\Message::where('receiver_id', auth()->id())
                    ->where('is_read', false)
                    ->count();
                @endphp

                <x-sidebar-link href="{{ route('messages.index') }}" icon="message-circle" label="Messages" :badge="$unreadMessages > 0 ? $unreadMessages : null"/>
                <x-sidebar-link href="{{ route('professeur.edit-profil') }}" icon="user" label="Mon profil"/>

            @else
                <x-sidebar-link href="{{ route('eleve.dashboard') }}" icon="grid" label="Tableau de bord"/>
                <x-sidebar-link href="{{ route('cours.index') }}" icon="search" label="Trouver un prof"/>
                <x-sidebar-link href="{{ route('eleve.dashboard') }}" icon="calendar" label="Mes réservations"/>
                @php
                $unreadMessages = \App\Models\Message::where('receiver_id', auth()->id())
                    ->where('is_read', false)
                    ->count();
                @endphp
                                
                <x-sidebar-link href="{{ route('messages.index') }}" icon="message-circle" label="Messages" :badge="$unreadMessages > 0 ? $unreadMessages : null"/>
                <x-sidebar-link href="#" icon="user" label="Mon profil"/>
            @endif
        </nav>

        <!-- Retour site -->
        <div class="px-4 py-6 border-t border-white/10">
            <a href="{{ url('/') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-white/10 transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Retour au site
            </a>
        </div>
    </aside>

    <!-- CONTENU PRINCIPAL -->
    <div class="flex flex-col flex-1 min-h-screen">

        <!-- Topbar -->
        <header class="flex items-center justify-between px-8 py-4 bg-white border-b border-gray-100">
            <div>
                <h1 class="text-lg font-bold text-black" style="font-family:'Poppins',sans-serif;">@yield('page-title')</h1>
                <p class="text-xs text-gray-400">@yield('page-subtitle')</p>
            </div>
            <div class="flex items-center gap-3">

                <!-- Notif -->
                <button class="flex items-center justify-center transition bg-gray-100 w-9 h-9 rounded-xl hover:bg-gray-200">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </button>

                <!-- Dropdown profil -->
                <div class="relative" id="profile-menu">
                    <button onclick="toggleProfileMenu()"
                        class="flex items-center gap-2 px-3 py-2 transition rounded-xl hover:bg-gray-100">
                        <x-avatar :user="auth()->user()" size="8" rounded="full"/>
                        <div class="hidden text-left sm:block">
                            <p class="text-xs font-semibold leading-tight text-black">{{ auth()->user()->name }}</p>
                            <p class="text-xs leading-tight text-gray-400 capitalize">{{ auth()->user()->role }}</p>
                        </div>
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Menu dropdown -->
                    <div id="profile-dropdown"
                        class="absolute right-0 z-50 hidden py-2 mt-2 bg-white border border-gray-100 shadow-lg w-52 rounded-2xl">

                        <!-- Infos -->
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-semibold text-black">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                        </div>

                        <!-- Liens -->
                        <div class="py-2">
                            <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-black transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Mon profil
                            </a>
                            <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-black transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Paramètres
                            </a>
                        </div>

                        <!-- Déconnexion -->
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

        <!-- Page content -->
        <main class="flex-1 p-8">
            @yield('content')
        </main>
    </div>
</div>

<script>
function toggleProfileMenu() {
    document.getElementById('profile-dropdown').classList.toggle('hidden');
}

// Fermer si on clique ailleurs
document.addEventListener('click', function(e) {
    const menu = document.getElementById('profile-menu');
    if (!menu.contains(e.target)) {
        document.getElementById('profile-dropdown').classList.add('hidden');
    }
});
</script>
</body>
</html>
