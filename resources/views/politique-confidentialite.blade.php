@extends('layouts.app')

@section('title', 'Politique de confidentialité')

@section('content')

<section class="py-16 px-4 bg-white">
    <div class="max-w-3xl mx-auto">

        <!-- Titre -->
        <div class="mb-12">
            <h1 class="text-4xl font-black text-black mb-3" style="font-family:'Plus Jakarta Sans',sans-serif;">
                Politique de confidentialité
            </h1>
            <p class="text-sm text-gray-400">Dernière mise à jour : 4 Juin 2026</p>
            <div class="w-12 h-1 rounded-full mt-4" style="background:#FCB315;"></div>
        </div>

        <p class="text-gray-600 leading-relaxed mb-10">
            Chez Kimboo, nous accordons une importance particulière à la protection de vos données personnelles et au respect de votre vie privée. La présente politique a pour objectif de vous informer de manière transparente sur les données que nous collectons, leur utilisation, leur conservation et les droits dont vous disposez.
        </p>

        <!-- Sections -->
        <div class="space-y-10">

            @foreach([
                [
                    'num' => '1',
                    'titre' => 'Qui sommes-nous ?',
                    'contenu' => 'Kimboo est une plateforme éducative qui met en relation des élèves, étudiants, parents et enseignants afin de faciliter l\'apprentissage et le partage des connaissances.<br><br>Responsable du traitement : <strong>Kimboo</strong><br>Email : <strong>bonjour@kimboo.net</strong>',
                ],
                [
                    'num' => '2',
                    'titre' => 'Les données que nous collectons',
                    'contenu' => '<strong>Informations d\'identification :</strong> nom, prénom, adresse e-mail, numéro de téléphone.<br><br><strong>Informations de profil :</strong> photo, niveau d\'études, matières enseignées ou recherchées, diplômes et certifications.<br><br><strong>Informations de connexion :</strong> adresse IP, navigateur, appareil, pages consultées, date et heure.<br><br><strong>Communications :</strong> messages envoyés via la plateforme, demandes au support.',
                ],
                [
                    'num' => '3',
                    'titre' => 'Pourquoi collectons-nous ces données ?',
                    'contenu' => 'Les données collectées nous permettent de : créer et gérer votre compte, mettre en relation élèves et enseignants, vérifier certains profils, assurer le bon fonctionnement du service, améliorer l\'expérience utilisateur, répondre aux demandes d\'assistance, prévenir les fraudes et respecter nos obligations légales.',
                ],
                [
                    'num' => '4',
                    'titre' => 'Base légale du traitement',
                    'contenu' => 'Nous traitons vos données sur la base de votre consentement, de l\'exécution des services proposés, de nos obligations légales et de notre intérêt légitime à améliorer et sécuriser la plateforme.',
                ],
                [
                    'num' => '5',
                    'titre' => 'Partage des données',
                    'contenu' => 'Kimboo <strong>ne vend jamais</strong> les données personnelles de ses utilisateurs. Certaines informations peuvent être partagées avec nos prestataires techniques, nos fournisseurs d\'hébergement et les autorités compétentes lorsque la loi l\'exige. Tous nos partenaires sont tenus de respecter la confidentialité des données.',
                ],
                [
                    'num' => '6',
                    'titre' => 'Durée de conservation',
                    'contenu' => 'Vos données sont conservées uniquement pendant la durée nécessaire à la réalisation des finalités décrites dans cette politique. Lorsque votre compte est supprimé, certaines données peuvent être conservées temporairement afin de respecter nos obligations légales.',
                ],
                [
                    'num' => '7',
                    'titre' => 'Sécurité des données',
                    'contenu' => 'Kimboo met en œuvre des mesures techniques et organisationnelles destinées à protéger vos données contre l\'accès non autorisé, la perte, la destruction et l\'altération. Malgré nos efforts, aucune transmission sur Internet ne peut être garantie totalement sécurisée.',
                ],
                [
                    'num' => '8',
                    'titre' => 'Cookies',
                    'contenu' => 'Kimboo peut utiliser des cookies afin de mémoriser vos préférences, améliorer la navigation, mesurer l\'audience et analyser les performances. Vous pouvez configurer votre navigateur pour refuser les cookies à tout moment.',
                ],
                [
                    'num' => '9',
                    'titre' => 'Vos droits',
                    'contenu' => 'Vous disposez des droits suivants : droit d\'accès, de rectification, de suppression, d\'opposition, de limitation du traitement et de portabilité de vos données.<br><br>Pour exercer ces droits : <strong>bonjour@kimboo.net</strong>',
                ],
                [
                    'num' => '10',
                    'titre' => 'Protection des mineurs',
                    'contenu' => 'Kimboo étant une plateforme éducative, certains utilisateurs peuvent être mineurs. Nous encourageons les parents ou représentants légaux à accompagner les mineurs dans l\'utilisation de la plateforme.',
                ],
                [
                    'num' => '11',
                    'titre' => 'Modifications de la politique',
                    'contenu' => 'La présente politique peut être mise à jour à tout moment afin de refléter les évolutions légales, réglementaires ou techniques. La date de la dernière mise à jour figurera toujours en haut de cette page.',
                ],
                [
                    'num' => '12',
                    'titre' => 'Nous contacter',
                    'contenu' => 'Pour toute question concernant cette politique :<br><strong>Kimboo</strong><br>Email : <strong>bonjour@kimboo.net</strong>',
                ],
            ] as $section)
            <div class="border-b border-gray-100 pb-10">
                <div class="flex items-start gap-4">
                    <span class="text-sm font-black shrink-0 w-8 h-8 rounded-lg flex items-center justify-center text-black" style="background:#FFF8E7; font-family:'Plus Jakarta Sans',sans-serif;">
                        {{ $section['num'] }}
                    </span>
                    <div class="flex-1">
                        <h2 class="text-lg font-bold text-black mb-3" style="font-family:'Plus Jakarta Sans',sans-serif;">
                            {{ $section['titre'] }}
                        </h2>
                        <p class="text-gray-600 leading-relaxed text-sm">{!! $section['contenu'] !!}</p>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</section>

@endsection
