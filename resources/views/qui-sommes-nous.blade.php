@extends('layouts.app')

@section('title', 'Qui sommes-nous ?')

@section('content')

<!-- HERO -->
<section class="py-20 px-4 text-center bg-white">
    <div class="max-w-3xl mx-auto">
        <h1 class="font-black text-black mb-8" style="font-family:'Plus Jakarta Sans',sans-serif; font-size:clamp(2.5rem, 6vw, 4.5rem); line-height:1.1;">
            Qui sommes-nous ?
        </h1>

        <!-- Citation Mandela -->
        <div class="my-10">
            <p class="text-xl md:text-2xl font-medium text-gray-700 italic leading-relaxed mb-3" style="font-family:'Plus Jakarta Sans',sans-serif;">
                « L'éducation est l'arme la plus puissante<br class="hidden md:block"> pour changer le monde. »
            </p>
            <p class="text-sm font-semibold text-gray-400 tracking-widest uppercase">— Nelson Mandela</p>
        </div>

        <div class="w-16 h-1 rounded-full mx-auto" style="background:#FCB315;"></div>
    </div>
</section>

<!-- INTRO -->
<section class="py-12 px-4 bg-white">
    <div class="max-w-3xl mx-auto">
        <p class="text-lg text-gray-600 leading-relaxed mb-4">
            Chez <strong class="text-black">Kimboo</strong>, nous croyons qu'un grand avenir commence toujours par l'apprentissage.
        </p>
        <p class="text-lg text-gray-600 leading-relaxed mb-4">
            La Côte d'Ivoire, comme une grande partie de l'Afrique, connaît aujourd'hui une transformation profonde : une jeunesse ambitieuse, un monde du travail en évolution, de nouveaux besoins, de nouvelles opportunités.
        </p>
        <p class="text-lg text-gray-600 leading-relaxed">
            Dans cette dynamique, l'éducation joue un rôle essentiel. Kimboo est né d'une conviction simple : <strong class="text-black">chaque élève mérite un accompagnement de qualité</strong>, et chaque savoir transmis peut changer une trajectoire de vie.
        </p>
    </div>
</section>

<!-- NOS SECTIONS -->
<section class="py-16 px-4 bg-white">
    <div class="max-w-3xl mx-auto space-y-16">

        <!-- Notre mission -->
        <div>
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:#FFF8E7;">
                    <svg class="w-5 h-5" style="color:#FCB315;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-black" style="font-family:'Plus Jakarta Sans',sans-serif;">
                    Notre mission
                </h2>
            </div>
            <div class="pl-13 space-y-4" style="padding-left:3.25rem;">
                <p class="text-gray-600 leading-relaxed">
                    Notre mission est de rendre l'apprentissage plus <strong class="text-black">accessible, fiable et humain</strong>.
                </p>
                <p class="text-gray-600 leading-relaxed">
                    Nous voulons créer un espace où élèves, étudiants et enseignants peuvent se rencontrer dans un cadre sérieux, moderne et rassurant.
                </p>
                <p class="text-gray-600 leading-relaxed">
                    Parce qu'apprendre ne devrait pas être une source de stress, mais de confiance. Parce qu'un bon professeur peut changer une vie. Et parce que miser sur l'éducation, c'est investir dans un développement durable, solide et ambitieux pour notre société.
                </p>
            </div>
        </div>

        <div class="w-full h-px bg-gray-100"></div>

        <!-- La qualité avant tout -->
        <div>
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:#FFF8E7;">
                    <svg class="w-5 h-5" style="color:#FCB315;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-black" style="font-family:'Plus Jakarta Sans',sans-serif;">
                    La qualité avant tout
                </h2>
            </div>
            <div style="padding-left:3.25rem;">
                <p class="text-gray-600 leading-relaxed mb-5">
                    Chez Kimboo, nous privilégions l'excellence et la confiance. Nous mettons en avant :
                </p>
                <div class="space-y-3">
                    @foreach([
                        'Des profils vérifiés',
                        'Des diplômes validés',
                        'Les avis des élèves',
                        'Des enseignants disponibles et engagés',
                        'Un accompagnement sérieux et transparent',
                    ] as $item)
                    <div class="flex items-center gap-3">
                        <div class="w-5 h-5 rounded-full flex items-center justify-center shrink-0" style="background:#FCB315;">
                            <svg class="w-3 h-3 text-black" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414L8.414 15 3.293 9.879a1 1 0 011.414-1.414L8.414 12.172l6.879-6.879a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-gray-700">{{ $item }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="w-full h-px bg-gray-100"></div>

        <!-- Une ambition, un projet -->
        <div>
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:#FFF8E7;">
                    <svg class="w-5 h-5" style="color:#FCB315;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-black" style="font-family:'Plus Jakarta Sans',sans-serif;">
                    Une ambition, un projet
                </h2>
            </div>
            <div style="padding-left:3.25rem;">
                <p class="text-gray-600 leading-relaxed mb-4">
                    Kimboo n'est pas seulement un service de mise en relation. C'est un projet porté par une vision : <strong class="text-black">valoriser le savoir, encourager la transmission</strong> et participer, à notre échelle, à la construction d'une Côte d'Ivoire plus forte par l'éducation.
                </p>
            </div>
        </div>

    </div>
</section>

<!-- CONCLUSION -->
<section class="py-20 px-4 text-center bg-gray-50 border-y border-gray-100">
    <div class="max-w-2xl mx-auto">
        <p class="text-2xl md:text-3xl font-bold text-gray-900 mb-2" style="font-family:'Plus Jakarta Sans',sans-serif;">Chaque cours compte.</p>
        <p class="text-2xl md:text-3xl font-bold mb-2" style="color:#FCB315; font-family:'Plus Jakarta Sans',sans-serif;">Chaque élève compte.</p>
        <p class="text-2xl md:text-3xl font-bold text-gray-900 mb-10" style="font-family:'Plus Jakarta Sans',sans-serif;">Chaque avenir compte.</p>

        <a href="{{ route('cours.index') }}"
           class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl text-black font-bold text-base transition hover:-translate-y-0.5 hover:shadow-lg"
           style="background:#FCB315; box-shadow:0 10px 24px rgba(252,179,21,0.22);">
            Trouver un professeur
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>
</section>

@endsection
