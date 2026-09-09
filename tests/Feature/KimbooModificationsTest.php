<?php

use App\Models\User;
use App\Models\TeacherProfile;
use App\Models\Course;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

test('page accueil contient la meta description seo et 9 profils', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('Kimboo cours particuliers et soutien scolaire', false);
});

test('recherche avec filtres personnalises fonctionne', function () {
    $profOnline = User::factory()->create(['name' => 'Prof En Ligne', 'role' => 'professeur', 'ville' => 'Abidjan']);
    $profileOnline = TeacherProfile::create([
        'user_id' => $profOnline->id,
        'hourly_rate' => 5000,
        'bio' => 'Expert en programmation et maths',
        'lieu_cours' => ['webcam'],
    ]);
    Course::create([
        'teacher_profile_id' => $profileOnline->id,
        'title' => 'Python et Algorithmique',
        'category' => 'Informatique',
        'level' => 'Université',
        'format' => 'En ligne',
        'price_per_hour' => 5000,
        'status' => 'approved',
        'is_active' => true,
    ]);

    $profPres = User::factory()->create(['name' => 'Prof Presentiel', 'role' => 'professeur', 'ville' => 'Bouaké']);
    $profilePres = TeacherProfile::create([
        'user_id' => $profPres->id,
        'hourly_rate' => 35000,
        'bio' => 'Cours à domicile uniquement',
        'lieu_cours' => ['chez_eleve'],
        'zone_deplacement' => 'Bouaké et environs',
    ]);
    Course::create([
        'teacher_profile_id' => $profilePres->id,
        'title' => 'Piano Classique',
        'category' => 'Musique',
        'level' => 'Tous niveaux',
        'format' => 'Présentiel',
        'price_per_hour' => 35000,
        'status' => 'approved',
        'is_active' => true,
    ]);

    // Test 1: Filtre type_cours = en_ligne
    $resOnline = $this->get('/cours?type_cours=en_ligne');
    $resOnline->assertOk();
    $resOnline->assertSee('Prof En Ligne');
    $resOnline->assertDontSee('Prof Presentiel');

    // Test 2: Filtre tarif max = 10000 FCFA
    $resTarif = $this->get('/cours?tarif=10000');
    $resTarif->assertOk();
    $resTarif->assertSee('Prof En Ligne');
    $resTarif->assertDontSee('Prof Presentiel');

    // Test 3: Filtre recherche q = "Python"
    $resQuery = $this->get('/cours?q=Python');
    $resQuery->assertOk();
    $resQuery->assertSee('Python et Algorithmique');
    $resQuery->assertDontSee('Piano Classique');

    // Test 4: Filtre par catégorie
    $resCat = $this->get('/cours?categorie=Musique');
    $resCat->assertOk();
    $resCat->assertSee('Piano Classique');
    $resCat->assertDontSee('Python et Algorithmique');

    // Test 5: Affichage des filtres actifs
    $resActive = $this->get('/cours?q=Python&type_cours=en_ligne&tarif=10000');
    $resActive->assertOk();
    $resActive->assertSee('Filtres actifs :');
    $resActive->assertSee('Tout effacer');
});

test('professeur peut modifier son cours et creer une nouvelle matiere', function () {
    $profUser = User::factory()->create(['role' => 'professeur']);
    $profile = TeacherProfile::create([
        'user_id' => $profUser->id,
        'hourly_rate' => 15000,
        'bio' => 'Test bio',
    ]);

    $course = Course::create([
        'teacher_profile_id' => $profile->id,
        'title' => 'Ancien titre cours',
        'category' => 'Mathématiques',
        'level' => 'Lycée',
        'format' => 'En ligne',
        'price_per_hour' => 12000,
        'status' => 'approved',
        'is_active' => true,
    ]);

    $response = $this->actingAs($profUser)->put(route('professeur.update-cours', $course->id), [
        'title' => 'Nouveau titre modifie',
        'category' => '__new__',
        'new_category' => 'Biologie Marine',
        'level' => 'Université',
        'format' => 'Présentiel',
        'price_per_hour' => 18000,
    ]);

    $response->assertRedirect(route('professeur.dashboard'));

    $course->refresh();
    expect($course->title)->toBe('Nouveau titre modifie')
        ->and($course->category)->toBe('Biologie Marine')
        ->and($course->price_per_hour)->toBe(18000);

    expect(Category::where('name', 'Biologie Marine')->exists())->toBeTrue();
});

test('admin peut modifier son profil assistance kimboo', function () {
    $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post(route('admin.profil.update'), [
        'name' => 'Assistance Kimboo',
        'email' => $admin->email,
    ]);

    $response->assertSessionHas('success');
    expect($admin->fresh()->name)->toBe('Assistance Kimboo');
});

test('envoi de message declenche email et notification au destinataire', function () {
    Mail::fake();

    $sender = User::factory()->create(['role' => 'eleve']);
    $receiver = User::factory()->create(['role' => 'professeur']);

    $response = $this->actingAs($sender)->post(route('messages.send', $receiver->id), [
        'content' => 'Bonjour professeur êtes-vous disponible pour un cours',
    ]);

    $response->assertRedirect(route('messages.show', $receiver->id));

    Mail::assertSent(\App\Mail\NouveauMessageRecu::class, function ($mail) use ($receiver) {
        return $mail->hasTo($receiver->email);
    });
});

test('reservation declenche un email au prof et un email recapitulatif a l eleve', function () {
    Mail::fake();

    $eleve = User::factory()->create(['role' => 'eleve']);
    $profUser = User::factory()->create(['role' => 'professeur']);
    $profile = TeacherProfile::create([
        'user_id' => $profUser->id,
        'hourly_rate' => 10000,
        'bio' => 'Professeur certifié',
    ]);

    $course = Course::create([
        'teacher_profile_id' => $profile->id,
        'title' => 'Cours d Anglais Intensif',
        'category' => 'Anglais',
        'level' => 'Tous niveaux',
        'format' => 'En ligne',
        'price_per_hour' => 10000,
        'status' => 'approved',
        'is_active' => true,
    ]);

    $response = $this->actingAs($eleve)->post(route('booking.store', $profile->id), [
        'course_id' => $course->id,
        'scheduled_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
        'duration_hours' => 2,
    ]);

    $response->assertRedirect(route('eleve.reservations'));

    // Vérifier envoi mail nouvelle réservation au prof
    Mail::assertSent(\App\Mail\NouvelleReservation::class, function ($mail) use ($profUser) {
        return $mail->hasTo($profUser->email);
    });

    // Vérifier envoi mail récapitulatif à l'élève
    Mail::assertSent(\App\Mail\DemandeReservationEleve::class, function ($mail) use ($eleve) {
        return $mail->hasTo($eleve->email);
    });
});

