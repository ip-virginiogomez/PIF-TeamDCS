<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CentroFormadorController;
use App\Http\Controllers\TipoCentroFormadorController;
use App\Http\Controllers\AlumnoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Ruta para gestionar Centros Formadores
    Route::resource('centros-formadores', CentroFormadorController::class);
    // Ruta para gestionar Tipos de Centro Formador
    Route::resource('tipos-centro-formador', TipoCentroFormadorController::class);
    // Ruta para gestionar Alumnos
    Route::resource('alumnos', AlumnoController::class);
});

require __DIR__.'/auth.php';