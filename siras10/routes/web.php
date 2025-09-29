<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\CentroFormadorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TipoCentroFormadorController;
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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- RUTAS PARA LOS MÓDULOS DE GESTIÓN ---
    Route::resource('centros-formadores', CentroFormadorController::class);
    Route::resource('tipos-centro-formador', TipoCentroFormadorController::class);
    Route::resource('alumnos', AlumnoController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('permisos', PermissionController::class);
    Route::get('asignar-permisos', [RoleController::class, 'showPermissionMatrix'])->name('roles.permission_matrix');
    Route::post('asignar-permisos', [RoleController::class, 'syncPermissionsFromMatrix'])->name('roles.sync_permissions');
    Route::resource('carreras', CarreraController::class);
});

require __DIR__.'/auth.php';
