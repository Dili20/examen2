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

// partw B
// web.php (esta va AL FINAL, después de todas las demás rutas)
Route::get('/{token}', [ControllerVisitantes::class, 'verLista'])->name('lista.publica');
Route::post('/{token}/item/{item}/toggle', [ControllerVisitantes::class, 'toggleItem'])->name('lista.item.toggle');
