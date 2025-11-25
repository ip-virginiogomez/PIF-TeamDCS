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

        $query = CupoDistribucion::with([
            'sedeCarrera.sede',
            'cupoOferta.unidadClinica.centroSalud',
        ]);

        if ($search) {
            $query->whereHas('sedeCarrera', function ($q) use ($search) {
                $q->where('nombreSedeCarrera', 'like', "%{$search}%");
            })
                ->orWhereHas('unidadClinica', function ($q) use ($search) {
                    $q->where('nombreUnidad', 'like', "%{$search}%");
                })
                ->orWhereHas('cupoOferta.unidadClinica.centroSalud', function ($q) use ($search) {
                    $q->where('nombreCentroSalud', 'like', "%{$search}%");
                });
        }

        $distribuciones = $query->orderBy('idCupoDistribucion', 'desc')->paginate(5);
        $listaDocentesCarrera = DocenteCarrera::with(['docente', 'sedeCarrera'])->get();

        $listaAsignaturas = Asignatura::orderBy('nombreAsignatura')->get();

        if ($request->ajax() && ! $request->has('get_grupos')) {
            return view('grupos._tabla_distribuciones', compact('distribuciones'))->render();
        }

        return view('grupos.index', compact('distribuciones', 'listaDocentesCarrera', 'listaAsignaturas'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombreGrupo' => 'required|string|max:45',
            'idAsignatura' => 'required|exists:asignatura,idAsignatura',
            'idDocenteCarrera' => 'required|exists:docente_carrera,idDocenteCarrera',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $grupo = Grupo::create($request->all());

        return response()->json(['success' => true, 'message' => 'Grupo creado exitosamente.', 'data' => $grupo]);
    }

    public function edit(Grupo $grupo)
    {
        return response()->json($grupo);
    }

    public function update(Request $request, Grupo $grupo)
    {
        $validator = Validator::make($request->all(), [
            'nombreGrupo' => 'required|string|max:45',
            'idAsignatura' => 'required|exists:asignatura,idAsignatura',
            'idDocenteCarrera' => 'required|exists:docente_carrera,idDocenteCarrera',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $grupo->update($request->all());

        return response()->json(['success' => true, 'message' => 'Grupo actualizado exitosamente.', 'data' => $grupo]);
    }

    public function destroy(Grupo $grupo)
    {
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
        ])->findOrFail($idGrupo);

        return view('grupos.dossier', compact('grupo'));
    }
}
