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

            <!-- Logo + description + réseaux -->
            <div>
                <p class="text-3xl font-bold" style="color:#FCB315; font-family:'Poppins',sans-serif;">kimboo</p>
                <p class="text-white text-sm mt-2 leading-relaxed">
                    La plateforme ivoirienne de mise en relation<br>entre professeurs et élèves.
                </p>

                {{-- Réseaux sociaux --}}
@php
    $facebook  = \App\Models\SiteSetting::get('facebook');
    $instagram = \App\Models\SiteSetting::get('instagram');
    $tiktok    = \App\Models\SiteSetting::get('tiktok');
    $whatsapp  = \App\Models\SiteSetting::get('whatsapp');
@endphp

@if($facebook || $instagram || $tiktok || $whatsapp)
<div class="flex items-center gap-3 mt-5">
    @if($facebook)
    <a href="{{ $facebook }}" target="_blank" class="w-9 h-9 rounded-xl flex items-center justify-center transition hover:opacity-80" style="background:#1877F2;">
        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
        </svg>
    </a>
    @endif
    @if($instagram)
    <a href="{{ $instagram }}" target="_blank" class="w-9 h-9 rounded-xl flex items-center justify-center transition hover:opacity-80" style="background:linear-gradient(135deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);">
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke-width="2"/>
            <circle cx="12" cy="12" r="4" stroke-width="2"/>
            <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>
        </svg>
    </a>
    @endif
    @if($tiktok)
    <a href="{{ $tiktok }}" target="_blank" class="w-9 h-9 rounded-xl flex items-center justify-center transition hover:opacity-80" style="background:#010101;">
        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.32 6.32 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.76a4.85 4.85 0 01-1.01-.07z"/>
        </svg>
    </a>
    @endif
    @if($whatsapp)
    <a href="{{ $whatsapp }}" target="_blank" class="w-9 h-9 rounded-xl flex items-center justify-center transition hover:opacity-80" style="background:#25D366;">
        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>
    @endif
</div>
@endif
            </div>

            <!-- Liens -->
            <div class="flex gap-12 text-sm">
                <div>
                    <p class="text-white font-semibold mb-4">Liens</p>
                    <a href="{{ url('/') }}" class="block text-white hover:text-yellow-400 transition mb-2">Accueil</a>
                    <a href="{{ route('cours.index') }}" class="block text-white hover:text-yellow-400 transition mb-2">Cours</a>
                    <a href="#comment-ca-marche" class="block text-white hover:text-yellow-400 transition">Comment ça marche</a>
                </div>
                <div>
                    <p class="text-white font-semibold mb-4">Légal</p>
                    <a href="#" class="block text-white hover:text-yellow-400 transition mb-2">Confidentialité</a>
                    <a href="#" class="block text-white hover:text-yellow-400 transition">CGU</a>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-8 pt-6 text-center text-white text-sm">
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
