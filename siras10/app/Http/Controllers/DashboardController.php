<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\CentroFormador;
use App\Models\CupoOferta;
use App\Models\Docente;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAlumnos = Alumno::count();
        $totalDocentes = Docente::count();
        $totalCupos = CupoOferta::count();
        $totalUsuarios = Usuario::count();
        $totalCentrosFormadores = CentroFormador::count();

        // Datos para gráfico: Cupos ofertados por carrera
        $cuposPorCarrera = \App\Models\Carrera::withCount(['cupoOfertas as total_cupos' => function ($query) {
            $query->select(\DB::raw('COALESCE(SUM(cantCupos),0)'));
        }])->get();

        $cuposPorCarreraLabels = $cuposPorCarrera->pluck('nombreCarrera');
        $cuposPorCarreraData = $cuposPorCarrera->pluck('total_cupos');

        // determine dashboard variant based on user role / tipo personal salud
        $user = Auth::user();
        $variant = 'default';

        if ($user) {
            if ($user->hasRole('Admin') || (method_exists($user, 'esAdmin') && $user->esAdmin())) {
                $variant = 'admin';
            } elseif ($user->hasRole('Coordinador Campo Clínico') || (method_exists($user, 'esCoordinador') && $user->esCoordinador())) {
                $variant = 'coordinador_campo';
            } elseif ($user->hasRole('Técnico RAD') || (method_exists($user, 'esTecnicoRAD') && $user->esTecnicoRAD())) {
                $variant = 'rad';
            } elseif ($user->hasRole('Docente')) {
                $variant = 'docente';
            } else {
                // fallback: check tipoPersonalSalud relation if available
                try {
                    $tipo = $user->tipoPersonalSalud ?? null;
                    if ($tipo && strpos(strtolower($tipo->cargo ?? ''), 'coordinador') !== false) {
                        $variant = 'coordinador_campo';
                    }
                } catch (\Throwable $e) {
                    // ignore and keep default
                }
            }
        }

        $dashboardData = [];

        if ($variant === 'coordinador_campo') {
            $coordinador = \App\Models\CoordinadorCampoClinico::where('runUsuario', $user->runUsuario)->first();
            if ($coordinador) {
                $idCentroFormador = $coordinador->idCentroFormador;
                $today = now();

                // 1. Periodo y Semana
                $periodo = \App\Models\Periodo::where('fechaInicio', '<=', $today)
                    ->where('fechaFin', '>=', $today)
                    ->first();
                $semanaRotacion = 0;
                if ($periodo) {
                    $start = \Carbon\Carbon::parse($periodo->fechaInicio);
                    $semanaRotacion = $start->diffInWeeks($today) + 1;
                }
                $dashboardData['periodoActual'] = $periodo;
                $dashboardData['semanaRotacion'] = (int) $semanaRotacion;

                // 2. Alumnos Totales del Centro Formador
                $dashboardData['alumnosTotalesCF'] = Alumno::whereHas('alumnoCarreras.sedeCarrera.sede', function ($q) use ($idCentroFormador) {
                    $q->where('idCentroFormador', $idCentroFormador);
                })->count();

                // 3. Alumnos en Práctica (Activos)
                $dashboardData['alumnosEnPractica'] = Alumno::whereHas('dossierGrupos.grupo.cupoDistribucion.cupoOferta', function ($q) use ($today, $periodo) {
                    $q->where('fechaEntrada', '<=', $today)
                        ->where('fechaSalida', '>=', $today);
                    if ($periodo) {
                        $q->where('idPeriodo', $periodo->idPeriodo);
                    }
                })->whereHas('alumnoCarreras.sedeCarrera.sede', function ($q) use ($idCentroFormador) {
                    $q->where('idCentroFormador', $idCentroFormador);
                })->count();

                // 4. Alumnos Finalizados
                $dashboardData['alumnosFinalizados'] = Alumno::whereHas('dossierGrupos.grupo.cupoDistribucion.cupoOferta', function ($q) use ($today, $periodo) {
                    $q->where('fechaSalida', '<', $today);
                    if ($periodo) {
                        $q->where('idPeriodo', $periodo->idPeriodo);
                    }
                })->whereHas('alumnoCarreras.sedeCarrera.sede', function ($q) use ($idCentroFormador) {
                    $q->where('idCentroFormador', $idCentroFormador);
                })->count();

                // Notificaciones
                $dashboardData['notificaciones'] = $user->unreadNotifications()->paginate(3);
                $dashboardData['notificaciones']->withPath(route('dashboard.notifications'));

                // 5. Alumnos Pendientes
                // Calculado como: Total CF - (En Practica + Finalizados)
                // Nota: Esto asume que un alumno no puede estar en ambas listas (activo y finalizado) en el mismo periodo,
                // o que si lo está, cuenta como "ya cubierto".
                // Para ser más precisos, contamos los alumnos únicos que tienen alguna actividad en el periodo.
                $alumnosConActividad = Alumno::whereHas('dossierGrupos.grupo.cupoDistribucion.cupoOferta', function ($q) use ($today, $periodo) {
                    if ($periodo) {
                        $q->where('idPeriodo', $periodo->idPeriodo);
                    }
                    $q->where(function ($q2) use ($today) {
                        $q2->where('fechaSalida', '<', $today) // Finalizado
                            ->orWhere(function ($q3) use ($today) { // Activo
                                $q3->where('fechaEntrada', '<=', $today)
                                    ->where('fechaSalida', '>=', $today);
                            });
                    });
                })->whereHas('alumnoCarreras.sedeCarrera.sede', function ($q) use ($idCentroFormador) {
                    $q->where('idCentroFormador', $idCentroFormador);
                })->count();

                $dashboardData['alumnosPendientes'] = $dashboardData['alumnosTotalesCF'] - $alumnosConActividad;
                if ($dashboardData['alumnosPendientes'] < 0) {
                    $dashboardData['alumnosPendientes'] = 0;
                }

                // 6. Estado de Inmunización Global (Por Alumno)
                // Definir estados
                $estadosVigentes = ['Activo', 'Vigente'];
                $estadosVencidos = ['Expirado', 'Vencida', 'Vencido'];
                // Base query para alumnos del CF
                $baseAlumnoQuery = Alumno::whereHas('alumnoCarreras.sedeCarrera.sede', function ($q) use ($idCentroFormador) {
                    $q->where('idCentroFormador', $idCentroFormador);
                });

                // 1. Vigentes: Alumnos con al menos una vacuna vigente
                $vigentes = (clone $baseAlumnoQuery)->whereHas('vacunas.estadoVacuna', function ($q) use ($estadosVigentes) {
                    $q->whereIn('nombreEstado', $estadosVigentes);
                })->count();

                // 2. Vencidas: Alumnos SIN vacunas vigentes Y con al menos una vencida
                $vencidas = (clone $baseAlumnoQuery)->whereDoesntHave('vacunas.estadoVacuna', function ($q) use ($estadosVigentes) {
                    $q->whereIn('nombreEstado', $estadosVigentes);
                })->whereHas('vacunas.estadoVacuna', function ($q) use ($estadosVencidos) {
                    $q->whereIn('nombreEstado', $estadosVencidos);
                })->count();

                // 4. Sin Vacunas: Alumnos sin ningún registro de vacunas
                $sinVacunas = (clone $baseAlumnoQuery)->doesntHave('vacunas')->count();

                $dashboardData['inmunizacionData'] = [
                    'vigentes' => $vigentes,
                    'vencidas' => $vencidas,
                    'sin_vacunas' => $sinVacunas,
                    'total' => $vigentes + $vencidas + $sinVacunas,
                ];

                // 7. Estado de Inmunización Docentes (Misma lógica)
                $baseDocenteQuery = \App\Models\Docente::whereHas('docenteCarreras.sedeCarrera.sede', function ($q) use ($idCentroFormador) {
                    $q->where('idCentroFormador', $idCentroFormador);
                });

                // 1. Vigentes
                $docentesVigentes = (clone $baseDocenteQuery)->whereHas('docenteVacunas.estadoVacuna', function ($q) use ($estadosVigentes) {
                    $q->whereIn('nombreEstado', $estadosVigentes);
                })->count();

                // 2. Vencidas
                $docentesVencidas = (clone $baseDocenteQuery)->whereDoesntHave('docenteVacunas.estadoVacuna', function ($q) use ($estadosVigentes) {
                    $q->whereIn('nombreEstado', $estadosVigentes);
                })->whereHas('docenteVacunas.estadoVacuna', function ($q) use ($estadosVencidos) {
                    $q->whereIn('nombreEstado', $estadosVencidos);
                })->count();

                // 3. Sin Vacunas
                $docentesSinVacunas = (clone $baseDocenteQuery)->doesntHave('docenteVacunas')->count();

                $dashboardData['docenteInmunizacionData'] = [
                    'vigentes' => $docentesVigentes,
                    'vencidas' => $docentesVencidas,
                    'sin_vacunas' => $docentesSinVacunas,
                    'total' => $docentesVigentes + $docentesVencidas + $docentesSinVacunas,
                ];

                // 8. Rotaciones Activas Hoy (Grupos)
                $gruposActivos = \App\Models\Grupo::where('fechaInicio', '<=', $today)
                    ->where('fechaFin', '>=', $today)
                    ->whereHas('cupoDistribucion.sedeCarrera.sede', function ($q) use ($idCentroFormador) {
                        $q->where('idCentroFormador', $idCentroFormador);
                    })
                    ->with(['cupoDistribucion.cupoOferta.unidadClinica.centroSalud'])
                    ->get();

                $totalGruposActivos = $gruposActivos->count();

                $desglosePorCentroSalud = $gruposActivos->groupBy(function ($grupo) {
                    return $grupo->cupoDistribucion->cupoOferta->unidadClinica->centroSalud->nombreCentro ?? 'Desconocido';
                })->map(function ($grupos) {
                    return $grupos->count();
                })->toArray();

                $dashboardData['rotacionesActivas'] = [
                    'total' => $totalGruposActivos,
                    'desglose' => $desglosePorCentroSalud,
                ];

                // 7. Ocupación de Centros de Salud (Actualmente)
                $ocupacion = \App\Models\CupoDistribucion::query()
                    ->join('cupo_oferta', 'cupo_distribucion.idCupoOferta', '=', 'cupo_oferta.idCupoOferta')
                    ->join('unidad_clinica', 'cupo_oferta.idUnidadClinica', '=', 'unidad_clinica.idUnidadClinica')
                    ->join('centro_salud', 'unidad_clinica.idCentroSalud', '=', 'centro_salud.idCentroSalud')
                    ->join('sede_carrera', 'cupo_distribucion.idSedeCarrera', '=', 'sede_carrera.idSedeCarrera')
                    ->join('sede', 'sede_carrera.idSede', '=', 'sede.idSede')
                    ->where('sede.idCentroFormador', $idCentroFormador)
                    ->whereDate('cupo_oferta.fechaEntrada', '<=', $today)
                    ->whereDate('cupo_oferta.fechaSalida', '>=', $today)
                    ->selectRaw('centro_salud.nombreCentro, SUM(cupo_distribucion.cantCupos) as total_cupos')
                    ->groupBy('centro_salud.nombreCentro')
                    ->get();

                $asignados = \App\Models\DossierGrupo::query()
                    ->join('grupo', 'dossier_grupo.idGrupo', '=', 'grupo.idGrupo')
                    ->join('cupo_distribucion', 'grupo.idCupoDistribucion', '=', 'cupo_distribucion.idCupoDistribucion')
                    ->join('sede_carrera', 'cupo_distribucion.idSedeCarrera', '=', 'sede_carrera.idSedeCarrera')
                    ->join('sede', 'sede_carrera.idSede', '=', 'sede.idSede')
                    ->join('cupo_oferta', 'cupo_distribucion.idCupoOferta', '=', 'cupo_oferta.idCupoOferta')
                    ->join('unidad_clinica', 'cupo_oferta.idUnidadClinica', '=', 'unidad_clinica.idUnidadClinica')
                    ->join('centro_salud', 'unidad_clinica.idCentroSalud', '=', 'centro_salud.idCentroSalud')
                    ->where('sede.idCentroFormador', $idCentroFormador)
                    ->whereDate('grupo.fechaInicio', '<=', $today)
                    ->whereDate('grupo.fechaFin', '>=', $today)
                    ->selectRaw('centro_salud.nombreCentro, count(*) as total_asignados')
                    ->groupBy('centro_salud.nombreCentro')
                    ->pluck('total_asignados', 'nombreCentro');

                $ocupacionLabels = [];
                $ocupacionTotal = [];
                $ocupacionAsignada = [];

                foreach ($ocupacion as $row) {
                    $ocupacionLabels[] = $row->nombreCentro;
                    $ocupacionTotal[] = $row->total_cupos;
                    $ocupacionAsignada[] = $asignados[$row->nombreCentro] ?? 0;
                }

                $dashboardData['ocupacionLabels'] = $ocupacionLabels;
                $dashboardData['ocupacionTotal'] = $ocupacionTotal;
                $dashboardData['ocupacionAsignada'] = $ocupacionAsignada;

                // 8. Vacunas por Vencer (Próximos 30 días) - Alumnos
                $vacunasAlumnosPorVencer = \App\Models\VacunaAlumno::select('alumno_vacuna.*')
                    ->join('tipo_vacuna', 'alumno_vacuna.idTipoVacuna', '=', 'tipo_vacuna.idTipoVacuna')
                    ->join('estado_vacuna', 'alumno_vacuna.idEstadoVacuna', '=', 'estado_vacuna.idEstadoVacuna')
                    ->with(['alumno', 'tipoVacuna'])
                    ->whereHas('alumno.alumnoCarreras.sedeCarrera.sede', function ($q) use ($idCentroFormador) {
                        $q->where('idCentroFormador', $idCentroFormador);
                    })
                    ->where('estado_vacuna.nombreEstado', 'Activo')
                    ->whereRaw('DATE_ADD(alumno_vacuna.fechaSubida, INTERVAL tipo_vacuna.duracion DAY) BETWEEN ? AND ?', [
                        $today->format('Y-m-d'),
                        $today->copy()->addDays(30)->format('Y-m-d'),
                    ])
                    ->get()
                    ->map(function ($vacuna) {
                        $fechaSubida = \Carbon\Carbon::parse($vacuna->fechaSubida);
                        $vacuna->fechaVencimiento = $fechaSubida->addDays($vacuna->tipoVacuna->duracion);
                        $vacuna->tipo_persona = 'Alumno';
                        $vacuna->nombre_completo = $vacuna->alumno->nombres.' '.$vacuna->alumno->apellidoPaterno;

                        return $vacuna;
                    });

                // 9. Vacunas por Vencer (Próximos 30 días) - Docentes
                $vacunasDocentesPorVencer = \App\Models\DocenteVacuna::select('docente_vacuna.*')
                    ->join('tipo_vacuna', 'docente_vacuna.idTipoVacuna', '=', 'tipo_vacuna.idTipoVacuna')
                    ->join('estado_vacuna', 'docente_vacuna.idEstadoVacuna', '=', 'estado_vacuna.idEstadoVacuna')
                    ->with(['docente', 'tipoVacuna'])
                    ->whereHas('docente.docenteCarreras.sedeCarrera.sede', function ($q) use ($idCentroFormador) {
                        $q->where('idCentroFormador', $idCentroFormador);
                    })
                    ->where('estado_vacuna.nombreEstado', 'Activo')
                    ->whereRaw('DATE_ADD(docente_vacuna.fechaSubida, INTERVAL tipo_vacuna.duracion DAY) BETWEEN ? AND ?', [
                        $today->format('Y-m-d'),
                        $today->copy()->addDays(30)->format('Y-m-d'),
                    ])
                    ->get()
                    ->map(function ($vacuna) {
                        $fechaSubida = \Carbon\Carbon::parse($vacuna->fechaSubida);
                        $vacuna->fechaVencimiento = $fechaSubida->addDays($vacuna->tipoVacuna->duracion);
                        $vacuna->tipo_persona = 'Docente';
                        $vacuna->nombre_completo = $vacuna->docente->nombresDocente.' '.$vacuna->docente->apellidoPaterno;

                        return $vacuna;
                    });

                // Fusionar y ordenar por fecha de vencimiento
                $dashboardData['vacunasPorVencer'] = $vacunasAlumnosPorVencer
                    ->concat($vacunasDocentesPorVencer)
                    ->sortBy('fechaVencimiento');
            }
        }

        return view('dashboard', array_merge([
            'totalAlumnos' => $totalAlumnos,
            'totalDocentes' => $totalDocentes,
            'totalCupos' => $totalCupos,
            'totalUsuarios' => $totalUsuarios,
            'totalCentrosFormadores' => $totalCentrosFormadores,
            'cuposPorCarreraLabels' => $cuposPorCarreraLabels,
            'cuposPorCarreraData' => $cuposPorCarreraData,
            'dashboardVariant' => $variant,
        ], $dashboardData));
    }

    public function notifications()
    {
        $user = Auth::user();
        $notificaciones = $user->unreadNotifications()->paginate(3);
        return view('dashboard.partials.notifications', compact('notificaciones'));
    }
}
