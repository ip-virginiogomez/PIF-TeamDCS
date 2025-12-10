<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\CentroFormador;
use App\Models\CupoOferta;
use App\Models\Docente;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    private function getFolderSize($path)
    {
        $totalSize = 0;
        if (! file_exists($path)) {
            return 0;
        }
        if (is_file($path)) {
            return filesize($path);
        }

        try {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                $totalSize += $file->getSize();
            }
        } catch (\Exception $e) {
            return 0;
        }

        return $totalSize;
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision).' '.$units[$pow];
    }

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

        $dashboardData = [];

        // determine dashboard variant based on user role / tipo personal salud
        $user = Auth::user();
        $variant = 'default';

        if ($user) {
            if ($user->hasRole('Admin') || (method_exists($user, 'esAdmin') && $user->esAdmin())) {
                $variant = 'admin';

                $query = Activity::with('causer')->latest();

                if (request()->has('search') && ! empty(request('search'))) {
                    $search = request('search');
                    $query->where(function ($q) use ($search) {
                        // 1. Búsqueda directa en descripción y tipo de entidad
                        $q->where('description', 'like', "%{$search}%")
                            ->orWhere('subject_type', 'like', "%{$search}%");

                        // 2. Búsqueda por Evento (traducción ES -> EN)
                        $lowerSearch = strtolower($search);
                        $eventMap = [
                            'creado' => 'created',
                            'actualizado' => 'updated',
                            'eliminado' => 'deleted',
                        ];

                        // Si busca "creado", busca "created"
                        foreach ($eventMap as $es => $en) {
                            if (str_contains($es, $lowerSearch)) {
                                $q->orWhere('event', $en);
                            }
                        }
                        // También permitir búsqueda directa en inglés
                        $q->orWhere('event', 'like', "%{$search}%");

                        // 3. Búsqueda por Fecha
                        // Intentar convertir formato d/m/Y a Y-m-d
                        try {
                            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}/', $search)) {
                                $date = \Carbon\Carbon::createFromFormat('d/m/Y', $search)->format('Y-m-d');
                                $q->orWhereDate('created_at', $date);
                            } else {
                                $q->orWhere('created_at', 'like', "%{$search}%");
                            }
                        } catch (\Exception $e) {
                            // Si falla el formato, búsqueda simple
                            $q->orWhere('created_at', 'like', "%{$search}%");
                        }

                        // 4. Búsqueda por Usuario (Relación Polimórfica Manual)
                        // Buscamos los IDs de usuarios que coincidan con el nombre/correo
                        $userIds = \App\Models\Usuario::where('nombreUsuario', 'like', "%{$search}%")
                            ->orWhere('apellidoPaterno', 'like', "%{$search}%")
                            ->orWhere('apellidoMaterno', 'like', "%{$search}%")
                            ->orWhere('correo', 'like', "%{$search}%")
                            ->pluck('runUsuario');

                        if ($userIds->isNotEmpty()) {
                            $q->orWhere(function ($sub) use ($userIds) {
                                $sub->where('causer_type', 'App\Models\Usuario')
                                    ->whereIn('causer_id', $userIds);
                            });
                        }
                    });
                }

                $logs = $query->paginate(10, ['*'], 'logs_page');

                if (request()->ajax() && (request()->has('logs_page') || request()->has('search'))) {
                    return view('dashboard.partials.logs_table', ['logs' => $logs])->render();
                }

                $dashboardData['logs'] = $logs;

                // Storage Logic
                $storagePath = storage_path('app/public');

                $vacunasSize = $this->getFolderSize($storagePath.'/vacunas_alumnos') +
                               $this->getFolderSize($storagePath.'/vacunas_docentes');
                $acuerdosSize = $this->getFolderSize($storagePath.'/acuerdos');
                $fotosSize = $this->getFolderSize($storagePath.'/fotos');
                $docentesSize = $this->getFolderSize($storagePath.'/docentes');
                $conveniosSize = $this->getFolderSize($storagePath.'/convenios');
                $mallasSize = $this->getFolderSize($storagePath.'/mallas-curriculares');
                $programasSize = $this->getFolderSize($storagePath.'/programas');
                $pautasSize = $this->getFolderSize($storagePath.'/pautas-evaluacion');

                $dashboardData['storage'] = [
                    'vacunas' => $this->formatBytes($vacunasSize),
                    'acuerdos' => $this->formatBytes($acuerdosSize),
                    'fotos' => $this->formatBytes($fotosSize),
                    'docentes' => $this->formatBytes($docentesSize),
                    'convenios' => $this->formatBytes($conveniosSize),
                    'mallas' => $this->formatBytes($mallasSize),
                    'programas' => $this->formatBytes($programasSize),
                    'pautas' => $this->formatBytes($pautasSize),
                ];

                // User Stats
                $totalUsers = Usuario::count();

                // Active vs Inactive Users (Last 30 days activity)
                $thirtyDaysAgo = now()->subDays(30);
                $activeUserIds = Activity::where('causer_type', 'App\Models\Usuario')
                    ->where('created_at', '>=', $thirtyDaysAgo)
                    ->distinct()
                    ->pluck('causer_id');

                $activeUsersCount = $activeUserIds->count();
                $inactiveUsersCount = max(0, $totalUsers - $activeUsersCount);

                $dashboardData['userStats'] = [
                    'total' => $totalUsers,
                    'by_role' => \Spatie\Permission\Models\Role::withCount('users')->get(),
                    'assigned_cf' => Usuario::has('centrosFormadores')->count(),
                    'assigned_cs' => Usuario::has('centroSalud')->count(),
                    'activity' => [
                        'active' => $activeUsersCount,
                        'inactive' => $inactiveUsersCount,
                        'details' => [
                            'active' => Usuario::whereIn('runUsuario', $activeUserIds)->select('runUsuario', 'nombreUsuario', 'apellidoPaterno', 'apellidoMaterno')->get(),
                            'inactive' => Usuario::whereNotIn('runUsuario', $activeUserIds)->select('runUsuario', 'nombreUsuario', 'apellidoPaterno', 'apellidoMaterno')->get(),
                        ],
                    ],
                ];

            } elseif ($user->hasRole('Coordinador Campo Clínico') || (method_exists($user, 'esCoordinador') && $user->esCoordinador())) {

            } elseif ($user->hasRole('Coordinador Campo Clínico') || (method_exists($user, 'esCoordinador') && $user->esCoordinador())) {
                $variant = 'coordinador_campo';
            } elseif ($user->hasRole('Técnico RAD') || (method_exists($user, 'esTecnicoRAD') && $user->esTecnicoRAD())) {
                $variant = 'rad';

                // Obtener el Centro de Salud del usuario RAD
                $personal = \App\Models\Personal::where('runUsuario', $user->runUsuario)->first();
                $idCentroSalud = $personal ? $personal->idCentroSalud : null;
                $nombreCentro = $personal && $personal->centroSalud ? $personal->centroSalud->nombreCentro : 'Sin Asignación';
                $dashboardData['nombreCentro'] = $nombreCentro;

                $today = now();
                $nextWeek = now()->addDays(7);

                // KPI: Próximos Ingresos
                // Cantidad de alumnos que inician su práctica en los próximos 7 días en este Centro de Salud.
                $proximosIngresos = \App\Models\DossierGrupo::whereHas('grupo.cupoDistribucion.cupoOferta.unidadClinica', function ($q) use ($idCentroSalud) {
                    if ($idCentroSalud) {
                        $q->where('idCentroSalud', $idCentroSalud);
                    }
                })->whereHas('grupo', function ($q) use ($today, $nextWeek) {
                    $q->whereBetween('fechaInicio', [$today->format('Y-m-d'), $nextWeek->format('Y-m-d')]);
                })->count();

                $dashboardData['proximosIngresos'] = $proximosIngresos;

                // KPI: Alumnos en Práctica (Actualmente)
                $alumnosEnPractica = \App\Models\DossierGrupo::whereHas('grupo.cupoDistribucion.cupoOferta.unidadClinica', function ($q) use ($idCentroSalud) {
                    if ($idCentroSalud) {
                        $q->where('idCentroSalud', $idCentroSalud);
                    }
                })->whereHas('grupo', function ($q) use ($today) {
                    $q->where('fechaInicio', '<=', $today->format('Y-m-d'))
                        ->where('fechaFin', '>=', $today->format('Y-m-d'));
                })->count();

                $dashboardData['alumnosEnPractica'] = $alumnosEnPractica;

                // Calendario de Rotaciones (Timeline)
                $startCal = now()->startOfMonth()->subMonth(); // Mostrar desde un mes atrás
                $endCal = now()->addMonths(3)->endOfMonth();   // Hasta 3 meses adelante

                $gruposCalendario = \App\Models\Grupo::with([
                    'cupoDistribucion.cupoOferta.unidadClinica.centroSalud',
                    'cupoDistribucion.sedeCarrera.sede.centroFormador',
                    'cupoDistribucion.sedeCarrera.carrera',
                ])
                    ->whereHas('cupoDistribucion.cupoOferta.unidadClinica', function ($q) use ($idCentroSalud) {
                        if ($idCentroSalud) {
                            $q->where('idCentroSalud', $idCentroSalud);
                        }
                    })
                    ->where(function ($q) use ($startCal, $endCal) {
                        $q->whereBetween('fechaInicio', [$startCal, $endCal])
                            ->orWhereBetween('fechaFin', [$startCal, $endCal])
                            ->orWhere(function ($q2) use ($startCal, $endCal) {
                                $q2->where('fechaInicio', '<', $startCal)
                                    ->where('fechaFin', '>', $endCal);
                            });
                    })
                    ->get();

                $calendarEvents = $gruposCalendario->map(function ($grupo) {
                    $cf = $grupo->cupoDistribucion->sedeCarrera->sede->centroFormador->nombreCentroFormador ?? 'Sin CF';
                    $carrera = $grupo->cupoDistribucion->sedeCarrera->carrera->nombreCarrera ?? 'Sin Carrera';
                    $unidad = $grupo->cupoDistribucion->cupoOferta->unidadClinica->nombreUnidad ?? 'Sin Unidad';
                    $centro = $grupo->cupoDistribucion->cupoOferta->unidadClinica->centroSalud->nombreCentro ?? 'Sin Centro';

                    // Generar color consistente basado en el nombre del Centro Formador
                    $hash = md5($cf);
                    $color = '#'.substr($hash, 0, 6);

                    return [
                        'id' => $grupo->idGrupo,
                        'title' => "$cf - $carrera ($unidad)",
                        'start' => $grupo->fechaInicio->format('Y-m-d'),
                        'end' => $grupo->fechaFin->addDay()->format('Y-m-d'), // FullCalendar usa end exclusivo
                        'extendedProps' => [
                            'unidad' => $unidad,
                            'centro' => $centro,
                            'carrera' => $carrera,
                            'institucion' => $cf,
                        ],
                        'backgroundColor' => $color,
                        'borderColor' => $color,
                        'textColor' => '#ffffff', // Asumimos fondo oscuro, texto blanco
                    ];
                });

                $dashboardData['calendarEvents'] = $calendarEvents;

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

    public function restoreActivity($id)
    {
        $activity = Activity::findOrFail($id);

        try {
            if ($activity->event === 'deleted') {
                $modelClass = $activity->subject_type;
                // Verificar si el modelo usa SoftDeletes
                if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses_recursive($modelClass))) {
                    $record = $modelClass::withTrashed()->find($activity->subject_id);
                    if ($record && $record->trashed()) {
                        $record->restore();

                        return back()->with('success', 'Registro restaurado exitosamente.');
                    }
                }

                return back()->with('error', 'No se pudo restaurar el registro (ya existe o no soporta restauración).');
            } elseif ($activity->event === 'updated') {
                $modelClass = $activity->subject_type;
                $record = $modelClass::find($activity->subject_id);

                if ($record) {
                    $oldAttributes = $activity->properties['old'] ?? [];
                    if (! empty($oldAttributes)) {
                        $record->fill($oldAttributes);
                        $record->save();

                        return back()->with('success', 'Cambios revertidos exitosamente.');
                    }
                }

                return back()->with('error', 'No se pudo revertir los cambios (registro no encontrado o sin datos previos).');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error al restaurar: '.$e->getMessage());
        }

        return back()->with('error', 'Acción no soportada para este tipo de evento.');
    }
}