test('partage reseaux sociaux affiche le logo officiel kimboo et non une photo de professeur', function () {
    $response = $this->get('/');
    $response->assertOk();

    // Doit avoir og:image pointant vers l'image officielle kimboo-preview.png
    $response->assertSee('<meta property="og:image" content="' . asset('images/kimboo-preview.png') . '"/>', false);
    $response->assertSee('<meta name="twitter:image" content="' . asset('images/kimboo-preview.png') . '"/>', false);

    // Ne doit pas contenir d'image professeur dans les balises og:image
    $content = $response->getContent();
    preg_match('/<meta property="og:image" content="([^"]+)"/', $content, $matches);
    expect($matches[1] ?? '')->toContain('kimboo-preview.png');
    expect($matches[1] ?? '')->not->toContain('avatars');
});

test('fichiers logos officiels existent dans public/images', function () {
    expect(file_exists(public_path('images/logo.png')))->toBeTrue();
    expect(file_exists(public_path('images/logo.webp')))->toBeTrue();
    expect(file_exists(public_path('images/logo-white.png')))->toBeTrue();
    expect(file_exists(public_path('images/logo-white.webp')))->toBeTrue();
    expect(file_exists(public_path('images/kimboo-preview.png')))->toBeTrue();
});

test('statistiques admin affichent les noms exacts des pages et les vrais profils professeurs', function () {
    $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

    \App\Models\PageView::create([
        'url' => '/',
        'ip' => '127.0.0.1',
        'viewed_at' => now(),
    ]);

    $response = $this->actingAs($admin)->get(route('admin.stats'));
    $response->assertOk();

    // Vérifier que "Accueil" est affiché et jamais "//"
    $response->assertSee('Accueil');
    $response->assertDontSee('>//<', false);

    // Vérifier la présence des sections Pages et Profils
    $response->assertSee('Pages les plus visitées');
    $response->assertSee('Profils professeurs les plus visités');
});

test('tableau de bord admin et page utilisateurs se chargent sans erreur avec le nouveau design', function () {
    $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

    $resDashboard = $this->actingAs($admin)->get(route('admin.dashboard'));
    $resDashboard->assertOk();
    $resDashboard->assertSee('Professeurs inscrits');
    $resDashboard->assertSee('Raccourcis', false);

    $resUsers = $this->actingAs($admin)->get(route('admin.users'));
    $resUsers->assertOk();
    $resUsers->assertSee('Tous les comptes utilisateurs');
});

test('tableau de bord eleve se charge sans erreur avec le nouveau design', function () {
    $eleve = User::where('role', 'eleve')->first() ?? User::factory()->create(['role' => 'eleve']);

    $response = $this->actingAs($eleve)->get(route('eleve.dashboard'));
    $response->assertOk();
    $response->assertSee('Espace Apprenant');
    $response->assertSee('Trouver un cours ou une matière');
});

test('tableau de bord professeur se charge sans erreur avec le nouveau design', function () {
    $profUser = User::where('role', 'professeur')->first() ?? User::factory()->create(['role' => 'professeur']);
    if (!$profUser->teacherProfile) {
        \App\Models\TeacherProfile::create([
            'user_id' => $profUser->id,
            'bio' => 'Enseignant passionné',
            'hourly_rate' => 5000,
        ]);
    }

    $response = $this->actingAs($profUser)->get(route('professeur.dashboard'));
    $response->assertOk();
    $response->assertSee('Espace Enseignant');
    $response->assertSee('Aperçu des performances');
    $response->assertSee('Calendrier des séances');
});

test('admin peut consulter les 5 onglets de parametres du site et modifier les reglages', function () {
    $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('admin.parametres'));
    $response->assertOk();
    $response->assertSee('Général & Coordonnées', false);
    $response->assertSee('Accueil & Textes', false);
    $response->assertSee('Réseaux Sociaux', false);
    $response->assertSee('Tarifs & Commission', false);
    $response->assertSee('Référencement (SEO)', false);

    $postData = [
        'tab' => 'homepage',
        'site_name' => 'Kimboo Elite',
        'hero_title' => 'L excellence scolaire à Abidjan',
        'banner_active' => '1',
        'banner_text' => 'Inscriptions ouvertes pour la session 2026 !',
        'contact_email' => 'direction@kimboo.ci',
        'commission_percent' => '12',
    ];

    $updateResponse = $this->actingAs($admin)->post(route('admin.parametres.update'), $postData);
    $updateResponse->assertRedirect(route('admin.parametres', ['tab' => 'homepage']));
    $updateResponse->assertSessionHas('success');

    expect(\App\Models\SiteSetting::get('site_name'))->toBe('Kimboo Elite');
    expect(\App\Models\SiteSetting::get('hero_title'))->toBe('L excellence scolaire à Abidjan');
    expect(\App\Models\SiteSetting::get('banner_active'))->toBe('1');
    expect(\App\Models\SiteSetting::get('banner_text'))->toBe('Inscriptions ouvertes pour la session 2026 !');
    expect(\App\Models\SiteSetting::get('contact_email'))->toBe('direction@kimboo.ci');
});

test('banniere annonce et reglages dynamiques s affichent sur le site', function () {
    \App\Models\SiteSetting::set('banner_active', '1');
    \App\Models\SiteSetting::set('banner_text', 'Bannière Flash Promotion Spéciale');
    \App\Models\SiteSetting::set('hero_title', 'Titre Hero Personnalisé Kimboo');

    $response = $this->get('/');
    $response->assertOk();
    $response->assertSee('Bannière Flash Promotion Spéciale');
    $response->assertSee('Titre Hero Personnalisé Kimboo');
});

test('composant avatar genere de superbes initiales doubles sans photo', function () {
    $user = User::factory()->create([
        'name' => 'Amadou Diallo',
        'avatar' => null,
    ]);

    $view = $this->blade('<x-avatar :user="$user" size="12" />', ['user' => $user]);

    // Doit contenir les initiales AD et les styles modernes de dégradé épuré (sans cercles SVG)
    $view->assertSee('AD');
    $view->assertSee('radial-gradient', false);
    $view->assertDontSee('<circle', false);
});

test('menu profil dashboard contient le lien fonctionnel selon le role et pas de doublure d avatar', function () {
    $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);
    $resAdmin = $this->actingAs($admin)->get(route('admin.dashboard'));
    $resAdmin->assertOk();
    $resAdmin->assertSee(route('admin.profil'));
    $resAdmin->assertDontSee('href="#"', false);

    $prof = User::where('role', 'professeur')->first() ?? User::factory()->create(['role' => 'professeur']);
    $resProf = $this->actingAs($prof)->get(route('professeur.dashboard'));
    $resProf->assertOk();
    $resProf->assertSee(route('professeur.edit-profil'));

    $eleve = User::where('role', 'eleve')->first() ?? User::factory()->create(['role' => 'eleve']);
    $resEleve = $this->actingAs($eleve)->get(route('eleve.dashboard'));
    $resEleve->assertOk();
    $resEleve->assertSee(route('eleve.edit-profil'));
});

