<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\dummy\DummyController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix("dummy")->group(function () {
        Route::get('/', [DummyController::class, 'index']);
        Route::get('/show', [DummyController::class, 'show']);
    });

});

