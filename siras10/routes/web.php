<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AsignacionController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\CentroFormadorController;
use App\Http\Controllers\CentroSaludController;
use App\Http\Controllers\ConvenioController;
use App\Http\Controllers\CupoDemandaController;
use App\Http\Controllers\CupoDistribucionController;
use App\Http\Controllers\CupoOfertaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocentesController;
use App\Http\Controllers\DossierGrupoController;
use App\Http\Controllers\GrupoController;
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/notifications', [DashboardController::class, 'notifications'])->name('dashboard.notifications');
    Route::post('/dashboard/activity/{id}/restore', [DashboardController::class, 'restoreActivity'])->name('dashboard.activity.restore');

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
    Route::get('cupo-demandas/asignaturas/{idSedeCarrera}', [CupoDemandaController::class, 'getAsignaturasBySedeCarrera']);
    Route::resource('cupo-demandas', CupoDemandaController::class);
    Route::get('/alumnos/sedes-carreras-by-centro', [AlumnoController::class, 'getSedesCarrerasByCentro'])->name('alumnos.sedes-carreras');
    Route::resource('alumnos', AlumnoController::class);
    Route::get('/alumnos/{run}/documentos', [App\Http\Controllers\AlumnoController::class, 'getDocumentos'])->name('alumnos.documentos');
    Route::resource('periodos', PeriodoController::class);

    // Gestión de Vacunas de Alumnos
    Route::get('/alumnos/{run}/vacunas', [App\Http\Controllers\AlumnoController::class, 'getVacunas'])->name('alumnos.vacunas.index');
    Route::post('/alumnos/{run}/vacunas', [App\Http\Controllers\AlumnoController::class, 'storeVacuna'])->name('alumnos.vacunas.store');
    Route::delete('/vacunas/{id}', [App\Http\Controllers\AlumnoController::class, 'destroyVacuna'])->name('alumnos.vacunas.destroy');
    Route::patch('/vacunas/{id}/estado', [App\Http\Controllers\AlumnoController::class, 'updateVacunaStatus'])->name('alumnos.vacunas.updateStatus');

    // --- GESTIÓN DE DOCENTES ---
    Route::get('/docentes/sedes-carreras-by-centro', [DocentesController::class, 'getSedesCarrerasByCentro'])->name('docentes.sedes-carreras');

    Route::prefix('docentes')->name('docentes.')->middleware('can:docentes.read')->group(function () {
        Route::get('/', [DocentesController::class, 'index'])->name('index');
        Route::post('/', [DocentesController::class, 'store'])->name('store')->middleware('can:docentes.create');

        // Vacunas
        Route::get('/{run}/vacunas', [DocentesController::class, 'getVacunas'])->name('vacunas.index');
        Route::post('/{run}/vacunas', [DocentesController::class, 'storeVacuna'])->name('vacunas.store');

        Route::get('{docente}/edit', [DocentesController::class, 'edit'])->name('edit')->middleware('can:docentes.update');
        Route::put('{docente}', [DocentesController::class, 'update'])->name('update')->middleware('can:docentes.update');
        Route::delete('{docente}', [DocentesController::class, 'destroy'])->name('destroy')->middleware('can:docentes.delete');

        // Documentos
        Route::get('{docente}/documentos', [DocentesController::class, 'getDocumentos'])->name('documentos');
        Route::post('{docente}/upload-document', [DocentesController::class, 'uploadDocument'])->name('uploadDocument')->middleware('can:docentes.update');
    });

    // Rutas extra para vacunas docentes
    Route::delete('/docentes/vacunas/{id}', [DocentesController::class, 'destroyVacuna'])->name('docentes.vacunas.destroy');
    Route::patch('/docentes/vacunas/{id}/status', [DocentesController::class, 'updateVacunaStatus'])->name('docentes.vacunas.updateStatus');

    // --- GESTIÓN DE CENTROS DE SALUD Y UNIDADES ---
    Route::resource('centro-salud', CentroSaludController::class);
    Route::resource('unidad-clinicas', UnidadClinicaController::class);
    Route::resource('tipos-practica', TipoPracticaController::class);

    // --- GESTIÓN DE CUPOS ---
    Route::get('cupo-demandas/asignaturas/{idSedeCarrera}', [CupoDemandaController::class, 'getAsignaturasBySedeCarrera'])->name('cupo-demandas.asignaturas');
    Route::resource('cupo-demandas', CupoDemandaController::class);
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

        // Rutas de Malla Curricular
        Route::post('malla', [SedeCarreraController::class, 'storeMalla'])->name('malla.store');
        Route::get('años-disponibles', [SedeCarreraController::class, 'getAniosDisponibles'])->name('anios');
        Route::get('sedes/{sedeId}/mallas', [SedeCarreraController::class, 'getMallasPorSede'])->name('mallas.por-sede');
        Route::get('malla/{idMallaSedeCarrera}/ver', [SedeCarreraController::class, 'verMallaPdf'])->name('malla.ver');
        Route::get('malla/{idMallaSedeCarrera}/descargar', [SedeCarreraController::class, 'descargarMallaPdf'])->name('malla.descargar');
        Route::put('malla/{mallaSedeCarrera}', [SedeCarreraController::class, 'updateMalla'])->name('malla.update');
        Route::delete('malla/{mallaSedeCarrera}', [SedeCarreraController::class, 'destroyMalla'])->name('malla.destroy');

        // Rutas de Asignatura (CRUD)
        Route::post('asignaturas', [SedeCarreraController::class, 'storeAsignatura'])->name('asignaturas.store');
        Route::get('asignaturas/{asignatura}/edit', [SedeCarreraController::class, 'editAsignatura'])->name('asignaturas.edit');
        Route::put('asignaturas/{asignatura}', [SedeCarreraController::class, 'updateAsignatura'])->name('asignaturas.update');
        Route::delete('asignaturas/{asignatura}', [SedeCarreraController::class, 'destroyAsignatura'])->name('asignaturas.destroy');
        Route::get('sede-carreras/{sedeCarrera}/asignaturas', [SedeCarreraController::class, 'getAsignaturasPorSedeCarrera'])->name('asignaturas.por-sede-carrera');

        // Rutas de Programa de Asignatura
        Route::get('{sedeCarrera}/archivos', [SedeCarreraController::class, 'archivos'])->name('archivos');
        Route::post('asignaturas/{asignatura}/programa', [SedeCarreraController::class, 'storePrograma'])->name('asignaturas.programa.store');
        Route::get('asignaturas/{asignatura}/programa/descargar', [SedeCarreraController::class, 'descargarPrograma'])->name('asignaturas.programa.download');
        Route::get('asignaturas/{asignatura}/programa/ver', [SedeCarreraController::class, 'verPrograma'])->name('asignaturas.programa.ver');
        // Mostrar todos los programas de una asignatura (AJAX o modal)
        Route::get('asignaturas/{asignatura}/programas', [SedeCarreraController::class, 'showProgramas'])->name('asignaturas.programas.list');
        // Descargar un programa específico por id
        Route::get('programas/{programa}/descargar', [SedeCarreraController::class, 'descargarProgramaEspecifico'])->name('programas.download');
        // Eliminar un programa específico
        Route::delete('programas/{programa}', [SedeCarreraController::class, 'destroyPrograma'])->name('programas.destroy');

        // Rutas de Pauta de Evaluación
        Route::post('asignaturas/{asignatura}/pauta', [SedeCarreraController::class, 'uploadPautaEvaluacion'])->name('asignaturas.pauta.upload');
        Route::get('asignaturas/{asignatura}/pauta/descargar', [SedeCarreraController::class, 'descargarPautaEvaluacion'])->name('asignaturas.pauta.download');
        Route::delete('asignaturas/{asignatura}/pauta', [SedeCarreraController::class, 'destroyPautaEvaluacion'])->name('asignaturas.pauta.destroy');
    });

    // --- GESTIÓN DE ASIGNACIONES ---
    Route::resource('asignacion', AsignacionController::class);
    // 1. La página principal
    Route::get('/asignaciones', [AsignacionController::class, 'index'])
        ->name('asignaciones.index');

    // --- RUTAS AJAX PARA CAMPO CLÍNICO (LAS QUE FALTABAN) ---
    Route::get('/asignaciones/campo-clinico/{usuario}/centros', [AsignacionController::class, 'getCentrosCampoClinico'])
        ->name('asignaciones.getCentrosCC');
    Route::post('/asignaciones/campo-clinico/{usuario}/centros', [AsignacionController::class, 'asignarCentroCampoClinico'])
        ->name('asignaciones.asignarCC');
    Route::delete('/asignaciones/campo-clinico/{usuario}/centros/{centro}', [AsignacionController::class, 'quitarCentroCampoClinico'])
        ->name('asignaciones.quitarCC');

    // --- RUTAS AJAX PARA RAD ---
    Route::get('/asignaciones/rad/{usuario}/centros', [AsignacionController::class, 'getCentrosRad'])
        ->name('asignaciones.getCentrosRAD');
    Route::post('/asignaciones/rad/{usuario}/centros', [AsignacionController::class, 'asignarCentroRad'])
        ->name('asignaciones.asignarRAD');
    Route::delete('/asignaciones/rad/{usuario}/centros/{centro}', [AsignacionController::class, 'quitarCentroRad'])
        ->name('asignaciones.quitarRAD');

    // --- GESTIÓN DE GRUPOS ---
    Route::resource('grupos', GrupoController::class);
    Route::post('/grupos/{grupo}/validar-dossier', [GrupoController::class, 'validarDossier'])->name('grupos.validarDossier');
    Route::post('/grupos/{grupo}/revertir-dossier', [GrupoController::class, 'revertirDossier'])->name('grupos.revertirDossier');
    Route::post('/grupos/{grupo}/rechazar-dossier', [GrupoController::class, 'rechazarDossier'])->name('grupos.rechazarDossier');
    Route::get('/grupos/por-distribucion/{idDistribucion}', [GrupoController::class, 'getGruposByDistribucion'])
        ->name('grupos.by-distribucion');

    // --- GESTIÓN DE DOSSIER DE GRUPO ---
    Route::prefix('dossier/{grupo}')->name('dossier.')->group(function () {

        Route::get('/', [DossierGrupoController::class, 'index'])->name('index');
        Route::get('/buscar-alumnos', [DossierGrupoController::class, 'buscarAlumnos'])->name('buscarAlumnos');
        Route::post('/agregar-alumno', [DossierGrupoController::class, 'agregarAlumno'])->name('agregarAlumno');
        Route::delete('/eliminar-alumno/{alumno}', [DossierGrupoController::class, 'eliminarAlumno'])->name('eliminarAlumno');
    });

    // otros recursos y/o rutas...
    Route::get('/api/sedes-carreras', [App\Http\Controllers\DocentesController::class, 'getSedesCarrerasByCentro'])->name('api.sedes-carreras');
});

require __DIR__.'/auth.php';