test('photos de profil sont centrees en haut pour toujours afficher le visage et la tete', function () {
    $user = User::factory()->create([
        'name' => 'Kouassi Yao',
        'avatar' => 'avatars/photo_test.jpg',
    ]);

    $view = $this->blade('<x-avatar :user="$user" size="12" />', ['user' => $user]);

    $view->assertSee('object-top', false);
    $view->assertSee('object-position: center top', false);
});

test('page profil professeur contient les cartes de cours interactives et le popup de details complets', function () {
    $profUser = User::factory()->create(['role' => 'professeur']);
    $profile = TeacherProfile::create([
        'user_id' => $profUser->id,
        'hourly_rate' => 15000,
        'bio' => 'Professeur certifié',
        'a_propos_cours' => 'Pédagogie active et méthodologie progressive',
    ]);

    $course = Course::create([
        'teacher_profile_id' => $profile->id,
        'title' => 'Physique Quantique et Relativité',
        'category' => 'Physique',
        'level' => 'Université',
        'format' => 'Les deux',
        'price_per_hour' => 15000,
        'description' => 'Programme complet incluant travaux dirigés et simulations numériques détaillées.',
        'status' => 'approved',
        'is_active' => true,
    ]);

    $response = $this->get(route('professeur.profil', $profile->id));
    $response->assertOk();

    $response->assertSee('Physique Quantique et Relativité');
    $response->assertSee('Voir le détail');
    $response->assertSee('En ligne &amp; Présentiel', false);
    $response->assertSee('course-detail-modal');
    $response->assertSee('openCourseModalById');
});

test('format des cours est formate clairement en francais', function () {
    $courseBoth = new Course(['format' => 'Les deux']);
    expect($courseBoth->formatted_format)->toBe('En ligne & Présentiel');

    $courseOnline = new Course(['format' => 'En ligne']);
    expect($courseOnline->formatted_format)->toBe('En ligne');

    $courseInPerson = new Course(['format' => 'Présentiel']);
    expect($courseInPerson->formatted_format)->toBe('Présentiel');
});

test('temps de reponse professeur est formate clairement en minutes et heures', function () {
    $profUser = User::factory()->create(['role' => 'professeur']);
    $profile = TeacherProfile::create([
        'user_id' => $profUser->id,
        'hourly_rate' => 10000,
        'bio' => 'Professeur réactif',
        'response_time' => 30, // 30 minutes
    ]);

    expect($profile->formatted_response_time)->toBe('30 minutes');

    $profile->response_time = 60;
    expect($profile->formatted_response_time)->toBe('1 heure');

    $profile->response_time = 120;
    expect($profile->formatted_response_time)->toBe('2 heures');

    $profile->response_time = null;
    expect($profile->formatted_response_time)->toBe('En quelques heures');

    // Vérifier l'affichage sur la page de profil
    $profile->response_time = 30;
    $profile->save();

    $response = $this->get(route('professeur.profil', $profile->id));
    $response->assertOk();
    $response->assertSee('30 minutes');

    // Vérifier que le formulaire d'édition contient les libellés clairs
    $editResponse = $this->actingAs($profUser)->get(route('professeur.edit-profil'));
    $editResponse->assertOk();
    $editResponse->assertSee('30 minutes (Rapide)');
    $editResponse->assertSee('1 heure');
    $editResponse->assertSee('2 heures');
    $editResponse->assertSee('24 heures (1 jour)');
});

test('champs du profil professeur sont strictement obligatoires et valides', function () {
    $profUser = User::factory()->create(['role' => 'professeur', 'ville' => 'Abidjan', 'phone' => '0700000000']);
    $profile = TeacherProfile::create([
        'user_id' => $profUser->id,
        'hourly_rate' => 10000,
        'bio' => 'Ancienne bio',
        'a_propos_cours' => 'Ancien cours',
    ]);

    // Test validation: envoi avec champs vides doit échouer
    $resFail = $this->actingAs($profUser)->post(route('professeur.update-profil'), []);
    $resFail->assertSessionHasErrors([
        'name', 'phone', 'ville', 'experience_years', 'bio', 'a_propos_cours', 'response_time'
    ]);

    // Test succès: envoi avec tous les champs requis du profil
    $resSuccess = $this->actingAs($profUser)->post(route('professeur.update-profil'), [
        'name' => 'Kouamé Jean-Baptiste',
        'phone' => '0701020304',
        'ville' => 'Abidjan Cocody',
        'experience_years' => '7 ans',
        'bio' => 'Agrégé de mathématiques pures et appliquées.',
        'a_propos_cours' => 'Méthode basée sur des exercices pratiques et fiches de synthèse.',
        'response_time' => 30,
    ]);

    $resSuccess->assertRedirect(route('professeur.dashboard'));
    $resSuccess->assertSessionHas('success');

    $profUser->refresh();
    $profile->refresh();

    expect($profUser->name)->toBe('Kouamé Jean-Baptiste')
        ->and($profUser->ville)->toBe('Abidjan Cocody')
        ->and($profUser->phone)->toBe('0701020304')
        ->and($profile->bio)->toBe('Agrégé de mathématiques pures et appliquées.')
        ->and($profile->a_propos_cours)->toBe('Méthode basée sur des exercices pratiques et fiches de synthèse.')
        ->and($profile->response_time)->toBe(30);
});

test('page de profil professeur affiche l avatar x-avatar de maniere harmonieuse et propre', function () {
    $profUser = User::factory()->create([
        'name' => 'Saliou Traoré',
        'role' => 'professeur',
    ]);
    $profile = TeacherProfile::create([
        'user_id' => $profUser->id,
        'hourly_rate' => 10000,
        'bio' => 'Professeur d informatique et de sciences.',
        'a_propos_cours' => 'Apprentissage interactif et suivi continu.',
        'response_time' => 15,
        'is_verified' => true,
    ]);

    Course::create([
        'teacher_profile_id' => $profile->id,
        'title' => 'Algorithmique Avancée',
        'category' => 'Informatique',
        'level' => 'Université',
        'format' => 'En ligne',
        'price_per_hour' => 10000,
        'status' => 'approved',
        'is_active' => true,
    ]);

    $response = $this->get(route('professeur.profil', $profile->id));
    $response->assertOk();

    // Doit contenir les initiales ST de Saliou Traoré générées par x-avatar
    $response->assertSee('ST');
    $response->assertSee('Saliou Traoré');
});

