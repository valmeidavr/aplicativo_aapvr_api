<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

/* Teste da Api */
Route::get('/ping', function(){
    return ['pong' => true];
});

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/401', [AuthController::class, 'unauthorized'])->name('login');

/* Rotas para usuários logados */
Route::middleware('auth:api')->group(function(){
    Route::post('auth/me', [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);

    /* Demais rotas do sistemas */
});

