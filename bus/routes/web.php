<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

// Page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');

// Routes d'authentification
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Routes publiques
Route::get('/search', [SearchController::class, 'showForm'])->name('search.form');
Route::post('/search', [SearchController::class, 'search'])->name('search');
Route::get('/reservation/verify/{numero}', [ReservationController::class, 'verify'])->name('reservation.verify');

// Routes protégées (nécessitent une connexion)
Route::middleware(['auth'])->group(function () {
    // Réservation
    Route::get('/reservation/create', [ReservationController::class, 'create'])->name('reservation.create');
    Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');
    
    // Gestion réservation
    Route::get('/reservation/{reservation}', [ReservationController::class, 'confirmation'])->name('reservation.confirmation');
    Route::post('/reservation/{reservation}/payment', [ReservationController::class, 'payment'])->name('reservation.payment');
   Route::get('/reservation/{reservation}/ticket', [ReservationController::class, 'downloadTicket'])
    ->name('reservation.ticket');;
    Route::post('/reservation/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservation.cancel');
    
    // Mes réservations
    Route::get('/mes-reservations', [ReservationController::class, 'index'])
        ->name('reservation.index');
});