test('seuls les professeurs avec cours approuves apparaissent publiquement', function () {
    // Prof sans cours
    $profSansCours = User::factory()->create(['name' => 'Prof Sans Cours', 'role' => 'professeur']);
    $profile1 = TeacherProfile::create([
        'user_id' => $profSansCours->id,
        'hourly_rate' => 5000,
        'bio' => 'Nouveau prof',
    ]);

    // Prof avec cours en attente (non approuvé)
    $profCoursAttente = User::factory()->create(['name' => 'Prof Cours En Attente', 'role' => 'professeur']);
    $profile2 = TeacherProfile::create([
        'user_id' => $profCoursAttente->id,
        'hourly_rate' => 8000,
        'bio' => 'Prof en attente de validation',
    ]);
    Course::create([
        'teacher_profile_id' => $profile2->id,
        'title' => 'Cours Non Approuvé',
        'category' => 'Mathématiques',
        'level' => 'Lycée',
        'format' => 'En ligne',
        'price_per_hour' => 8000,
        'status' => 'pending',
        'is_active' => true,
    ]);

    // Prof avec cours approuvé
    $profApprouve = User::factory()->create(['name' => 'Prof Cours Approuve', 'role' => 'professeur']);
    $profile3 = TeacherProfile::create([
        'user_id' => $profApprouve->id,
        'hourly_rate' => 12000,
        'bio' => 'Prof validé',
    ]);
    Course::create([
        'teacher_profile_id' => $profile3->id,
        'title' => 'Cours Approuvé Validé',
        'category' => 'Français',
        'level' => 'Collège',
        'format' => 'Présentiel',
        'price_per_hour' => 12000,
        'status' => 'approved',
        'is_active' => true,
    ]);

    $response = $this->get('/cours');
    $response->assertOk();
    $response->assertSee('Prof Cours Approuve');
    $response->assertDontSee('Prof Sans Cours');
    $response->assertDontSee('Prof Cours En Attente');
});

test('categorie Langues est unifiee sans boutons de sous-langues comme Anglais ou Francais', function () {
    // Prof 1 : Anglais sous catégorie Langues
    $profLangue1 = User::factory()->create(['name' => 'Professeur John Doe', 'role' => 'professeur']);
    $profileLangue1 = TeacherProfile::create([
        'user_id' => $profLangue1->id,
        'hourly_rate' => 10000,
        'bio' => 'English teacher and linguist',
    ]);
    Course::create([
        'teacher_profile_id' => $profileLangue1->id,
        'title' => 'Anglais des Affaires & TOEIC',
        'category' => 'Langues',
        'level' => 'Tous niveaux',
        'format' => 'En ligne',
        'price_per_hour' => 10000,
        'status' => 'approved',
        'is_active' => true,
    ]);

    // Prof 2 : Espagnol sous catégorie Langues
    $profLangue2 = User::factory()->create(['name' => 'Professeur Carlos Gomez', 'role' => 'professeur']);
    $profileLangue2 = TeacherProfile::create([
        'user_id' => $profLangue2->id,
        'hourly_rate' => 12000,
        'bio' => 'Profesor de español',
    ]);
    Course::create([
        'teacher_profile_id' => $profileLangue2->id,
        'title' => 'Espagnol Débutant et Intermédiaire',
        'category' => 'Langues',
        'level' => 'Tous niveaux',
        'format' => 'Présentiel',
        'price_per_hour' => 12000,
        'status' => 'approved',
        'is_active' => true,
    ]);

    // Prof 3 : Mathématiques
    $profMaths = User::factory()->create(['name' => 'Professeur Kouamé Marc', 'role' => 'professeur']);
    $profileMaths = TeacherProfile::create([
        'user_id' => $profMaths->id,
        'hourly_rate' => 8000,
        'bio' => 'Professeur de mathématiques',
    ]);
    Course::create([
        'teacher_profile_id' => $profileMaths->id,
        'title' => 'Mathématiques et Géométrie',
        'category' => 'Mathématiques',
        'level' => 'Lycée',
        'format' => 'Présentiel',
        'price_per_hour' => 8000,
        'status' => 'approved',
        'is_active' => true,
    ]);

    // 1. La page cours affiche la catégorie Langues et n'affiche pas de boutons sous-langues
    $resIndex = $this->get('/cours');
    $resIndex->assertOk();
    $resIndex->assertSee('>Langues<', false);
    $resIndex->assertDontSee('>Anglais<', false);
    $resIndex->assertDontSee('>Français<', false);
    $resIndex->assertDontSee('>Espagnol<', false);

    // 2. Clic sur la catégorie "Langues" -> affiche tous les profs de langues
    $resLangues = $this->get('/cours?categorie=Langues');
    $resLangues->assertOk();
    $resLangues->assertSee('Professeur John Doe');
    $resLangues->assertSee('Professeur Carlos Gomez');
    $resLangues->assertDontSee('Professeur Kouamé Marc');

    // 3. Recherche textuelle "Anglais" -> permet de cibler un cours précis dans la barre de recherche
    $resSearch = $this->get('/cours?q=Anglais');
    $resSearch->assertOk();
    $resSearch->assertSee('Professeur John Doe');
    $resSearch->assertDontSee('Professeur Carlos Gomez');
    $resSearch->assertDontSee('Professeur Kouamé Marc');
});

test('sidebar des tableaux de bord est fixe et intacte pendant que le contenu de la page scrolle', function () {
    $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));
    $response->assertOk();

    // Doit avoir un body sans scroll global et une sidebar fixe indépendante
    $response->assertSee('h-screen overflow-hidden', false);
    $response->assertSee('aside class="hidden lg:flex flex-col w-64 h-screen shrink-0 border-r border-white/[0.08] overflow-y-auto z-30"', false);
    $response->assertSee('flex flex-col flex-1 min-w-0 h-screen overflow-y-auto overflow-x-hidden', false);

    // Doit contenir le bouton de déconnexion dans la sidebar
    $response->assertSee('action="' . route('logout') . '"', false);
    $response->assertSee('Déconnexion');
});

test('messages d erreur de connexion et d inscription sont en francais', function () {
    // 1. Test erreur de connexion avec mauvais mot de passe
    $user = User::factory()->create(['email' => 'test_auth@kimboo.net', 'password' => bcrypt('bon_password')]);
    $resLogin = $this->post('/login', [
        'email' => 'test_auth@kimboo.net',
        'password' => 'mauvais_password',
    ]);
    $resLogin->assertSessionHasErrors('email');
    $errors = session('errors')->get('email');
    expect($errors[0])->toBe('Ces identifiants ne correspondent à aucun compte enregistré.');

    // 2. Test erreur de validation d'inscription (champs obligatoires)
    $resRegister = $this->post('/register', []);
    $resRegister->assertSessionHasErrors(['name', 'email', 'password', 'role']);
    $nameError = session('errors')->get('name')[0];
    expect($nameError)->toContain('obligatoire');
});

test('favicon officiel est present sur le site public, les dashboards et la page de connexion', function () {
    $resHome = $this->get('/');
    $resHome->assertOk();
    $resHome->assertSee('favicon.svg', false);
    $resHome->assertSee('favicon-32x32.png', false);
    $resHome->assertSee('favicon.ico', false);

    $resLogin = $this->get('/login');
    $resLogin->assertOk();
    $resLogin->assertSee('favicon.svg', false);

    $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);
    $resDash = $this->actingAs($admin)->get(route('admin.dashboard'));
    $resDash->assertOk();
    $resDash->assertSee('favicon.svg', false);
});

