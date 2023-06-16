<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('login', [LoginController::class, 'login'])->name('login');
