<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AsignacionController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\SedeCarreraController;
use App\Http\Controllers\CentroFormadorController;
use App\Http\Controllers\CentroSaludController;
use App\Http\Controllers\ConvenioController;
use App\Http\Controllers\CupoDistribucionController;
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
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('inicio');
})->name('inicio');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- RUTAS PARA LOS MÓDULOS DE GESTIÓN ---
    Route::resource('centros-formadores', CentroFormadorController::class);
    Route::resource('convenios', ConvenioController::class);
    Route::get('convenios/{id}/documento/descargar', [ConvenioController::class, 'descargarDocumento'])->name('convenios.descargarDocumento');
    Route::get('convenios/{id}/documento/ver', [ConvenioController::class, 'verDocumento'])->name('convenios.verDocumento');
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

    // --- GESTIÓN DE CARRERAS ESPECÍFICAS POR SEDE (ENFOQUE JSON) ---

    // 1. Ruta para la página principal que contiene todo el HTML
    Route::get('gestion-carreras', [SedeCarreraController::class, 'index'])->name('sede-carrera.index');

    // Ruta que devuelve el contenedor de gestión completo (con modal)
    Route::get('sedes/{sede}/gestion-html', [SedeCarreraController::class, 'getGestionAsHtml'])->name('sede-carrera.gestion.html');

    // Ruta que devuelve SOLO la tabla para refrescar
    Route::get('sedes/{sede}/tabla-html', [SedeCarreraController::class, 'getTablaAsHtml'])->name('sede-carrera.list.html');

    // 3. Rutas para el CRUD del modal (siguen siendo las mismas)
    Route::post('sede-carrera', [SedeCarreraController::class, 'store'])->name('sede-carrera.store');
    Route::get('sede-carrera/{sedeCarrera}/edit', [SedeCarreraController::class, 'edit'])->name('sede-carrera.edit');
    Route::put('sede-carrera/{sedeCarrera}', [SedeCarreraController::class, 'update'])->name('sede-carrera.update');
    Route::delete('sede-carrera/{sedeCarrera}', [SedeCarreraController::class, 'destroy'])->name('sede-carrera.destroy');

    Route::get('/docentes', [DocentesController::class, 'index'])->name('docentes.index')->middleware('can:docentes.read');
    Route::post('/docentes', [DocentesController::class, 'store'])->name('docentes.store')->middleware('can:docentes.create');
    Route::get('/docentes/{docente}/edit', [DocentesController::class, 'edit'])->name('docentes.edit')->middleware('can:docentes.update');
    Route::put('/docentes/{docente}', [DocentesController::class, 'update'])->name('docentes.update')->middleware('can:docentes.update');
    Route::delete('/docentes/{docente}', [DocentesController::class, 'destroy'])->name('docentes.destroy')->middleware('can:docentes.delete');
    Route::get('/docentes/{docente}/documentos', [DocentesController::class, 'showDocumentos'])->name('docentes.documentos');
    Route::resource('periodos', PeriodoController::class);
    Route::resource('cupo-ofertas', CupoOfertaController::class);
    Route::resource('unidad-clinicas', UnidadClinicaController::class);
    Route::resource('tipos-practica', TipoPracticaController::class);
    Route::resource('cupo-distribuciones', CupoDistribucionController::class);
    Route::get('/asignacion', [AsignacionController::class, 'index'])
        ->name('asignacion.index');

    Route::get('/coordinadores/{usuario}/centros', [AsignacionController::class, 'getCentrosDeCoordinador'])
        ->name('asignaciones.getCentros');

    Route::post('/coordinadores/{usuario}/centros', [AsignacionController::class, 'asignarCentro'])
        ->name('asignaciones.asignar');

    Route::delete('/coordinadores/{usuario}/centros/{centro}', [AsignacionController::class, 'quitarCentro'])
        ->name('asignaciones.quitar');
});

require __DIR__.'/auth.php';
