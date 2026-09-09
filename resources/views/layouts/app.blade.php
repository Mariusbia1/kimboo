<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $siteName = \App\Models\SiteSetting::get('site_name', 'Kimboo');
        $seoTitle = \App\Models\SiteSetting::get('seo_title', 'Kimboo — Trouvez votre professeur en Côte d\'Ivoire');
        $seoDesc  = \App\Models\SiteSetting::get('seo_description', 'Kimboo cours particuliers et soutien scolaire en Côte d’Ivoire. Trouvez le professeur qu’il vous faut.');
        $seoKeywords = \App\Models\SiteSetting::get('seo_keywords', 'cours particuliers, professeur, abidjan, soutien scolaire, kimboo');
        $bannerActive = \App\Models\SiteSetting::get('banner_active', '0');
        $bannerText   = \App\Models\SiteSetting::get('banner_text', '');
    @endphp
    <title>{{ $siteName }} — @yield('title', 'Trouvez votre professeur')</title>
    <meta name="description" content="{{ $seoDesc }}">
    <meta name="keywords" content="{{ $seoKeywords }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    @if(request()->is('/'))
    <link rel="preload" as="image" href="{{ asset('images/hero-home.webp') }}" type="image/webp" fetchpriority="high">
    @endif
<!-- Open Graph / Partage réseaux sociaux -->
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="{{ url()->current() }}"/>
    <meta property="og:title" content="{{ $seoTitle }}"/>
    <meta property="og:description" content="{{ $seoDesc }}"/>
    <meta property="og:image" content="{{ asset('images/kimboo-preview.png') }}"/>
    <meta property="og:image:type" content="image/png"/>
    <meta property="og:image:width" content="1200"/>
    <meta property="og:image:height" content="630"/>
    <meta property="og:locale" content="fr_FR"/>
    <meta property="og:site_name" content="{{ $siteName }}"/>

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:title" content="{{ $seoTitle }}"/>
    <meta name="twitter:description" content="{{ $seoDesc }}"/>
    <meta name="twitter:image" content="{{ asset('images/kimboo-preview.png') }}"/>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=3">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=3">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}?v=3">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=3">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=3">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="font-family:'Plus Jakarta Sans', sans-serif;" class="bg-white text-black">

    @if($bannerActive === '1' && !empty($bannerText))
    <!-- BANNIÈRE D'ANNONCE DYNAMIQUE -->
    <aside aria-label="Annonce" class="w-full bg-[#0B0F19] border-b border-amber-400/20 text-white text-xs sm:text-sm py-2 px-4 shadow-sm relative z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-center gap-2.5 text-center font-medium">
            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-[#FCB315] text-[#0B0F19] text-xs font-black shrink-0 shadow-sm">
                !
            </span>
            <span class="text-amber-100/90">{{ $bannerText }}</span>
        </div>
    </aside>
    @endif

    <!-- NAVBAR -->
<nav class="w-full bg-white sticky top-0 z-50" style="box-shadow: 0 1px 12px rgba(0,0,0,0.08);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <!-- Logo -->
            <x-application-logo />

            <!-- Auth buttons -->
            <div class="hidden md:flex items-center gap-3">
                @auth
                    <span class="text-sm text-gray-500">{{ Auth::user()->name }}</span>
                    <a href="{{ route('dashboard') }}"
                       class="text-sm font-medium px-5 py-2 rounded-full text-gray-700 hover:text-black hover:bg-gray-100 transition">
                        Tableau de bord
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="text-sm font-medium px-5 py-2 rounded-full text-gray-500 hover:text-black hover:bg-gray-100 transition">
                            Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('register') }}?role=professeur"
                       class="text-sm font-medium text-gray-600 hover:text-black transition px-2">
                        Donner des cours
                    </a>

                    <a href="{{ route('login') }}"
                       class="text-sm font-semibold text-black px-6 py-2.5 rounded-full transition hover:opacity-90 shadow-sm"
                       style="background:#FCB315;">
                        Connexion / Inscription
                    </a>
                @endauth
            </div>

            <!-- Menu burger mobile -->
            <button class="md:hidden text-gray-600 p-2 rounded-full hover:bg-gray-100 transition" id="menu-toggle">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Menu mobile -->
        <div class="md:hidden hidden pb-4 border-t border-gray-100 pt-3" id="mobile-menu">
            @auth
                <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-sm text-gray-600 rounded-full hover:bg-gray-50 transition">Tableau de bord</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2.5 text-sm text-gray-600 rounded-full hover:bg-gray-50 transition">Déconnexion</button>
                </form>
            @else
                <a href="{{ route('register') }}?role=professeur" class="block px-4 py-2.5 text-sm text-gray-600 rounded-full hover:bg-gray-50 transition">Donner des cours</a>
                <div class="px-4 pt-2">
                    <a href="{{ route('login') }}"
                       class="block text-center text-sm font-semibold text-black px-6 py-2.5 rounded-full transition hover:opacity-90 shadow-sm"
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
                <x-application-logo dark="true" />
                <p class="text-white text-sm mt-2 leading-relaxed">
                    La plateforme ivoirienne de mise en relation<br>entre professeurs et élèves.
                </p>

                {{-- Réseaux sociaux --}}
