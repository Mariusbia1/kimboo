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


Route::get('/', [HomeController::class, 'index']);
Route::get('/qui-sommes-nous', function () {
    return view('qui-sommes-nous');
})->name('qui-sommes-nous');

Route::get('/politique-confidentialite', function () {
    return view('politique-confidentialite');
})->name('politique-confidentialite');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
    // Espace élève
    Route::middleware(['role:eleve'])->prefix('eleve')->name('eleve.')->group(function () {
        Route::get('/dashboard', [EleveController::class, 'dashboard'])->name('dashboard');
        Route::get('/mes-cours', [EleveController::class, 'mesCours'])->name('mes-cours');
        Route::get('/reservations', [EleveController::class, 'mesReservations'])->name('reservations');
        Route::patch('/reservations/{id}/cancel', [EleveController::class, 'cancelBooking'])->name('booking.cancel');

        Route::patch('/reservations/{id}/terminer', [EleveController::class, 'terminerBooking'])->name('booking.terminer');
        Route::post('/reservations/{id}/avis', [EleveController::class, 'storeAvis'])->name('booking.avis');
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
        Route::delete('/cours/{id}', [ProfesseurController::class, 'deleteCours'])->name('delete-cours');
        Route::get('/reservations', [ProfesseurController::class, 'reservations'])->name('reservations');
        Route::patch('/reservations/{id}/confirm', [ProfesseurController::class, 'confirmBooking'])->name('booking.confirm');
        Route::patch('/reservations/{id}/cancel', [ProfesseurController::class, 'cancelBooking'])->name('booking.cancel');
        Route::get('/calendrier', [ProfesseurController::class, 'calendrier'])->name('calendrier');
    });

    // Espace admin
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/utilisateurs', [AdminController::class, 'users'])->name('users');
        Route::get('/statistiques', [StatsController::class, 'index'])->name('stats'); // ← ajouter

        Route::patch('/professeurs/{id}/certifier', [AdminController::class, 'certifierProfesseur'])->name('certifier');
        Route::patch('/professeurs/{id}/decertifier', [AdminController::class, 'decertifierProfesseur'])->name('decertifier');
        Route::delete('/utilisateurs/{id}', [AdminController::class, 'deleteUser'])->name('delete-user');
        Route::get('/cours', [AdminController::class, 'cours'])->name('cours');
        Route::get('/cours/{id}', [AdminController::class, 'showCours'])->name('cours.show');
        Route::patch('/cours/{id}/approuver', [AdminController::class, 'approuverCours'])->name('cours.approuver');
        Route::patch('/cours/{id}/refuser', [AdminController::class, 'refuserCours'])->name('cours.refuser');
        Route::delete('/cours/{id}', [AdminController::class, 'supprimerCours'])->name('cours.supprimer');
        Route::get('/messages/alertes', [AdminController::class, 'messagesAlertes'])->name('messages.alertes');
        Route::get('/messages/conversations', [AdminController::class, 'messagesConversations'])->name('messages.conversations');
        Route::get('/messages/conversations/{userId1}/{userId2}', [AdminController::class, 'voirConversation'])->name('messages.voir');
        Route::patch('/messages/alertes/{id}/reviewed', [AdminController::class, 'alerteReviewed'])->name('messages.alertes.reviewed');
        Route::patch('/messages/alertes/{id}/ignored', [AdminController::class, 'alerteIgnored'])->name('messages.alertes.ignored');
        Route::get('/parametres', [AdminController::class, 'parametres'])->name('parametres');
        Route::post('/parametres', [AdminController::class, 'updateParametres'])->name('parametres.update');
    });
});

Route::get('/professeur/{id}', [HomeController::class, 'profil'])->name('professeur.profil');
Route::get('/cours', [CoursController::class, 'index'])->name('cours.index');
Route::post('/professeur/{id}/reserver', [HomeController::class, 'storeBooking'])->name('booking.store');

// Messagerie
Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
Route::get('/messages/{userId}', [MessageController::class, 'show'])->name('messages.show');
Route::post('/messages/{userId}', [MessageController::class, 'send'])->name('messages.send');

use App\Http\Controllers\FavoriteController;

// Dans le groupe auth
Route::post('/favoris/{profileId}', [FavoriteController::class, 'toggle'])->name('favoris.toggle');
Route::get('/favoris', [FavoriteController::class, 'index'])->name('favoris.index');

require __DIR__.'/auth.php';
