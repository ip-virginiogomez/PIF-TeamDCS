<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\CupoDistribucion;
use App\Models\DocenteCarrera;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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

        $periodosDisponibles = \App\Models\CupoOferta::selectRaw('YEAR(fechaEntrada) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $query = CupoDistribucion::with([
            'sedeCarrera.sede.centroFormador',
            'cupoOferta.unidadClinica.centroSalud',
        ]);

        if ($search) {
            $query->where(function($q) use ($search) {
                // A. Buscar por Centro Formador
                $q->whereHas('sedeCarrera.sede.centroFormador', function ($sub) use ($search) {
                    $sub->where('nombreCentroFormador', 'like', "%{$search}%");
                })
                // B. Buscar por Sede / Carrera
                ->orWhereHas('sedeCarrera', function ($sub) use ($search) {
                    $sub->where('nombreSedeCarrera', 'like', "%{$search}%");
                })
                // C. Buscar por Centro de Salud
                ->orWhereHas('cupoOferta.unidadClinica.centroSalud', function ($sub) use ($search) {
                    $sub->where('nombreCentro', 'like', "%{$search}%"); // Ojo: verifica si es 'nombreCentro' o 'nombreCentroSalud' en tu BD
                })
                // D. Buscar por Unidad Clínica
                ->orWhereHas('cupoOferta.unidadClinica', function ($sub) use ($search) {
                    $sub->where('nombreUnidad', 'like', "%{$search}%");
                });
            });
        }

        if ($periodo) {
            $query->whereHas('cupoOferta', function ($q) use ($periodo) {
                $q->whereYear('fechaEntrada', $periodo);
            });
        }

        $distribuciones = $query->orderBy('idCupoDistribucion', 'desc')->paginate(5);

        $distribuciones->appends(['search' => $search, 'periodo' => $periodo]);

        $listaDocentesCarrera = DocenteCarrera::with(['docente', 'sedeCarrera'])->get();

        $listaAsignaturas = Asignatura::orderBy('nombreAsignatura')->get();

        if ($request->ajax() && ! $request->has('get_grupos')) {
            return view('grupos._tabla_distribuciones', compact('distribuciones'))->render();
        }

        return view('grupos.index', compact('distribuciones', 'listaDocentesCarrera', 'listaAsignaturas', 'periodosDisponibles'));
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
            $grupo = Grupo::create($input);

            return response()->json(['success' => true, 'message' => 'Grupo creado exitosamente.', 'data' => $grupo]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al guardar: ' . $e->getMessage()], 500);
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
            $grupo->update($input);

            return response()->json(['success' => true, 'message' => 'Grupo actualizado exitosamente.', 'data' => $grupo]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Grupo $grupo){
        $grupo->delete();

        return response()->json(['success' => true, 'message' => 'Grupo eliminado exitosamente.']);
    }

    public function getGruposByDistribucion($idDistribucion)
    {
        $grupos = Grupo::with(['docenteCarrera.docente', 'asignatura'])
            ->where('idCupoDistribucion', $idDistribucion)
            ->paginate(5);

        $distribucion = CupoDistribucion::with([
            'sedeCarrera.sede',
            'cupoOferta.unidadClinica',
        ])->find($idDistribucion);

        if (! $distribucion) {
            return response()->json(['error' => 'Distribución no encontrada'], 404);
        }

        return view('grupos._tabla_grupos', compact('grupos', 'distribucion'))->render();
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