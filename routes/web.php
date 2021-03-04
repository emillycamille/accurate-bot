<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home');

Route::name('auth.')->prefix('/auth')->group(function () {
    Route::get('callback', [AuthController::class, 'callback'])->name('callback');
});
