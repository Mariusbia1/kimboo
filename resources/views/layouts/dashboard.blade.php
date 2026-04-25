<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-1">

            @if(auth()->user()->role === 'admin')
                @php
                $pendingCours = \App\Models\Course::where('status', 'pending')->count();
                $unreadNotifs = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                @endphp
                <x-sidebar-link href="{{ route('admin.dashboard') }}" icon="grid" label="Tableau de bord"/>
                <x-sidebar-link href="{{ route('admin.users') }}" icon="users" label="Utilisateurs"/>
                <x-sidebar-link href="{{ route('admin.cours') }}" icon="book" label="Cours" :badge="$pendingCours ?: null"/>
                <x-sidebar-link href="{{ route('admin.stats') }}" icon="bar-chart" label="Statistiques"/>
                @php
                $pendingAlertes = \App\Models\MessageAlert::where('status', 'pending')->count();
                @endphp
                <x-sidebar-link href="{{ route('admin.messages.alertes') }}" icon="message-circle" label="Alertes messages" :badge="$pendingAlertes ?: null"/>

            @elseif(auth()->user()->role === 'professeur')
                @php
                $unreadMessages = \App\Models\Message::where('receiver_id', auth()->id())->where('is_read', false)->count();
                $unreadNotifs   = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                @endphp
                <x-sidebar-link href="{{ route('professeur.dashboard') }}" icon="grid" label="Tableau de bord"/>
                <x-sidebar-link href="{{ route('professeur.create-cours') }}" icon="book" label="Mes cours"/>
                <x-sidebar-link href="{{ route('professeur.reservations') }}" icon="calendar" label="Réservations"/>
                <x-sidebar-link href="{{ route('messages.index') }}" icon="message-circle" label="Messages" :badge="$unreadMessages > 0 ? $unreadMessages : null"/>
                <x-sidebar-link href="{{ route('professeur.edit-profil') }}" icon="user" label="Mon profil"/>

            @else
                @php
                $unreadMessages = \App\Models\Message::where('receiver_id', auth()->id())->where('is_read', false)->count();
                $unreadNotifs   = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                @endphp
                <x-sidebar-link href="{{ route('eleve.dashboard') }}" icon="grid" label="Tableau de bord"/>
                <x-sidebar-link href="{{ route('cours.index') }}" icon="search" label="Trouver un prof"/>
                <x-sidebar-link href="{{ route('eleve.dashboard') }}" icon="calendar" label="Mes réservations"/>
                <x-sidebar-link href="{{ route('favoris.index') }}" icon="heart" label="Mes favoris"/>
                <x-sidebar-link href="{{ route('messages.index') }}" icon="message-circle" label="Messages" :badge="$unreadMessages > 0 ? $unreadMessages : null"/>
                <x-sidebar-link href="{{ route('eleve.edit-profil') }}" icon="user" label="Mon profil"/>
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

                <!-- Cloche notifications -->
                <div class="relative" id="notif-menu">
                    <button onclick="toggleNotifMenu()"
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
                         class="absolute right-0 z-50 hidden mt-2 bg-white border border-gray-100 shadow-xl rounded-2xl"
                         style="width:340px;">

                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-semibold text-black">Notifications</p>
                            @if(isset($unreadNotifs) && $unreadNotifs > 0)
                            <form method="POST" action="{{ route('notifications.read-all') }}">
                                @csrf
                                <button type="submit" class="text-xs font-medium hover:underline" style="color:#FCB315;">
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
                                    <p class="text-xs font-semibold text-black">{{ $notif->title }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ Str::limit($notif->body, 80) }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
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

                    <div id="profile-dropdown"
                        class="absolute right-0 z-50 hidden py-2 mt-2 bg-white border border-gray-100 shadow-lg w-52 rounded-2xl">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-semibold text-black">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="py-2">
                            @php
$profilRoute = match(auth()->user()->role) {
    'professeur' => route('professeur.edit-profil'),
    'eleve'      => route('eleve.edit-profil'),
    default      => '#',
};
@endphp
<a href="{{ $profilRoute }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-black transition">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

        <main class="flex-1 p-8">
            @yield('content')
        </main>
    </div>
</div>

<script>
function toggleProfileMenu() {
    document.getElementById('profile-dropdown').classList.toggle('hidden');
    document.getElementById('notif-dropdown').classList.add('hidden');
}

function toggleNotifMenu() {
    document.getElementById('notif-dropdown').classList.toggle('hidden');
    document.getElementById('profile-dropdown').classList.add('hidden');
}

document.addEventListener('click', function(e) {
    const profileMenu = document.getElementById('profile-menu');
    const notifMenu   = document.getElementById('notif-menu');
    if (!profileMenu.contains(e.target)) {
        document.getElementById('profile-dropdown').classList.add('hidden');
    }
    if (!notifMenu.contains(e.target)) {
        document.getElementById('notif-dropdown').classList.add('hidden');
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
</script>
@stack('scripts')
</body>
</html>
