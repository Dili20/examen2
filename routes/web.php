<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\ListaController;

Route::get('/', fn() => auth()->check() ? redirect()->route('listas.index') : view('login'))->name('login');
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);
Route::post('/logout', [GoogleController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::resource('listas', ListaController::class)->except('show');
});

use App\Http\Controllers\ControllerVisitantes;

Route::get('/lista/{token}', [ControllerVisitantes::class, 'verLista'])->name('lista.publica');
Route::post('/lista/{token}/item/{item}/toggle', [ControllerVisitantes::class, 'toggleItem'])->name('lista.item.toggle');
// partw B