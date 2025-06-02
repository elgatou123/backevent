<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AuthController;

Route::get('/test-api', function () {
    return response()->json(['message' => 'API is working!']);
});

// 🔓 Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::apiResource('events', EventController::class)->only(['index', 'show']);

// 🔐 Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'getAuthenticatedUser']);

    // Users
    Route::apiResource('users', UtilisateurController::class)->except(['store']);
    
    // Events
    Route::apiResource('events', EventController::class)->except(['index', 'show']);
    
    // Services
    Route::apiResource('services', ServiceController::class);
    
    // Reservations
Route::apiResource('reservations', ReservationController::class)->except(['show']);    
    // Custom reservation routes
Route::get('/reservations/my', [ReservationController::class, 'myReservations']);
    Route::get('/events/{event}/reservations', [ReservationController::class, 'eventReservations']);
    
    // Invites
    Route::prefix('invites')->group(function () {
        Route::get('/{invite}', [InviteController::class, 'show']);
        Route::put('/{invite}/status', [InviteController::class, 'updateStatus']);
    });
});