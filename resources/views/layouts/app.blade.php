<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kimboo — @yield('title', 'Trouvez votre professeur')</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="font-family:'Plus Jakarta Sans', sans-serif;" class="bg-white text-black">

    <!-- NAVBAR -->
<nav class="w-full bg-white sticky top-0 z-50" style="box-shadow: 0 1px 12px rgba(0,0,0,0.08);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <!-- Logo -->
            <a href="{{ url('/') }}" class="text-3xl font-bold shrink-0" style="color:#FCB315; font-family:'Poppins',sans-serif; letter-spacing:-1px;">
                kimboo
            </a>

            <!-- Menu desktop -->
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ url('/') }}"
                   class="text-sm font-medium px-4 py-2 rounded-xl transition
                   {{ request()->is('/') ? 'text-black bg-gray-100' : 'text-gray-500 hover:text-black hover:bg-gray-50' }}">
                    Accueil
                </a>
                <a href="{{ route('cours.index') }}"
                   class="text-sm font-medium px-4 py-2 rounded-xl transition
                   {{ request()->routeIs('cours.index') ? 'text-black bg-gray-100' : 'text-gray-500 hover:text-black hover:bg-gray-50' }}">
                    Cours
                </a>
                <a href="#comment-ca-marche"
                   class="text-sm font-medium px-4 py-2 rounded-xl transition text-gray-500 hover:text-black hover:bg-gray-50">
                    Comment ça marche
                </a>
            </div>

            <!-- Auth buttons -->
            <div class="hidden md:flex items-center gap-3">
                @auth
                    <span class="text-sm text-gray-500">{{ Auth::user()->name }}</span>
                    <a href="{{ route('dashboard') }}"
                       class="text-sm font-medium px-4 py-2 rounded-xl text-gray-700 hover:text-black hover:bg-gray-50 transition">
                        Tableau de bord
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="text-sm font-medium px-4 py-2 rounded-xl text-gray-500 hover:text-black hover:bg-gray-50 transition">
                            Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('register') }}?role=professeur"
                       class="text-sm font-medium text-gray-600 hover:text-black transition px-2">
                        Donner des cours
                    </a>

                    <a href="{{ route('login') }}"
                       class="text-sm font-semibold text-black px-5 py-2.5 rounded-xl transition hover:opacity-90"
                       style="background:#FCB315;">
                        Connexion / Inscription
                    </a>
                @endauth
            </div>

            <!-- Menu burger mobile -->
            <button class="md:hidden text-gray-600 p-2 rounded-xl hover:bg-gray-100 transition" id="menu-toggle">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Menu mobile -->
        <div class="md:hidden hidden pb-4 border-t border-gray-100 pt-3" id="mobile-menu">
            <a href="{{ url('/') }}" class="block px-4 py-2.5 text-sm text-gray-600 rounded-xl hover:bg-gray-50 transition">Accueil</a>
            <a href="{{ route('cours.index') }}" class="block px-4 py-2.5 text-sm text-gray-600 rounded-xl hover:bg-gray-50 transition">Cours</a>
            <a href="#comment-ca-marche" class="block px-4 py-2.5 text-sm text-gray-600 rounded-xl hover:bg-gray-50 transition">Comment ça marche</a>
            @auth
                <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-sm text-gray-600 rounded-xl hover:bg-gray-50 transition">Tableau de bord</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2.5 text-sm text-gray-600 rounded-xl hover:bg-gray-50 transition">Déconnexion</button>
                </form>
            @else
                <a href="{{ route('register') }}?role=professeur" class="block px-4 py-2.5 text-sm text-gray-600 rounded-xl hover:bg-gray-50 transition">Donner des cours</a>
                <div class="px-4 pt-2">
                    <a href="{{ route('login') }}"
                       class="block text-center text-sm font-semibold text-black px-5 py-2.5 rounded-xl transition hover:opacity-90"
                       style="background:#FCB315;">
                        Connexion / Inscription
                    </a>
                </div>
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
            <div class="border-t border-gray-800 mt-8 pt-6 text-center text-[rgb(43,43,43)] text-xs">
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

async function toggleFavori(profileId, btn) {
    try {
        const response = await fetch(`/favoris/${profileId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            }
        });
        const data = await response.json();
        const icon = btn.querySelector('.favori-icon');
        if (data.liked) {
            icon.setAttribute('fill', '#e53e3e');
            icon.setAttribute('stroke', '#e53e3e');
        } else {
            icon.setAttribute('fill', 'none');
            icon.setAttribute('stroke', '#666');
        }
    } catch(e) {
        console.error(e);
    }
}

// Animation compteur
function animateCounters() {
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;

        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                counter.textContent = target;
                clearInterval(timer);
            } else {
                counter.textContent = Math.floor(current);
            }
        }, 16);
    });
}

// Lancer quand la section est visible
const statsSection = document.querySelector('.counter');
if (statsSection) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                observer.disconnect();
            }
        });
    }, { threshold: 0.5 });
    observer.observe(statsSection);
}

let currentSlide = 0;
const totalSlides = 6;
const cardWidth = 340 + 24; // width + gap

function updateSlider() {
    const track = document.getElementById('temoignages-track');
    if (!track) return;
    track.style.transform = `translateX(-${currentSlide * cardWidth}px)`;

    // Mise à jour dots
    document.querySelectorAll('.dot').forEach((dot, i) => {
        if (i === currentSlide) {
            dot.style.background = '#FCB315';
            dot.style.width = '24px';
        } else {
            dot.style.background = '#E5E5E5';
            dot.style.width = '8px';
        }
    });
}

function slideNext() {
    currentSlide = (currentSlide + 1) % totalSlides;
    updateSlider();
}

function slidePrev() {
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
    updateSlider();
}

function goToSlide(index) {
    currentSlide = index;
    updateSlider();
}

// Auto-play
setInterval(slideNext, 4000);
</script>

</body>
</html>