test('acces a la messagerie requiert une authentification et redirige les invites sans erreur 500', function () {
    // 1. Invité -> redirection vers login
    $resGuest = $this->get(route('messages.index'));
    $resGuest->assertRedirect(route('login'));

    // 2. Utilisateur connecté -> accès direct 200 OK
    $user = User::factory()->create(['role' => 'eleve']);
    $resAuth = $this->actingAs($user)->get(route('messages.index'));
    $resAuth->assertOk();
    $resAuth->assertSee('Vos conversations');
});

test('professeur peut voir ses cours enregistres avant le formulaire d ajout', function () {
    $user = User::factory()->create(['role' => 'professeur']);
    $profile = TeacherProfile::create([
        'user_id' => $user->id,
        'hourly_rate' => 12000,
        'bio' => 'Professeur expérimenté',
        'a_propos_cours' => 'Méthode dynamique et interactive',
        'response_time' => 30,
    ]);

    $course = Course::create([
        'teacher_profile_id' => $profile->id,
        'title' => 'Algèbre Linéaire Avancée',
        'category' => 'Mathématiques',
        'level' => 'Université',
        'format' => 'Les deux',
        'price_per_hour' => 12000,
        'status' => 'approved',
        'is_active' => true,
    ]);

    $response = $this->actingAs($user)->get(route('professeur.create-cours'));
    $response->assertOk();
    $response->assertSee('Mes cours enregistrés');
    $response->assertSee('Algèbre Linéaire Avancée');
    $response->assertSee('En ligne &amp; Présentiel', false);
    $response->assertSee('Publier un nouveau cours');
    $response->assertSee('nouveau-cours-form');
});

test('zone d intervention et deplacement est formatee clairement en km ou metres', function () {
    $profUser = User::factory()->create(['role' => 'professeur', 'ville' => 'Abidjan']);
    $profile = TeacherProfile::create([
        'user_id' => $profUser->id,
        'hourly_rate' => 10000,
        'bio' => 'Bio test',
        'a_propos_cours' => 'Cours test',
        'response_time' => 15,
        'zone_deplacement' => '200',
    ]);

    expect($profile->formatted_zone_deplacement)->toBe('200 m');

    $profile->zone_deplacement = '15';
    expect($profile->formatted_zone_deplacement)->toBe('15 km');

    $profile->zone_deplacement = '10 km (autour de Cocody)';
    expect($profile->formatted_zone_deplacement)->toBe('10 km (autour de Cocody)');

    // Test de soumission avec zone_distance et zone_unit
    $response = $this->actingAs($profUser)->post(route('professeur.update-profil'), [
        'name' => 'Professeur Test Zone',
        'ville' => 'Abidjan',
        'phone' => '0102030405',
        'experience_years' => '5 ans',
        'bio' => 'Bio de test suffisamment longue pour validation',
        'a_propos_cours' => 'Pédagogie de test suffisamment longue pour validation',
        'response_time' => 30,
        'zone_distance' => '25',
        'zone_unit' => 'km',
        'zone_precisions' => 'autour de Plateau',
    ]);

    $response->assertRedirect(route('professeur.dashboard'));
    $profile->refresh();
    expect($profile->zone_deplacement)->toBe('25 km (autour de Plateau)');
});

test('premiere heure offerte est configuree au niveau du cours et synchronisee avec le profil', function () {
    $profUser = User::factory()->create(['role' => 'professeur']);
    $profile = TeacherProfile::create([
        'user_id' => $profUser->id,
        'hourly_rate' => 15000,
        'bio' => 'Professeur certifié',
        'a_propos_cours' => 'Méthodologie claire',
        'first_course_free' => false,
    ]);

    // 1. Création d'un cours avec 1ère heure offerte
    $response = $this->actingAs($profUser)->post(route('professeur.store-cours'), [
        'title' => 'Cours de SVT avec 1ère séance offerte',
        'category' => 'Biologie',
        'level' => 'Lycée',
        'format' => 'En ligne',
        'price_per_hour' => 12000,
        'first_course_free' => 1,
    ]);

    $response->assertRedirect(route('professeur.dashboard'));

    $course = Course::where('title', 'Cours de SVT avec 1ère séance offerte')->first();
    expect($course)->not->toBeNull()
        ->and($course->first_course_free)->toBeTrue();

    $profile->refresh();
    expect($profile->first_course_free)->toBeTrue();
});

test('admin peut reserver un cours sans erreur 403 et est redirige vers dashboard admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $prof = User::factory()->create(['role' => 'professeur']);
    $profile = TeacherProfile::create([
        'user_id' => $prof->id,
        'hourly_rate' => 10000,
        'bio' => 'Professeur expérimenté',
    ]);
    $course = Course::create([
        'teacher_profile_id' => $profile->id,
        'title' => 'Mathématiques Supérieures',
        'category' => 'Mathématiques',
        'level' => 'Université',
        'format' => 'En ligne',
        'price_per_hour' => 10000,
        'status' => 'approved',
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)->post(route('booking.store', $profile->id), [
        'course_id' => $course->id,
        'scheduled_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
        'duration_hours' => 2,
    ]);

    $response->assertRedirect(route('admin.dashboard'));
    $response->assertSessionHas('success');
    expect(Booking::where('user_id', $admin->id)->exists())->toBeTrue();
});

test('acces a une section d un autre role redirige gracieusement vers le tableau de bord approprie sans 403', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    // Admin essaie d'accéder à l'espace élève
    $response = $this->actingAs($admin)->get(route('eleve.reservations'));
    $response->assertRedirect(route('admin.dashboard'));
    $response->assertSessionHas('error');

    // Admin essaie d'accéder à l'espace professeur
    $responseProf = $this->actingAs($admin)->get(route('professeur.dashboard'));
    $responseProf->assertRedirect(route('admin.dashboard'));
    $responseProf->assertSessionHas('error');
});

test('envoi de donnees bancaires est bloque mais numeros de telephone sont toleres', function () {
    $sender = User::factory()->create(['role' => 'eleve']);
    $receiver = User::factory()->create(['role' => 'professeur']);

    // Mock Mail::send to throw an exception like Mailtrap rate limit
    Mail::shouldReceive('to')->andReturnSelf();
    Mail::shouldReceive('send')->andThrow(new \Symfony\Component\Mailer\Exception\TransportException('550 5.7.0 Too many emails per second'));

    // Message avec contenu bancaire interdit
    $responseBank = $this->actingAs($sender)
        ->from(route('messages.show', $receiver->id))
        ->post(route('messages.send', $receiver->id), [
            'content' => 'Voici ma carte bancaire 4532 1234 5678 9010 et mon IBAN CI001234567890123456',
        ]);

    $responseBank->assertRedirect(route('messages.show', $receiver->id));
    $responseBank->assertSessionHas('error');
    expect(Message::where('sender_id', $sender->id)->where('is_blocked', true)->exists())->toBeTrue();

    // Message avec numéro de téléphone (toléré)
    $responsePhone = $this->actingAs($sender)->post(route('messages.send', $receiver->id), [
        'content' => 'Vous pouvez me joindre au 0708091011 pour organiser la séance.',
    ]);

    $responsePhone->assertRedirect(route('messages.show', $receiver->id));
    expect(Message::where('sender_id', $sender->id)->where('is_blocked', false)->exists())->toBeTrue();
});

