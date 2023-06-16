<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/register', [RegistrationController::class, 'register'])
    ->name('register');
Route::middleware('auth:api')->group(function (): void {
    Route::post('/logout', [LogoutController::class, 'logout'])
        ->name('logout');
});
