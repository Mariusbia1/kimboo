<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kimboo — @yield('title', 'Trouvez votre professeur')</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="font-family:'Plus Jakarta Sans', sans-serif;" class="bg-white text-black">

    <!-- NAVBAR -->
<nav class="w-full bg-white sticky top-0 z-50" style="box-shadow: 0 4px 20px rgba(116, 80, 3, 0.42);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <!-- Logo -->
            <a href="{{ url('/') }}" class="text-3xl font-bold shrink-0" style="color:#FCB315; font-family:'Poppins',sans-serif; letter-spacing:-1px;">
                kimboo
            </a>

            <!-- Menu desktop -->
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ url('/') }}" class="text-sm font-medium text-gray-600 hover:text-black transition">Accueil</a>
                <a href="{{ route('cours.index') }}" class="text-sm font-medium text-gray-600 hover:text-black transition">Cours</a>
                <a href="#comment-ca-marche" class="text-sm font-medium text-gray-600 hover:text-black transition">Comment ça marche</a>
            </div>

            <!-- Auth buttons -->
            <div class="hidden md:flex items-center gap-3">
                @auth
                    <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
                    <a href="{{ route('dashboard') }}"
                       class="text-sm font-medium text-gray-700 hover:text-black transition">
                        Tableau de bord
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-black transition">
                            Déconnexion
                        </button>
                    </form>
                @else
                    <!-- Donner des cours -->
                    <a href="{{ route('register') }}?role=professeur"
                       class="text-sm font-medium text-gray-700 hover:text-black transition">
                        Donner des cours
                    </a>

                    <!-- S'inscrire -->
                    <a href="{{ route('register') }}"
                       class="text-sm font-medium text-white px-4 py-2 rounded-xl transition hover:opacity-90"
                       style="background:#FCB315;">
                        S'inscrire
                    </a>
                @endauth
            </div>

            <!-- Menu burger mobile -->
            <button class="md:hidden text-gray-600" id="menu-toggle">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Menu mobile -->
        <div class="md:hidden hidden pb-4" id="mobile-menu">
            <a href="{{ url('/') }}" class="block py-2 text-sm text-gray-600">Accueil</a>
            <a href="{{ route('cours.index') }}" class="block py-2 text-sm text-gray-600">Cours</a>
            <a href="#comment-ca-marche" class="block py-2 text-sm text-gray-600">Comment ça marche</a>
            @auth
                <a href="{{ route('dashboard') }}" class="block py-2 text-sm text-gray-600">Tableau de bord</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block py-2 text-sm text-gray-600">Déconnexion</button>
                </form>
            @else
                <a href="{{ route('register') }}?role=professeur" class="block py-2 text-sm text-gray-600">Donner des cours</a>
                <a href="{{ route('register') }}" class="block py-2 text-sm font-medium" style="color:#FCB315;">S'inscrire</a>
                <a href="{{ route('login') }}" class="block py-2 text-sm text-gray-600">Se connecter</a>
            @endauth
        </div>
    </div>
</nav>
    <!-- CONTENU PRINCIPAL -->
    <main>
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-black text-white mt-20">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="flex flex-col md:flex-row justify-between items-start gap-8">
                <div>
                    <p class="text-3xl font-bold" style="color:#FCB315; font-family:'Poppins',sans-serif;">kimboo</p>
                    <p class="text-gray-400 text-sm mt-2">La plateforme ivoirienne de mise en relation<br>entre professeurs et élèves.</p>
                </div>
                <div class="flex gap-12 text-sm text-gray-400">
                    <div>
                        <p class="text-white font-medium mb-3">Liens</p>
                        <a href="#" class="block hover:text-white transition mb-1">Accueil</a>
                        <a href="#" class="block hover:text-white transition mb-1">Cours</a>
                        <a href="#" class="block hover:text-white transition">Comment ça marche</a>
                    </div>
                    <div>
                        <p class="text-white font-medium mb-3">Légal</p>
                        <a href="#" class="block hover:text-white transition mb-1">Confidentialité</a>
                        <a href="#" class="block hover:text-white transition">CGU</a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-6 text-center text-gray-500 text-xs">
                © {{ date('Y') }} Kimboo. Tous droits réservés.
            </div>
        </div>
    </footer>

    <!-- Script menu burger -->
   <script>
    document.getElementById('menu-toggle').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });

    // Texte défilant dans la barre de recherche
    const words = ['Maths...', 'Cuisine...', 'Lingala...', 'Anglais...', 'Sport...', 'Informatique...'];
    let wordIndex = 0;
    let charIndex = 0;
    let isDeleting = false;
    const input = document.getElementById('search-input');

    function typeEffect() {
        if (!input) return;
        if (document.activeElement === input) {
            setTimeout(typeEffect, 300);
            return;
        }

        const currentWord = words[wordIndex];

        if (isDeleting) {
            input.placeholder = currentWord.substring(0, charIndex - 1);
            charIndex--;
        } else {
            input.placeholder = currentWord.substring(0, charIndex + 1);
            charIndex++;
        }

        if (!isDeleting && charIndex === currentWord.length) {
            setTimeout(() => { isDeleting = true; typeEffect(); }, 1500);
            return;
        }

        if (isDeleting && charIndex === 0) {
            isDeleting = false;
            wordIndex = (wordIndex + 1) % words.length;
        }

        setTimeout(typeEffect, isDeleting ? 60 : 100);
    }

    typeEffect();
</script>

</body>
</html>
