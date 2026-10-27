<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CatalogoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaginaController;
use App\Http\Controllers\ZonaController;
use App\Http\Controllers\CuadernoController;

Route::get('/', [PaginaController::class, 'inicio'])->name('inicio');
Route::get('/mes', [PaginaController::class, 'mes'])->name('mes');

Route::get('/bolets', [CatalogoController::class, 'index'])->name('catalogo.index');
Route::get('/bolets/{especie}', [CatalogoController::class, 'show'])->name('catalogo.show');

Route::get('/tipus/{tipo:clave}', [PaginaController::class, 'tipo'])->name('tipo');

Route::get('/idioma/{idioma}', function (string $idioma) {
    abort_unless(array_key_exists($idioma, config('bolets.idiomas')), 404);
    session(['idioma' => $idioma]);
    return back();
})->name('idioma');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/zona', [ZonaController::class, 'index'])->name('dashboard');
    Route::put('/zona/municipis', [ZonaController::class, 'municipios'])->name('zona.municipios');
    Route::put('/zona/bolets', [ZonaController::class, 'especies'])->name('zona.especies');
    Route::put('/zona/avisos', [ZonaController::class, 'frecuencia'])->name('zona.frecuencia');
    Route::get('/quadern', [CuadernoController::class, 'index'])->name('cuaderno');
    Route::post('/quadern/crear', [CuadernoController::class, 'crear'])->name('cuaderno.crear');
    Route::post('/quadern', [CuadernoController::class, 'guardar'])->name('cuaderno.guardar');
    Route::delete('/quadern/{hallazgo}', [CuadernoController::class, 'borrar'])->name('cuaderno.borrar');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
