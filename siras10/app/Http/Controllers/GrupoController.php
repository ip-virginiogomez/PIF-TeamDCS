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
        // 1. VALIDACIÓN
        $validator = Validator::make($request->all(), [
            'nombreGrupo' => 'required|string|max:45',
            'idAsignatura' => 'required|exists:asignatura,idAsignatura',
            'idDocenteCarrera' => 'required|exists:docente_carrera,idDocenteCarrera',
            'idCupoDistribucion' => 'required|exists:cupo_distribucion,idCupoDistribucion',
            'fechaInicio' => 'nullable|date',
            'fechaFin' => 'nullable|date|after_or_equal:fechaInicio',
            // max:2048 KB = 2MB
            'archivo_dossier' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            // 2. PREPARAR DATOS
            $input = $request->except('archivo_dossier'); // Sacamos el archivo del array inicial

            // 3. SUBIDA DE ARCHIVO
            if ($request->hasFile('archivo_dossier')) {
                // Guarda en storage/app/public/dossiers
                $path = $request->file('archivo_dossier')->store('dossiers', 'public');
                $input['archivo_dossier'] = $path;
            }

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
            // max:2048 KB = 2MB
            'archivo_dossier' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            // 2. PREPARAR DATOS
            // Nota: Si usas FormData en JS, los campos vacíos pueden llegar como 'null' string, Laravel lo maneja,
            // pero nos aseguramos de no procesar el archivo si no viene.
            $input = $request->except(['archivo_dossier', '_method']);

            // 3. MANEJO DE ARCHIVO (Reemplazo)
            if ($request->hasFile('archivo_dossier')) {
                
                // A) Si ya existía un archivo antes, lo borramos para ahorrar espacio
                if ($grupo->archivo_dossier && Storage::disk('public')->exists($grupo->archivo_dossier)) {
                    Storage::disk('public')->delete($grupo->archivo_dossier);
                }

                // B) Subimos el nuevo
                $path = $request->file('archivo_dossier')->store('dossiers', 'public');
                $input['archivo_dossier'] = $path;
            }

            // 4. ACTUALIZAR EN BD
            $grupo->update($input);

            return response()->json(['success' => true, 'message' => 'Grupo actualizado exitosamente.', 'data' => $grupo]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Grupo $grupo)
    {
        // Opcional: Borrar el archivo físico si se borra el grupo
        if ($grupo->archivo_dossier && Storage::disk('public')->exists($grupo->archivo_dossier)) {
            Storage::disk('public')->delete($grupo->archivo_dossier);
        }

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