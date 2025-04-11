<?php

use App\Http\Controllers\CardController;
use App\Http\Middleware\ApiKeyMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::prefix('api')->middleware(['api', ApiKeyMiddleware::class])->group(function () {
    Route::get('/register', [CardController::class, 'register']);
    Route::get('/check', [CardController::class, 'check']);
    Route::get('/fetch', [CardController::class, 'fetch']);
    Route::post('/delete', [CardController::class, 'delete']);
});
