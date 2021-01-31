<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('/webhook')
    ->group(function () {
        Route::get('', [WebhookController::class, 'verify']);

        Route::post('', [WebhookController::class, 'handle']);
    });