test('espace admin permet de surveiller toutes les conversations et filtrer avec details', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user1 = User::factory()->create(['role' => 'eleve', 'name' => 'Kouassi Jean']);
    $user2 = User::factory()->create(['role' => 'professeur', 'name' => 'Professeur Martin']);

    Message::create([
        'sender_id' => $user1->id,
        'receiver_id' => $user2->id,
        'content' => 'Bonjour professeur, êtes-vous disponible ?',
        'is_read' => true,
        'is_blocked' => false,
    ]);

    $resConversations = $this->actingAs($admin)->get(route('admin.messages.conversations'));
    $resConversations->assertOk();
    $resConversations->assertSee('Kouassi Jean', false);
    $resConversations->assertSee('Professeur Martin', false);
    $resConversations->assertSee('Conversations &amp; Assistance', false);
    $resConversations->assertSee('admin-user-modal', false);

    $resVoir = $this->actingAs($admin)->get(route('admin.messages.voir', [$user1->id, $user2->id]));
    $resVoir->assertOk();
    $resVoir->assertSee('Bonjour professeur, êtes-vous disponible ?', false);
    $resVoir->assertSee('Surveillance Admin', false);
    $resVoir->assertSee('Écrire à Kouassi Jean', false);
    $resVoir->assertSee('Écrire à Professeur Martin', false);
});

test('pages d erreur personnalisees kimboo sont configurées et fonctionnelles', function () {
    expect(view()->exists('errors.403'))->toBeTrue()
        ->and(view()->exists('errors.404'))->toBeTrue()
        ->and(view()->exists('errors.419'))->toBeTrue()
        ->and(view()->exists('errors.500'))->toBeTrue()
        ->and(view()->exists('errors.503'))->toBeTrue();

    // Tester le rendu de la page 404
    $response = $this->get('/page-totalement-inexistante-pour-tester-404');
    $response->assertNotFound();
    $response->assertSee('kimboo', false);
    $response->assertSee('Cette page semble introuvable', false);
});

test('modals interactifs de details utilisateurs et cours sont presents dans les vues admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $prof = User::factory()->create(['role' => 'professeur']);
    $profile = TeacherProfile::create([
        'user_id' => $prof->id,
        'hourly_rate' => 12000,
        'bio' => 'Professeur certifié Kimboo',
    ]);
    $course = Course::create([
        'teacher_profile_id' => $profile->id,
        'title' => 'Sciences Physiques Prépa',
        'category' => 'Physique',
        'level' => 'Lycée',
        'format' => 'En ligne',
        'price_per_hour' => 12000,
        'status' => 'pending',
        'is_active' => false,
    ]);

    // 1. Page Utilisateurs Admin
    $resUsers = $this->actingAs($admin)->get(route('admin.users'));
    $resUsers->assertOk();
    $resUsers->assertSee('admin-user-detail-modal', false);
    $resUsers->assertSee('openAdminUserModal', false);

    // 2. Dashboard Admin
    $resDash = $this->actingAs($admin)->get(route('admin.dashboard'));
    $resDash->assertOk();
    $resDash->assertSee('admin-user-detail-modal', false);
    $resDash->assertSee('openAdminUserModal', false);

    // 3. Page Cours Admin
    $resCours = $this->actingAs($admin)->get(route('admin.cours'));
    $resCours->assertOk();
    $resCours->assertSee('admin-course-detail-modal', false);
    $resCours->assertSee('openAdminCourseModal', false);
});

test('messagerie avec assistance kimboo et bandeau whatsapp conditionnel apres 24h sans reponse', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'name' => 'Assistance Kimboo',
        'email' => 'assistance@kimboo.net',
    ]);
    $eleve = User::factory()->create(['role' => 'eleve']);

    // 1. Redirection vers l'assistance Kimboo
    $resAssistance = $this->actingAs($eleve)->get(route('messages.assistance'));
    $resAssistance->assertRedirect(route('messages.show', $admin->id));

    // 2. Affichage initial sans message ou avec message récent (< 24h) : pas de bandeau 24h, pas d'étoile ni de doublon
    $resShow1 = $this->actingAs($eleve)->get(route('messages.show', $admin->id));
    $resShow1->assertOk();
    $resShow1->assertSee('Assistance Kimboo', false);
    $resShow1->assertSee('En ligne', false);
    $resShow1->assertDontSee('⭐', false);
    $resShow1->assertDontSee('Support officiel', false);
    $resShow1->assertDontSee('Délai dépassé (24h)', false);

    // 3. Message envoyé il y a 2h : toujours pas de bandeau
    $msgRecent = Message::create([
        'sender_id' => $eleve->id,
        'receiver_id' => $admin->id,
        'content' => 'Bonjour, j\'ai une question',
        'created_at' => now()->subHours(2),
    ]);
    $resShow2 = $this->actingAs($eleve)->get(route('messages.show', $admin->id));
    $resShow2->assertDontSee('Délai dépassé (24h)', false);

    // 4. Message envoyé il y a 25h sans réponse : le bandeau 24h apparaît
    $msgRecent->timestamps = false;
    $msgRecent->created_at = now()->subHours(25);
    $msgRecent->save();

    $resShow3 = $this->actingAs($eleve)->get(route('messages.show', $admin->id));
    $resShow3->assertSee('Délai dépassé (24h)', false);
    $resShow3->assertSee('Contacter sur WhatsApp', false);
    $resShow3->assertSee('wa.me', false);

    // 5. L'assistance répond : le bandeau 24h disparaît
    Message::create([
        'sender_id' => $admin->id,
        'receiver_id' => $eleve->id,
        'content' => 'Bonjour ! Nous vous répondons.',
        'created_at' => now()->subMinutes(10),
    ]);
    $resShow4 = $this->actingAs($eleve)->get(route('messages.show', $admin->id));
    $resShow4->assertDontSee('Délai dépassé (24h)', false);

    // 6. Liste des messages avec carte d'assistance
    $resIndex = $this->actingAs($eleve)->get(route('messages.index'));
    $resIndex->assertOk();
    $resIndex->assertSee('Assistance Officielle Kimboo', false);
    $resIndex->assertSee('Écrire à l\'assistance', false);
});

