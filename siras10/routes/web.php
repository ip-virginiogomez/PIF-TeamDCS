<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AsignacionController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\CentroFormadorController;
use App\Http\Controllers\CentroSaludController;
use App\Http\Controllers\ConvenioController;
use App\Http\Controllers\CupoDistribucionController;
use App\Http\Controllers\CupoOfertaController;
use App\Http\Controllers\DocentesController;
use App\Http\Controllers\PeriodoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SedeCarreraController;
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

// Ruta pública
Route::get('/', function () {
    return view('inicio');
})->name('inicio');

Route::middleware(['auth', 'verified'])->group(function () {

    // --- DASHBOARD Y PERFIL ---
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // --- GESTIÓN DE CENTROS FORMADORES ---
    Route::resource('centros-formadores', CentroFormadorController::class);
    Route::resource('tipos-centro-formador', TipoCentroFormadorController::class);
    Route::resource('sede', SedeController::class);

    // --- GESTIÓN DE CONVENIOS ---
    Route::resource('convenios', ConvenioController::class);
    Route::prefix('convenios/{id}/documento')->name('convenios.')->group(function () {
        Route::get('descargar', [ConvenioController::class, 'descargarDocumento'])->name('descargarDocumento');
        Route::get('ver', [ConvenioController::class, 'verDocumento'])->name('verDocumento');
    });

    // --- GESTIÓN DE USUARIOS Y PERMISOS ---
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permisos', PermissionController::class);
    Route::prefix('asignar-permisos')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'showPermissionMatrix'])->name('permission_matrix');
        Route::post('/', [RoleController::class, 'syncPermissionsFromMatrix'])->name('sync_permissions');
    });

    // --- GESTIÓN ACADÉMICA ---
    Route::resource('carreras', CarreraController::class);
    Route::resource('alumnos', AlumnoController::class);
    Route::resource('periodos', PeriodoController::class);

    // --- GESTIÓN DE DOCENTES ---
    Route::prefix('docentes')->name('docentes.')->middleware('can:docentes.read')->group(function () {
        Route::get('/', [DocentesController::class, 'index'])->name('index');
        Route::post('/', [DocentesController::class, 'store'])->name('store')->middleware('can:docentes.create');
        Route::get('{docente}/edit', [DocentesController::class, 'edit'])->name('edit')->middleware('can:docentes.update');
        Route::put('{docente}', [DocentesController::class, 'update'])->name('update')->middleware('can:docentes.update');
        Route::delete('{docente}', [DocentesController::class, 'destroy'])->name('destroy')->middleware('can:docentes.delete');
        Route::get('{docente}/documentos', [DocentesController::class, 'showDocumentos'])->name('documentos');
    });

    // --- GESTIÓN DE CENTROS DE SALUD Y UNIDADES ---
    Route::resource('centro-salud', CentroSaludController::class);
    Route::resource('unidad-clinicas', UnidadClinicaController::class);
    Route::resource('tipos-practica', TipoPracticaController::class);

    // --- GESTIÓN DE CUPOS ---
    Route::resource('cupo-ofertas', CupoOfertaController::class);
    Route::resource('cupo-distribuciones', CupoDistribucionController::class);

    // --- GESTIÓN DE CARRERAS POR SEDE ---
    Route::prefix('gestion-carreras')->name('sede-carrera.')->group(function () {
        Route::get('/', [SedeCarreraController::class, 'index'])->name('index');
        Route::get('sedes/{sede}/tabla-html', [SedeCarreraController::class, 'getTablaAsHtml'])->name('tabla.html');
        Route::post('/', [SedeCarreraController::class, 'store'])->name('store');
        Route::get('{sedeCarrera}/edit', [SedeCarreraController::class, 'edit'])->name('edit');
        Route::put('{sedeCarrera}', [SedeCarreraController::class, 'update'])->name('update');
        Route::delete('{sedeCarrera}', [SedeCarreraController::class, 'destroy'])->name('destroy');
    });

    // --- GESTIÓN DE ASIGNACIONES ---
    Route::resource('asignacion', AsignacionController::class);
});

require __DIR__.'/auth.php';
