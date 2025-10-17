<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\CentroFormadorController;
use App\Http\Controllers\CentroSaludController;
use App\Http\Controllers\CupoOfertaController;
use App\Http\Controllers\DocentesController;
use App\Http\Controllers\PeriodoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\TipoCentroFormadorController;
use App\Http\Controllers\TipoPracticaController;
use App\Http\Controllers\UnidadClinicaController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- RUTAS PARA LOS MÓDULOS DE GESTIÓN ---
    Route::resource('centros-formadores', CentroFormadorController::class);
    Route::resource('tipos-centro-formador', TipoCentroFormadorController::class);
    Route::resource('sede', SedeController::class);
    Route::resource('centro-salud', CentroSaludController::class);
    Route::resource('alumnos', AlumnoController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('permisos', PermissionController::class);
    Route::get('asignar-permisos', [RoleController::class, 'showPermissionMatrix'])->name('roles.permission_matrix');
    Route::post('asignar-permisos', [RoleController::class, 'syncPermissionsFromMatrix'])->name('roles.sync_permissions');
    Route::resource('carreras', CarreraController::class);
    Route::get('/docentes', [DocentesController::class, 'index'])->name('docentes.index')->middleware('can:docentes.read');
    Route::post('/docentes', [DocentesController::class, 'store'])->name('docentes.store')->middleware('can:docentes.create');
    Route::get('/docentes/{docente}/edit', [DocentesController::class, 'edit'])->name('docentes.edit')->middleware('can:docentes.update');
    Route::put('/docentes/{docente}', [DocentesController::class, 'update'])->name('docentes.update')->middleware('can:docentes.update');
    Route::delete('/docentes/{docente}', [DocentesController::class, 'destroy'])->name('docentes.destroy')->middleware('can:docentes.delete');
    Route::resource('periodos', PeriodoController::class);
    Route::resource('cupo-ofertas', CupoOfertaController::class);
    Route::resource('unidad-clinicas', UnidadClinicaController::class);
    Route::resource('tipos-practica', TipoPracticaController::class);
});

require __DIR__.'/auth.php';
