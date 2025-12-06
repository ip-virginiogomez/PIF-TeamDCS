<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\CupoDistribucion;
use App\Models\DocenteCarrera;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GrupoController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:grupos.read')->only('index');
        $this->middleware('can:grupos.create')->only(['create', 'store']);
        $this->middleware('can:grupos.update')->only(['edit', 'update']);
        $this->middleware('can:grupos.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $periodo = $request->input('periodo');
        $sortBy = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');

        $periodosDisponibles = \App\Models\CupoOferta::selectRaw('YEAR(fechaEntrada) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $query = CupoDistribucion::select('cupo_distribucion.*')
            ->join('sede_carrera', 'cupo_distribucion.idSedeCarrera', '=', 'sede_carrera.idSedeCarrera')
            ->join('sede', 'sede_carrera.idSede', '=', 'sede.idSede')
            ->join('centro_formador', 'sede.idCentroFormador', '=', 'centro_formador.idCentroFormador')
            ->join('cupo_oferta', 'cupo_distribucion.idCupoOferta', '=', 'cupo_oferta.idCupoOferta')
            ->join('tipo_practica', 'cupo_oferta.idTipoPractica', '=', 'tipo_practica.idTipoPractica')
            ->join('unidad_clinica', 'cupo_oferta.idUnidadClinica', '=', 'unidad_clinica.idUnidadClinica')
            ->join('centro_salud', 'unidad_clinica.idCentroSalud', '=', 'centro_salud.idCentroSalud')
            ->with([
                'sedeCarrera.sede.centroFormador',
                'cupoOferta.unidadClinica.centroSalud',
            ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                // A. Buscar por Centro Formador
                $q->where('centro_formador.nombreCentroFormador', 'like', "%{$search}%")
                // B. Buscar por Sede / Carrera
                    ->orWhere('sede_carrera.nombreSedeCarrera', 'like', "%{$search}%")
                // C. Buscar por Centro de Salud
                    ->orWhere('centro_salud.nombreCentro', 'like', "%{$search}%")
                // D. Buscar por Unidad Clínica
                    ->orWhere('unidad_clinica.nombreUnidad', 'like', "%{$search}%");
            });
        }

        if ($periodo) {
            $query->whereYear('cupo_oferta.fechaEntrada', $periodo);
        }

        // Ordenamiento
        if ($sortBy) {
            switch ($sortBy) {
                case 'centro_formador':
                    $query->orderBy('centro_formador.nombreCentroFormador', $sortDirection);
                    break;
                case 'sede_carrera':
                    $query->orderBy('sede_carrera.nombreSedeCarrera', $sortDirection);
                    break;
                case 'centro_salud':
                    $query->orderBy('centro_salud.nombreCentro', $sortDirection);
                    break;
                case 'unidad_clinica':
                    $query->orderBy('unidad_clinica.nombreUnidad', $sortDirection);
                    break;
                case 'tipo_practica':
                    $query->orderBy('tipo_practica.nombrePractica', $sortDirection);
                    break;
                default:
                    $query->orderBy('cupo_distribucion.idCupoDistribucion', 'desc');
                    break;
            }
        } else {
            $query->orderBy('cupo_distribucion.idCupoDistribucion', 'desc');
        }

        $distribuciones = $query->paginate(5);

        $distribuciones->appends([
            'search' => $search,
            'periodo' => $periodo,
            'sort_by' => $sortBy,
            'sort_direction' => $sortDirection,
        ]);

        $listaDocentesCarrera = DocenteCarrera::with(['docente', 'sedeCarrera'])->get();

        $listaAsignaturas = Asignatura::orderBy('nombreAsignatura')->get();

        if ($request->ajax() && ! $request->has('get_grupos')) {
            return view('grupos._tabla_distribuciones', compact('distribuciones', 'sortBy', 'sortDirection'))->render();
        }

        return view('grupos.index', compact('distribuciones', 'listaDocentesCarrera', 'listaAsignaturas', 'periodosDisponibles', 'sortBy', 'sortDirection'));
    }

    public function store(Request $request)
    {
        // 1. VALIDACIÓN
        $validator = Validator::make($request->all(), [
            'nombreGrupo' => 'required|string|max:45',
            'idAsignatura' => 'required|exists:asignatura,idAsignatura',
            'idDocenteCarrera' => 'required|exists:docente_carrera,idDocenteCarrera',
            'idCupoDistribucion' => 'required|exists:cupo_distribucion,idCupoDistribucion',
            'fechaInicio' => 'nullable|date',
            'fechaFin' => 'nullable|date|after_or_equal:fechaInicio',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            // 4. CREAR EN BD
            $grupo = Grupo::create($request->all());

            return response()->json(['success' => true, 'message' => 'Grupo creado exitosamente.', 'data' => $grupo]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al guardar: '.$e->getMessage()], 500);
        }
    }

    public function edit(Grupo $grupo)
    {
        return response()->json($grupo);
    }

    public function update(Request $request, Grupo $grupo)
    {
        // 1. VALIDACIÓN
        $validator = Validator::make($request->all(), [
            'nombreGrupo' => 'required|string|max:45',
            'idAsignatura' => 'required|exists:asignatura,idAsignatura',
            'idDocenteCarrera' => 'required|exists:docente_carrera,idDocenteCarrera',
            'fechaInicio' => 'nullable|date',
            'fechaFin' => 'nullable|date|after_or_equal:fechaInicio',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            // 4. ACTUALIZAR EN BD
            $grupo->update($request->all());

            return response()->json(['success' => true, 'message' => 'Grupo actualizado exitosamente.', 'data' => $grupo]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al actualizar: '.$e->getMessage()], 500);
        }
    }

    public function destroy(Grupo $grupo)
    {
        $grupo->delete();

        return response()->json(['success' => true, 'message' => 'Grupo eliminado exitosamente.']);
    }

    public function getGruposByDistribucion(Request $request, $idDistribucion)
    {
        $sortBy = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = Grupo::with(['docenteCarrera.docente', 'asignatura'])
            ->where('idCupoDistribucion', $idDistribucion);

        if ($sortBy) {
            switch ($sortBy) {
                case 'nombre_grupo':
                    $query->orderBy('nombreGrupo', $sortDirection);
                    break;
                case 'asignatura':
                    $query->join('asignatura', 'grupo.idAsignatura', '=', 'asignatura.idAsignatura')
                        ->orderBy('asignatura.nombreAsignatura', $sortDirection)
                        ->select('grupo.*');
                    break;
                default:
                    $query->orderBy('idGrupo', 'desc');
                    break;
            }
        } else {
            $query->orderBy('idGrupo', 'desc');
        }

        $grupos = $query->paginate(5);

        $grupos->appends([
            'sort_by' => $sortBy,
            'sort_direction' => $sortDirection,
        ]);

        $distribucion = CupoDistribucion::with([
            'sedeCarrera.sede',
            'cupoOferta.unidadClinica',
        ])->find($idDistribucion);

        if (! $distribucion) {
            return response()->json(['error' => 'Distribución no encontrada'], 404);
        }

        return view('grupos._tabla_grupos', compact('grupos', 'distribucion', 'sortBy', 'sortDirection'))->render();
    }

    public function generarDossier($idGrupo)
    {
        $grupo = Grupo::with([
            'asignatura',
            'docenteCarrera.docente',
            'cupoDistribucion.sedeCarrera.sede.centroFormador',
            'cupoDistribucion.cupoOferta.unidadClinica.centroSalud',
            'cupoDistribucion.cupoOferta.tipoPractica',
            'alumnos',
        ])->findOrFail($idGrupo);

        return view('grupos.dossier', compact('grupo'));
    }
}
