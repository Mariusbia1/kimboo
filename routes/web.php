<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\ProfesseurController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FavoriteController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/qui-sommes-nous', function () {
    return view('qui-sommes-nous');
})->name('qui-sommes-nous');

Route::get('/politique-confidentialite', function () {
    return view('politique-confidentialite');
})->name('politique-confidentialite');

Route::get('/cours', [CoursController::class, 'index'])->name('cours.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::post('/user/heartbeat', function () {
        $user = auth()->user();
        if ($user) {
            $user->increment('time_spent_seconds', 60);
            session(['user_last_active_at' => now()]);
        }
        return response()->json(['status' => 'ok']);
    })->name('user.heartbeat');

    // Réservations
    Route::post('/professeur/{id}/reserver', [HomeController::class, 'storeBooking'])->whereNumber('id')->name('booking.store');

    // Messagerie
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/assistance', [MessageController::class, 'assistance'])->name('messages.assistance');
    Route::get('/messages/{userId}', [MessageController::class, 'show'])->whereNumber('userId')->name('messages.show');
    Route::post('/messages/{userId}', [MessageController::class, 'send'])->whereNumber('userId')->name('messages.send');

    // Favoris
    Route::post('/favoris/{profileId}', [FavoriteController::class, 'toggle'])->whereNumber('profileId')->name('favoris.toggle');
    Route::get('/favoris', [FavoriteController::class, 'index'])->name('favoris.index');

    // Espace élève
    Route::middleware(['role:eleve'])->prefix('eleve')->name('eleve.')->group(function () {
        Route::get('/dashboard', [EleveController::class, 'dashboard'])->name('dashboard');
        Route::get('/mes-cours', [EleveController::class, 'mesCours'])->name('mes-cours');
        Route::get('/reservations', [EleveController::class, 'mesReservations'])->name('reservations');
        Route::patch('/reservations/{id}/cancel', [EleveController::class, 'cancelBooking'])->whereNumber('id')->name('booking.cancel');

        Route::patch('/reservations/{id}/terminer', [EleveController::class, 'terminerBooking'])->whereNumber('id')->name('booking.terminer');
        Route::post('/reservations/{id}/avis', [EleveController::class, 'storeAvis'])->whereNumber('id')->name('booking.avis');
        Route::get('/profil', [EleveController::class, 'editProfil'])->name('edit-profil');
        Route::post('/profil', [EleveController::class, 'updateProfil'])->name('update-profil');
        Route::delete('/profil', [EleveController::class, 'destroyAccount'])->name('delete-account');
        Route::get('/calendrier', [EleveController::class, 'calendrier'])->name('calendrier');
    });

    // Espace professeur
    Route::middleware(['role:professeur'])->prefix('professeur')->name('professeur.')->group(function () {
        Route::get('/dashboard', [ProfesseurController::class, 'dashboard'])->name('dashboard');
        Route::get('/profil/modifier', [ProfesseurController::class, 'editProfil'])->name('edit-profil');
        Route::post('/profil/modifier', [ProfesseurController::class, 'updateProfil'])->name('update-profil');
        Route::get('/cours/ajouter', [ProfesseurController::class, 'createCours'])->name('create-cours');
        Route::post('/cours/ajouter', [ProfesseurController::class, 'storeCours'])->name('store-cours');
        Route::get('/cours/{id}/modifier', [ProfesseurController::class, 'editCours'])->whereNumber('id')->name('edit-cours');
        Route::put('/cours/{id}', [ProfesseurController::class, 'updateCours'])->whereNumber('id')->name('update-cours');
        Route::delete('/cours/{id}', [ProfesseurController::class, 'deleteCours'])->whereNumber('id')->name('delete-cours');
        Route::get('/reservations', [ProfesseurController::class, 'reservations'])->name('reservations');
        Route::patch('/reservations/{id}/confirm', [ProfesseurController::class, 'confirmBooking'])->whereNumber('id')->name('booking.confirm');
        Route::patch('/reservations/{id}/cancel', [ProfesseurController::class, 'cancelBooking'])->whereNumber('id')->name('booking.cancel');
        Route::get('/calendrier', [ProfesseurController::class, 'calendrier'])->name('calendrier');
    });

    // Espace admin
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/utilisateurs', [AdminController::class, 'users'])->name('users');
        Route::get('/statistiques', [StatsController::class, 'index'])->name('stats');

        Route::patch('/professeurs/{id}/certifier', [AdminController::class, 'certifierProfesseur'])->whereNumber('id')->name('certifier');
        Route::patch('/professeurs/{id}/decertifier', [AdminController::class, 'decertifierProfesseur'])->whereNumber('id')->name('decertifier');
        Route::patch('/professeurs/{id}/feature', [AdminController::class, 'featureProfesseur'])->whereNumber('id')->name('feature');
        Route::patch('/professeurs/{id}/unfeature', [AdminController::class, 'unfeatureProfesseur'])->whereNumber('id')->name('unfeature');
        Route::delete('/utilisateurs/{id}', [AdminController::class, 'deleteUser'])->whereNumber('id')->name('delete-user');
        Route::patch('/utilisateurs/{id}/suspendre', [AdminController::class, 'suspendUser'])->whereNumber('id')->name('suspend-user');
        Route::patch('/utilisateurs/{id}/reactiver', [AdminController::class, 'reactivateUser'])->whereNumber('id')->name('reactivate-user');
        Route::get('/cours', [AdminController::class, 'cours'])->name('cours');
        Route::get('/cours/{id}', [AdminController::class, 'showCours'])->whereNumber('id')->name('cours.show');
        Route::patch('/cours/{id}/approuver', [AdminController::class, 'approuverCours'])->whereNumber('id')->name('cours.approuver');
        Route::patch('/cours/{id}/refuser', [AdminController::class, 'refuserCours'])->whereNumber('id')->name('cours.refuser');
        Route::delete('/cours/{id}', [AdminController::class, 'supprimerCours'])->whereNumber('id')->name('cours.supprimer');
        Route::get('/messages/alertes', [AdminController::class, 'messagesAlertes'])->name('messages.alertes');
        Route::get('/messages/conversations', [AdminController::class, 'messagesConversations'])->name('messages.conversations');
        Route::post('/messages/broadcast', [AdminController::class, 'broadcastMessage'])->name('messages.broadcast');
        Route::get('/messages/conversations/{userId1}/{userId2}', [AdminController::class, 'voirConversation'])->whereNumber('userId1')->whereNumber('userId2')->name('messages.voir');
        Route::patch('/messages/conversations/{userId1}/{userId2}/resoudre-alertes', [AdminController::class, 'resoudreAlertesConversation'])->whereNumber('userId1')->whereNumber('userId2')->name('messages.resoudre-alertes');
        Route::patch('/messages/alertes/{id}/reviewed', [AdminController::class, 'alerteReviewed'])->whereNumber('id')->name('messages.alertes.reviewed');
        Route::patch('/messages/alertes/{id}/ignored', [AdminController::class, 'alerteIgnored'])->whereNumber('id')->name('messages.alertes.ignored');
        Route::get('/parametres', [AdminController::class, 'parametres'])->name('parametres');
        Route::post('/parametres', [AdminController::class, 'updateParametres'])->name('parametres.update');
        Route::post('/parametres/tester-email', [AdminController::class, 'testerEmail'])->name('parametres.tester-email');
        Route::get('/profil', [AdminController::class, 'editProfil'])->name('profil');
        Route::post('/profil', [AdminController::class, 'updateProfil'])->name('profil.update');
    });
});

Route::get('/test-email-status', function () {
    $email = request('email', 'bonjour@kimboo.net');
    try {
        \Illuminate\Support\Facades\Mail::raw("Ceci est un test de validation de messagerie en ligne pour Kimboo.", function ($message) use ($email) {
            $message->to($email)->subject("Test Messagerie Web Kimboo");
        });
        return response("<div style='font-family:sans-serif;padding:40px;text-align:center;'>
            <h1 style='color:#16a34a;'>Succès !</h1>
            <p>L'email de test a été envoyé avec succès à <strong>" . e($email) . "</strong> via le driver <code>" . e(config('mail.default')) . "</code>.</p>
        </div>", 200);
    } catch (\Throwable $e) {
        return response("<div style='font-family:sans-serif;padding:40px;text-align:center;'>
            <h1 style='color:#dc2626;'>Erreur d'envoi</h1>
            <p style='color:#666;'>" . e($e->getMessage()) . "</p>
        </div>", 500);
    }
});

Route::get('/professeur/{id}', [HomeController::class, 'profil'])->whereNumber('id')->name('professeur.profil');

require __DIR__.'/auth.php';