test('upload de fichiers et pieces jointes dans les messages persiste en base de donnees', function () {
    \Illuminate\Support\Facades\Storage::fake('public');
    Mail::fake();

    $sender = User::factory()->create(['role' => 'eleve']);
    $receiver = User::factory()->create(['role' => 'professeur']);
    $admin = User::factory()->create(['role' => 'admin']);

    $pdfFile = \Illuminate\Http\UploadedFile::fake()->create('cours_mathematiques.pdf', 500, 'application/pdf');

    // 1. Envoi d'un message avec fichier PDF joint
    $response = $this->actingAs($sender)->post(route('messages.send', $receiver->id), [
        'content' => 'Voici l exercice résolu en PDF.',
        'attachment' => $pdfFile,
    ]);

    $response->assertRedirect(route('messages.show', $receiver->id));

    $message = Message::where('sender_id', $sender->id)->where('receiver_id', $receiver->id)->latest()->first();
    expect($message)->not->toBeNull();
    expect($message->attachment_name)->toBe('cours_mathematiques.pdf');
    expect($message->attachment_type)->toBe('application/pdf');
    expect($message->attachment_size)->toBeGreaterThan(0);
    expect($message->hasAttachment())->toBeTrue();
    expect($message->isImageAttachment())->toBeFalse();
    expect($message->formatted_attachment_size)->toContain('Ko');

    \Illuminate\Support\Facades\Storage::disk('public')->assertExists($message->attachment);

    // 2. Affichage dans la vue de conversation pour l'élève et le professeur
    $resShow = $this->actingAs($receiver)->get(route('messages.show', $sender->id));
    $resShow->assertOk();
    $resShow->assertSee('cours_mathematiques.pdf');
    $resShow->assertSee('Télécharger');

    // 3. Affichage dans la vue de surveillance admin
    $resAdminVoir = $this->actingAs($admin)->get(route('admin.messages.voir', [$sender->id, $receiver->id]));
    $resAdminVoir->assertOk();
    $resAdminVoir->assertSee('cours_mathematiques.pdf');
    $resAdminVoir->assertSee('Télécharger');
});

test('assistance kimboo peut diffuser un message a tous les professeurs, eleves ou selection personnalisee', function () {
    \Illuminate\Support\Facades\Storage::fake('public');
    Mail::fake();

    $admin = User::factory()->create(['role' => 'admin', 'name' => 'Assistance Kimboo']);
    $prof1 = User::factory()->create(['role' => 'professeur', 'name' => 'Professeur Alpha']);
    $prof2 = User::factory()->create(['role' => 'professeur', 'name' => 'Professeur Beta']);
    $eleve1 = User::factory()->create(['role' => 'eleve', 'name' => 'Élève Charles']);
    $eleve2 = User::factory()->create(['role' => 'eleve', 'name' => 'Élève David']);

    // 1. Diffusion à tous les professeurs uniquement
    $resTeachers = $this->actingAs($admin)->post(route('admin.messages.broadcast'), [
        'target_audience' => 'all_teachers',
        'content'         => 'Rappel pédagogique : mise à jour des disponibilités.',
    ]);

    $resTeachers->assertRedirect(route('admin.messages.conversations'));
    $resTeachers->assertSessionHas('success');

    expect(Message::where('sender_id', $admin->id)->where('receiver_id', $prof1->id)->where('content', 'Rappel pédagogique : mise à jour des disponibilités.')->exists())->toBeTrue();
    expect(Message::where('sender_id', $admin->id)->where('receiver_id', $prof2->id)->where('content', 'Rappel pédagogique : mise à jour des disponibilités.')->exists())->toBeTrue();
    expect(Message::where('sender_id', $admin->id)->where('receiver_id', $eleve1->id)->where('content', 'Rappel pédagogique : mise à jour des disponibilités.')->exists())->toBeFalse();

    // 2. Diffusion ciblée avec pièce jointe (sélection personnalisée : prof1 et eleve1)
    $docFile = \Illuminate\Http\UploadedFile::fake()->create('guide_kimboo.pdf', 300, 'application/pdf');

    $resCustom = $this->actingAs($admin)->post(route('admin.messages.broadcast'), [
        'target_audience' => 'custom',
        'user_ids'        => [$prof1->id, $eleve1->id],
        'content'         => 'Voici le nouveau guide de la plateforme.',
        'attachment'      => $docFile,
    ]);

    $resCustom->assertRedirect(route('admin.messages.conversations'));
    $resCustom->assertSessionHas('success');

    $msgCustom1 = Message::where('sender_id', $admin->id)->where('receiver_id', $prof1->id)->where('content', 'Voici le nouveau guide de la plateforme.')->first();
    $msgCustom2 = Message::where('sender_id', $admin->id)->where('receiver_id', $eleve1->id)->where('content', 'Voici le nouveau guide de la plateforme.')->first();

    expect($msgCustom1)->not->toBeNull();
    expect($msgCustom2)->not->toBeNull();
    expect($msgCustom1->attachment_name)->toBe('guide_kimboo.pdf');
    expect($msgCustom2->attachment_name)->toBe('guide_kimboo.pdf');
    expect($msgCustom1->attachment)->toBe($msgCustom2->attachment); // même fichier stocké

    // prof2 et eleve2 n'ont pas reçu le message custom
    expect(Message::where('sender_id', $admin->id)->where('receiver_id', $prof2->id)->where('content', 'Voici le nouveau guide de la plateforme.')->exists())->toBeFalse();
    expect(Message::where('sender_id', $admin->id)->where('receiver_id', $eleve2->id)->where('content', 'Voici le nouveau guide de la plateforme.')->exists())->toBeFalse();

    // 3. Test de tolérance aux pannes email avec Log::warning
    \Illuminate\Support\Facades\Mail::shouldReceive('to')->andThrow(new \Exception('SMTP connection timeout'));
    $resFailMail = $this->actingAs($admin)->post(route('admin.messages.broadcast'), [
        'target_audience' => 'all_students',
        'content'         => 'Message diffusé même si le serveur SMTP est indisponible.',
    ]);
    $resFailMail->assertRedirect(route('admin.messages.conversations'));
    $resFailMail->assertSessionHas('success');
    expect(Message::where('sender_id', $admin->id)->where('content', 'Message diffusé même si le serveur SMTP est indisponible.')->count())->toBe(2);
});

test('admin peut suspendre un compte utilisateur, envoyer un email et un message in-app de l assistance', function () {
    Mail::fake();

    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create([
        'role' => 'eleve',
        'name' => 'Jean Suspendu',
        'email' => 'jean.suspendu@example.com',
        'is_suspended' => false,
    ]);

    $response = $this->actingAs($admin)->patch(route('admin.suspend-user', $user->id), [
        'reason' => 'Signalements répétitifs pour comportement inapproprié.',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $user->refresh();
    expect($user->is_suspended)->toBeTrue();
    expect($user->suspended_at)->not->toBeNull();
    expect($user->suspension_reason)->toBe('Signalements répétitifs pour comportement inapproprié.');

    // Vérifier l'envoi de l'email de notification de suspension
    Mail::assertSent(\App\Mail\CompteSuspendu::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email) && $mail->reason === 'Signalements répétitifs pour comportement inapproprié.';
    });

    // Vérifier la réception du message in-app de l'Assistance Kimboo
    $inboxMessage = Message::where('sender_id', $admin->id)->where('receiver_id', $user->id)->latest()->first();
    expect($inboxMessage)->not->toBeNull();
    expect($inboxMessage->content)->toContain('Votre compte Kimboo a été suspendu par l\'administration.');
    expect($inboxMessage->content)->toContain('Signalements répétitifs pour comportement inapproprié.');
});

