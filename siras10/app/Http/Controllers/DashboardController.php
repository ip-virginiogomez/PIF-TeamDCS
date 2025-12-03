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
}
