<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CatalogoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaginaController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
