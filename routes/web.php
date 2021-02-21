<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::name('auth.')->prefix('/auth')->group(function () {
    Route::get('callback', [AuthController::class, 'callback'])->name('callback');
});
