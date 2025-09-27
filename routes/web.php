<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Rute untuk user yang sudah login
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('chat');
    })->name('chat');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('api')->group(function () {

        // --- Rute untuk Conversation ---

        // GET /api/conversations -> Ambil semua history chat
        Route::get('/conversations', [ConversationController::class, 'index']);

        // POST /api/conversations -> Buat chat baru
        Route::post('/conversations', [ConversationController::class, 'store']);

        // GET /api/conversations/{id} -> Ambil satu chat spesifik
        Route::get('/conversations/{conversation}', [ConversationController::class, 'show']);

        // DELETE /api/conversations/{id} -> Hapus satu chat spesifik
        Route::delete('/conversations/{conversation}', [ConversationController::class, 'destroy']);

        // DELETE /api/conversations -> Hapus semua history chat
        Route::delete('/conversations', [ConversationController::class, 'clearAll']);

        Route::get('/conversations/{conversation}/messages', [MessageController::class, 'index']);

        Route::post('/messages', [MessageController::class, 'store']);


        // --- Rute untuk Message ---

        // POST /api/messages -> Kirim pesan baru
        Route::post('/messages', [MessageController::class, 'store']);
    });
});
