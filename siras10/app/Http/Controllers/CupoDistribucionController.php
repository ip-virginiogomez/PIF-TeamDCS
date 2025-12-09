<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\CupoDemanda;
use App\Models\CupoDistribucion;
use App\Models\CupoOferta;
use App\Models\Periodo;
use Illuminate\Http\Request;

class CupoDistribucionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:cupo-distribuciones.read')->only('index');
        $this->middleware('permission:cupo-distribuciones.create')->only('create', 'store');
        $this->middleware('permission:cupo-distribuciones.update')->only('edit', 'update');
        $this->middleware('permission:cupo-distribuciones.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        // 1. Obtener Periodo Activo (o el más reciente)
        $periodoId = $request->get('periodo_id');
        $periodos = Periodo::orderBy('Año', 'desc')->get();

        if (! $periodoId) {
            $currentYear = date('Y');
            $periodoActual = $periodos->firstWhere('Año', $currentYear) ?? $periodos->first();
            $periodoId = $periodoActual->idPeriodo;
        } else {
            $periodoActual = $periodos->find($periodoId);
        }

        // AJAX: Cargar Demandas
        if ($request->ajax() && $request->get('type') === 'demandas') {
            $search = $request->get('search');

            $demandas = CupoDemanda::where('idPeriodo', $periodoId)
                ->with(['sedeCarrera.sede.centroFormador', 'sedeCarrera.carrera', 'cupoDistribuciones'])
                ->leftJoin('asignatura', function ($join) {
                    $join->on('cupo_demanda.idSedeCarrera', '=', 'asignatura.idSedeCarrera')
                        ->on('cupo_demanda.asignatura', '=', 'asignatura.nombreAsignatura')
                        ->whereNull('asignatura.deleted_at');
                })
                ->leftJoin('tipo_practica', function ($join) {
                    $join->on('asignatura.idTipoPractica', '=', 'tipo_practica.idTipoPractica')
                        ->whereNull('tipo_practica.deleted_at');
                })
                ->select('cupo_demanda.*', 'tipo_practica.nombrePractica as nombreTipoPractica', 'tipo_practica.idTipoPractica')
                ->get()
                ->map(function ($demanda) {
                    $asignado = $demanda->cupoDistribuciones->sum('cantCupos');
                    $demanda->pendiente = $demanda->cuposSolicitados - $asignado;

                    return $demanda;
                })
                ->filter(function ($demanda) {
                    return $demanda->pendiente > 0;
                });

            // Filtrado por búsqueda
            if ($search) {
                $search = strtolower($search);
                $demandas = $demandas->filter(function ($demanda) use ($search) {
                    $text = strtolower(
                        ($demanda->sedeCarrera->sede->centroFormador->nombreCentroFormador ?? '').' '.
                        ($demanda->sedeCarrera->sede->nombreSede ?? '').' '.
                        ($demanda->sedeCarrera->carrera->nombreCarrera ?? '').' '.
                        ($demanda->asignatura ?? '').' '.
                        ($demanda->nombreTipoPractica ?? '')
                    );

                    return str_contains($text, $search);
                });
            }

            // Paginación manual de la colección
            $page = $request->get('page', 1);
            $perPage = 5;
            $demandasPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
                $demandas->forPage($page, $perPage),
                $demandas->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('cupo-distribucion._lista_demandas', ['demandas' => $demandasPaginated]);
        }

        // AJAX: Cargar Ofertas
        if ($request->ajax() && $request->get('type') === 'ofertas') {
            $tipoPracticaId = $request->get('tipo_practica_id');
            $carreraId = $request->get('carrera_id');

            if (! $tipoPracticaId || ! $carreraId) {
                return view('cupo-distribucion._lista_ofertas', ['ofertas' => collect(), 'waitingSelection' => true]);
            }

            $ofertas = CupoOferta::where('idPeriodo', $periodoId)
                ->where('idTipoPractica', $tipoPracticaId)
                ->where('idCarrera', $carreraId)
                ->with(['unidadClinica.centroSalud', 'carrera', 'tipoPractica', 'cupoDistribuciones'])
                ->get()
                ->map(function ($oferta) {
                    $ocupado = $oferta->cupoDistribuciones->sum('cantCupos');
                    $oferta->disponible = $oferta->cantCupos - $ocupado;

                    return $oferta;
                })
                ->filter(function ($oferta) {
                    return $oferta->disponible > 0;
                });

            // Paginación manual de la colección
            $page = $request->get('page', 1);
            $perPage = 5;
            $ofertasPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
                $ofertas->forPage($page, $perPage),
                $ofertas->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('cupo-distribucion._lista_ofertas', ['ofertas' => $ofertasPaginated, 'waitingSelection' => false]);
        }

        // AJAX: Cargar Distribuciones
        if ($request->ajax() && $request->get('type') === 'distribuciones') {
            $search = $request->get('search');

            $query = CupoDistribucion::whereHas('cupoDemanda', function ($q) use ($periodoId) {
                $q->where('idPeriodo', $periodoId);
            })
                ->with([
                    'cupoDemanda.sedeCarrera.sede.centroFormador',
                    'cupoDemanda.sedeCarrera.carrera',
                    'cupoOferta.unidadClinica.centroSalud',
                    'cupoOferta.unidadClinica',
                    'cupoOferta.tipoPractica',
                    'cupoOferta.horarios',
                ]);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('cupoDemanda.sedeCarrera.sede.centroFormador', function ($q) use ($search) {
                        $q->where('nombreCentroFormador', 'like', "%{$search}%");
                    })
                        ->orWhereHas('cupoDemanda.sedeCarrera.carrera', function ($q) use ($search) {
                            $q->where('nombreCarrera', 'like', "%{$search}%");
                        })
                        ->orWhereHas('cupoOferta.unidadClinica.centroSalud', function ($q) use ($search) {
                            $q->where('nombreCentro', 'like', "%{$search}%");
                        })
                        ->orWhereHas('cupoOferta.unidadClinica', function ($q) use ($search) {
                            $q->where('nombreUnidad', 'like', "%{$search}%");
                        })
                        ->orWhereHas('cupoOferta.tipoPractica', function ($q) use ($search) {
                            $q->where('nombrePractica', 'like', "%{$search}%");
                        });
                });
            }

            $distribuciones = $query->orderBy('idCupoDistribucion', 'desc')
                ->paginate(10, ['*'], 'dist_page');

            return view('cupo-distribucion._lista_distribuciones', ['distribuciones' => $distribuciones]);
        }

        // Carga inicial (solo vista base, las listas se cargarán por AJAX o precargadas la primera página de demandas)
        // Para optimizar, cargamos la primera página de demandas directamente
        $demandas = CupoDemanda::where('idPeriodo', $periodoId)
            ->with(['sedeCarrera.sede.centroFormador', 'sedeCarrera.carrera', 'cupoDistribuciones'])
            ->leftJoin('asignatura', function ($join) {
                $join->on('cupo_demanda.idSedeCarrera', '=', 'asignatura.idSedeCarrera')
                    ->on('cupo_demanda.asignatura', '=', 'asignatura.nombreAsignatura')
                    ->whereNull('asignatura.deleted_at');
            })
            ->leftJoin('tipo_practica', function ($join) {
                $join->on('asignatura.idTipoPractica', '=', 'tipo_practica.idTipoPractica')
                    ->whereNull('tipo_practica.deleted_at');
            })
            ->select('cupo_demanda.*', 'tipo_practica.nombrePractica as nombreTipoPractica', 'tipo_practica.idTipoPractica')
            ->get()
            ->map(function ($demanda) {
                $asignado = $demanda->cupoDistribuciones->sum('cantCupos');
                $demanda->pendiente = $demanda->cuposSolicitados - $asignado;

                return $demanda;
            })
            ->filter(function ($demanda) {
                return $demanda->pendiente > 0;
            });

        $page = 1;
        $perPage = 5;
        $demandasPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $demandas->forPage($page, $perPage),
            $demandas->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Ofertas vacías al inicio
        $ofertasPaginated = new \Illuminate\Pagination\LengthAwarePaginator(collect(), 0, 5, 1, ['path' => $request->url()]);

        // Cargar Distribuciones (Asignaciones ya realizadas)
        $distribuciones = CupoDistribucion::whereHas('cupoDemanda', function ($q) use ($periodoId) {
            $q->where('idPeriodo', $periodoId);
        })
            ->with([
            'cupoDemanda.sedeCarrera.sede.centroFormador',
            'cupoDemanda.sedeCarrera.carrera',
            'cupoOferta.unidadClinica.centroSalud',
            'cupoOferta.unidadClinica',
            'cupoOferta.tipoPractica',
            'cupoOferta.horarios',
        ])
            ->orderBy('idCupoDistribucion', 'desc')
            ->paginate(10, ['*'], 'dist_page');

        return view('cupo-distribucion.mesa', [
            'demandas' => $demandasPaginated,
            'ofertas' => $ofertasPaginated,
            'periodoActual' => $periodoActual,
            'periodos' => $periodos,
            'distribuciones' => $distribuciones,
        ]);
    }

    public function store(Request $request)
    {
        $oferta = CupoOferta::findOrFail($request->idCupoOferta);
        $cuposRestantes = $this->_recalcularCupos($oferta->idCupoOferta);

        // Validar si viene idDemandaCupo (Nuevo flujo) o idSedeCarrera (Flujo antiguo)
        if ($request->has('idDemandaCupo')) {
            $request->validate([
                'idCupoOferta' => 'required|exists:cupo_oferta,idCupoOferta',
                'idDemandaCupo' => 'required|exists:cupo_demanda,idDemandaCupo',
                'cantCupos' => [
                    'required', 'integer', 'min:1', 'max:'.$cuposRestantes,
                ],
            ], [
                'cantCupos.max' => 'La cantidad no puede superar los cupos restantes (:max).',
            ]);

            $demanda = CupoDemanda::findOrFail($request->idDemandaCupo);

        } else {
            // Flujo antiguo (por si acaso)
            $request->validate([
                'idCupoOferta' => 'required|exists:cupo_oferta,idCupoOferta',
                'idSedeCarrera' => 'required|exists:sede_carrera,idSedeCarrera',
                'cantCupos' => [
                    'required', 'integer', 'min:1', 'max:'.$cuposRestantes,
                ],
            ], [
                'cantCupos.max' => 'La cantidad no puede superar los cupos restantes (:max).',
            ]);

            // Buscar o Crear la demanda correspondiente para esta Sede/Carrera y Periodo
            $demanda = CupoDemanda::firstOrCreate(
                [
                    'idSedeCarrera' => $request->idSedeCarrera,
                    'idPeriodo' => $oferta->idPeriodo,
                ],
                [
                    'cuposSolicitados' => 0,
                ]
            );
        }

        // Validar unicidad: que no exista ya una distribución para esta oferta y esta demanda
        $existe = CupoDistribucion::where('idCupoOferta', $oferta->idCupoOferta)
            ->where('idDemandaCupo', $demanda->idDemandaCupo)
            ->exists();

        if ($existe) {
            return response()->json(['errors' => ['general' => ['Esta Sede/Carrera ya tiene cupos asignados en esta oferta.']]], 422);
        }

        CupoDistribucion::create([
            'idCupoOferta' => $oferta->idCupoOferta,
            'idDemandaCupo' => $demanda->idDemandaCupo,
            'cantCupos' => $request->cantCupos,
        ]);

        $nuevosRestantes = $this->_recalcularCupos($oferta->idCupoOferta);

        return response()->json(['message' => 'Distribución asignada con éxito.', 'cuposRestantes' => $nuevosRestantes]);
    }

    public function edit($id)
    {
        try {
            $distribucion = CupoDistribucion::with('cupoDemanda')->findOrFail($id);
            // Inyectar idSedeCarrera para que el JS pueda preseleccionar el valor correcto
            $distribucion->idSedeCarrera = $distribucion->cupoDemanda->idSedeCarrera;

            return response()->json($distribucion);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Registro no encontrado.'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        // Cargar la distribución sin el scope global para evitar conflictos
        $distribucion = CupoDistribucion::withoutGlobalScopes()->findOrFail($id);

        // Cargar explícitamente la relación cupoOferta
        $distribucion->load('cupoOferta');
        $oferta = $distribucion->cupoOferta;

        if (! $oferta) {
            return response()->json(['error' => 'No se encontró la oferta de cupos asociada.'], 404);
        }

        $cuposRestantes = $this->_recalcularCupos($oferta->idCupoOferta);
        $cuposDisponiblesParaEditar = $cuposRestantes + $distribucion->cantCupos;

        $request->validate([
            'idSedeCarrera' => 'required|exists:sede_carrera,idSedeCarrera',
            'cantCupos' => [
                'required', 'integer', 'min:1', 'max:'.$cuposDisponiblesParaEditar,
            ],
        ], [
            'cantCupos.max' => 'La cantidad no puede superar los cupos disponibles (:max).',
        ]);

        // Buscar o Crear la demanda correspondiente
        $demanda = CupoDemanda::firstOrCreate(
            [
                'idSedeCarrera' => $request->idSedeCarrera,
                'idPeriodo' => $oferta->idPeriodo,
            ],
            [
                'cuposSolicitados' => 0,
            ]
        );

        // Validar unicidad (excluyendo el registro actual)
        $existe = CupoDistribucion::where('idCupoOferta', $oferta->idCupoOferta)
            ->where('idDemandaCupo', $demanda->idDemandaCupo)
            ->where('idCupoDistribucion', '!=', $distribucion->idCupoDistribucion)
            ->exists();

        if ($existe) {
            return response()->json(['errors' => ['idSedeCarrera' => ['Esta Sede/Carrera ya tiene cupos asignados en esta oferta.']]], 422);
        }

        $distribucion->update([
            'idDemandaCupo' => $demanda->idDemandaCupo,
            'cantCupos' => $request->cantCupos,
        ]);

        $nuevosRestantes = $this->_recalcularCupos($oferta->idCupoOferta);

        return response()->json(['message' => 'Distribución actualizada con éxito.', 'cuposRestantes' => $nuevosRestantes]);
    }

    public function destroy($id)
    {
        try {
            $distribucion = CupoDistribucion::findOrFail($id);
            $idOferta = $distribucion->idCupoOferta;

            $distribucion->delete();

            $cuposRestantes = $this->_recalcularCupos($idOferta);

            return response()->json([
                'message' => 'Registro eliminado con éxito.',
                'cuposRestantes' => $cuposRestantes,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function _recalcularCupos(int $idCupoOferta): int
    {
        $oferta = CupoOferta::findOrFail($idCupoOferta);
        $totalOfertado = $oferta->cantCupos;
        $totalDistribuido = CupoDistribucion::where('idCupoOferta', $idCupoOferta)->sum('cantCupos');

        return $totalOfertado - $totalDistribuido;
    }
}