test('un utilisateur suspendu conserve l acces a son compte mais ne peut contacter que l assistance kimboo', function () {
    $password = 'password123';
    $admin = User::factory()->create(['role' => 'admin', 'name' => 'Assistance Kimboo']);
    $otherUser = User::factory()->create(['role' => 'professeur', 'name' => 'Professeur Destinataire']);
    
    $suspendedUser = User::factory()->create([
        'role' => 'eleve',
        'email' => 'suspendu@example.com',
        'password' => \Illuminate\Support\Facades\Hash::make($password),
        'is_suspended' => true,
        'suspended_at' => now(),
        'suspension_reason' => 'Vérification de compte en cours.',
    ]);

    // 1. L'utilisateur suspendu PEUT se connecter normalement à son compte
    $loginResponse = $this->post('/login', [
        'email' => 'suspendu@example.com',
        'password' => $password,
    ]);
    $loginResponse->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs($suspendedUser);

    // 2. Le tableau de bord affiche le bandeau d'alerte de suspension avec bouton Assistance
    $dashboardRes = $this->actingAs($suspendedUser)->get(route('eleve.dashboard'));
    $dashboardRes->assertOk();
    $dashboardRes->assertSee('Compte actuellement suspendu');
    $dashboardRes->assertSee('Vérification de compte en cours.');
    $dashboardRes->assertSee('Contacter l\'Assistance', false);

    // 3. L'utilisateur NE PEUT PAS envoyer de message à un autre membre
    $msgOtherRes = $this->actingAs($suspendedUser)->post(route('messages.send', $otherUser->id), [
        'content' => 'Bonjour professeur je souhaite un cours',
    ]);
    $msgOtherRes->assertRedirect(route('messages.show', $admin->id));
    $msgOtherRes->assertSessionHas('error');
    expect(Message::where('sender_id', $suspendedUser->id)->where('receiver_id', $otherUser->id)->exists())->toBeFalse();

    // 4. L'utilisateur PEUT envoyer un message à l'Assistance Kimboo (Admin)
    $msgAdminRes = $this->actingAs($suspendedUser)->post(route('messages.send', $admin->id), [
        'content' => 'Bonjour assistance, voici les pièces justificatives demandées.',
    ]);
    $msgAdminRes->assertRedirect(route('messages.show', $admin->id));
    expect(Message::where('sender_id', $suspendedUser->id)->where('receiver_id', $admin->id)->where('content', 'Bonjour assistance, voici les pièces justificatives demandées.')->exists())->toBeTrue();

    // 5. L'utilisateur suspendu NE PEUT PAS réserver un cours
    $profProfile = TeacherProfile::create([
        'user_id' => $otherUser->id,
        'hourly_rate' => 10000,
        'bio' => 'Bio test',
    ]);
    $course = Course::create([
        'teacher_profile_id' => $profProfile->id,
        'title' => 'Maths Sup',
        'category' => 'Mathématiques',
        'level' => 'Lycée',
        'format' => 'En ligne',
        'price_per_hour' => 10000,
        'status' => 'approved',
        'is_active' => true,
    ]);

    $bookingRes = $this->actingAs($suspendedUser)->post(route('booking.store', $profProfile->id), [
        'course_id' => $course->id,
        'scheduled_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
        'duration_hours' => 2,
    ]);
    $bookingRes->assertRedirect(route('messages.show', $admin->id));
    $bookingRes->assertSessionHas('error');
    expect(Booking::where('user_id', $suspendedUser->id)->exists())->toBeFalse();
});

test('admin peut lever la suspension d un compte utilisateur et le reactiver', function () {
    Mail::fake();

    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create([
        'role' => 'professeur',
        'name' => 'Claire Reactivée',
        'email' => 'claire.reactivee@example.com',
        'is_suspended' => true,
        'suspended_at' => now()->subDays(2),
        'suspension_reason' => 'Vérification de documents.',
    ]);

    $response = $this->actingAs($admin)->patch(route('admin.reactivate-user', $user->id));

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $user->refresh();
    expect($user->is_suspended)->toBeFalse();
    expect($user->suspended_at)->toBeNull();
    expect($user->suspension_reason)->toBeNull();

    // Vérifier l'envoi de l'email de réactivation
    Mail::assertSent(\App\Mail\CompteReactive::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });

    // Vérifier l'envoi du message in-app confirmant la réactivation
    $reactivationMessage = Message::where('sender_id', $admin->id)->where('receiver_id', $user->id)->latest()->first();
    expect($reactivationMessage)->not->toBeNull();
    expect($reactivationMessage->content)->toContain('Bonne nouvelle ! La suspension de votre compte Kimboo a été levée');
});

test('admin ne peut pas suspendre un autre compte administrateur', function () {
    $admin1 = User::factory()->create(['role' => 'admin']);
    $admin2 = User::factory()->create(['role' => 'admin', 'is_suspended' => false]);

    $response = $this->actingAs($admin1)->patch(route('admin.suspend-user', $admin2->id), [
        'reason' => 'Tentative impossible',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error');

    $admin2->refresh();
    expect($admin2->is_suspended)->toBeFalse();
});

test('vue admin utilisateurs filtre par statut tous, actifs et suspendus', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $activeUser = User::factory()->create(['role' => 'eleve', 'name' => 'Eleve Actif Unique', 'is_suspended' => false]);
    $suspendedUser = User::factory()->create(['role' => 'professeur', 'name' => 'Prof Suspendu Unique', 'is_suspended' => true]);

    // Onglet Tous
    $resAll = $this->actingAs($admin)->get(route('admin.users', ['status' => 'all']));
    $resAll->assertOk();
    $resAll->assertSee('Eleve Actif Unique');
    $resAll->assertSee('Prof Suspendu Unique');

    // Onglet Actifs
    $resActive = $this->actingAs($admin)->get(route('admin.users', ['status' => 'active']));
    $resActive->assertOk();
    $resActive->assertSee('Eleve Actif Unique');
    $resActive->assertDontSee('Prof Suspendu Unique');

    // Onglet Suspendus
    $resSuspended = $this->actingAs($admin)->get(route('admin.users', ['status' => 'suspended']));
    $resSuspended->assertOk();
    $resSuspended->assertSee('Prof Suspendu Unique');
    $resSuspended->assertDontSee('Eleve Actif Unique');
});