@php
    $facebook  = \App\Models\SiteSetting::get('facebook');
    $instagram = \App\Models\SiteSetting::get('instagram');
    $tiktok    = \App\Models\SiteSetting::get('tiktok');
    $whatsapp  = \App\Models\SiteSetting::get('whatsapp');
    $youtube   = \App\Models\SiteSetting::get('youtube');
    $linkedin  = \App\Models\SiteSetting::get('linkedin');
    $contactEmail = \App\Models\SiteSetting::get('contact_email', 'contact@kimboo.ci');
    $contactPhone = \App\Models\SiteSetting::get('contact_phone', '+225 07 00 00 00 00');
    $contactAddress = \App\Models\SiteSetting::get('contact_address', 'Abidjan, Côte d\'Ivoire');
@endphp

@if($facebook || $instagram || $tiktok || $whatsapp || $youtube || $linkedin)
<div class="flex items-center gap-2.5 mt-5 flex-wrap">
    @if($facebook)
    <a href="{{ $facebook }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-xl flex items-center justify-center transition hover:opacity-80" style="background:#1877F2;" title="Facebook">
        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
        </svg>
    </a>
    @endif
    @if($instagram)
    <a href="{{ $instagram }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-xl flex items-center justify-center transition hover:opacity-80" style="background:linear-gradient(135deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);" title="Instagram">
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke-width="2"/>
            <circle cx="12" cy="12" r="4" stroke-width="2"/>
            <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>
        </svg>
    </a>
    @endif
    @if($tiktok)
    <a href="{{ $tiktok }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-xl flex items-center justify-center transition hover:opacity-80" style="background:#010101; border:1px solid #333;" title="TikTok">
        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.32 6.32 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.76a4.85 4.85 0 01-1.01-.07z"/>
        </svg>
    </a>
    @endif
    @if($whatsapp)
    <a href="{{ $whatsapp }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-xl flex items-center justify-center transition hover:opacity-80" style="background:#25D366;" title="WhatsApp">
        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>
    @endif
    @if($youtube)
    <a href="{{ $youtube }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-xl flex items-center justify-center transition hover:opacity-80" style="background:#FF0000;" title="YouTube">
        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
        </svg>
    </a>
    @endif
    @if($linkedin)
    <a href="{{ $linkedin }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-xl flex items-center justify-center transition hover:opacity-80" style="background:#0A66C2;" title="LinkedIn">
        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
        </svg>
    </a>
    @endif
</div>
@endif
            </div>

            <!-- Liens -->
            <div class="flex flex-wrap gap-10 sm:gap-14 text-sm">
                <div>
                    <p class="text-white font-semibold mb-4">Liens</p>
                    <a href="{{ url('/') }}" class="block text-gray-300 hover:text-yellow-400 transition mb-2">Accueil</a>
                    <a href="{{ route('cours.index') }}" class="block text-gray-300 hover:text-yellow-400 transition mb-2">Cours</a>
                    <a href="{{ route('qui-sommes-nous') }}" class="block text-gray-300 hover:text-yellow-400 transition">Qui sommes-nous ?</a>
                </div>
                <div>
                    <p class="text-white font-semibold mb-4">Légal</p>
                    <div>
                        <a href="{{ route('politique-confidentialite') }}" class="block text-gray-300 hover:text-yellow-400 transition mb-2">Confidentialité</a>
                        <a href="#" class="block text-gray-300 hover:text-yellow-400 transition mb-2">CGU</a>
                    </div>
                </div>
                <div>
                    <p class="text-white font-semibold mb-4">Contact</p>
                    <p class="text-gray-300 text-xs mb-2">{{ $contactAddress }}</p>
                    <a href="mailto:{{ $contactEmail }}" class="block text-gray-300 hover:text-yellow-400 transition text-xs mb-2">{{ $contactEmail }}</a>
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactPhone) }}" class="block text-gray-300 hover:text-yellow-400 transition text-xs">{{ $contactPhone }}</a>
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

@auth
<script>
(function() {
    function sendHeartbeat() {
        if (document.visibilityState === 'visible') {
            fetch('{{ route("user.heartbeat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).catch(() => {});
        }
    }
    setInterval(sendHeartbeat, 60000);
})();
</script>
@endauth

</body>
</html>
