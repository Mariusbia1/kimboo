<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\ProfesseurController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\MessageController;

Route::get('/', [HomeController::class, 'index']);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Espace élève
    Route::middleware(['role:eleve'])->prefix('eleve')->name('eleve.')->group(function () {
        Route::get('/dashboard', [EleveController::class, 'dashboard'])->name('dashboard');
        Route::patch('/reservations/{id}/cancel', [EleveController::class, 'cancelBooking'])->name('booking.cancel');
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
    });

    // Espace admin
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/utilisateurs', [AdminController::class, 'users'])->name('users');
        Route::patch('/professeurs/{id}/certifier', [AdminController::class, 'certifierProfesseur'])->name('certifier');
        Route::patch('/professeurs/{id}/decertifier', [AdminController::class, 'decertifierProfesseur'])->name('decertifier');
        Route::delete('/utilisateurs/{id}', [AdminController::class, 'deleteUser'])->name('delete-user');
    });
});

Route::get('/professeur/{id}', [HomeController::class, 'profil'])->name('professeur.profil');
Route::get('/cours', [CoursController::class, 'index'])->name('cours.index');
Route::post('/professeur/{id}/reserver', [HomeController::class, 'storeBooking'])->name('booking.store');

// Messagerie
Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
Route::get('/messages/{userId}', [MessageController::class, 'show'])->name('messages.show');
Route::post('/messages/{userId}', [MessageController::class, 'send'])->name('messages.send');

require __DIR__.'/auth.php';
