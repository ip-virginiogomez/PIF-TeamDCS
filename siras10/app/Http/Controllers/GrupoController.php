<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grupo;
use App\Models\CupoDistribucion;
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

        $query = CupoDistribucion::with(['sedeCarrera.sede', 'cupoOferta.unidadClinica']);

        if ($search) {
            $query->whereHas('sedeCarrera', function($q) use ($search) {
                $q->where('nombreSedeCarrera', 'like', "%{$search}%");
            })->orWhereHas('unidadClinica', function($q) use ($search) {
                $q->where('nombreUnidad', 'like', "%{$search}%");
            });
        }

        $distribuciones = $query->orderBy('idCupoDistribucion', 'desc')->paginate(5);

        if ($request->ajax() && !$request->has('get_grupos')) {
            return view('grupos._tabla_distribuciones', compact('distribuciones'))->render();
        }

        return view('grupos.index', compact('distribuciones'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombreGrupo' => 'required|string|max:45',
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
        $grupos = Grupo::where('idCupoDistribucion', $idDistribucion)->paginate(5);
        
        $distribucion = CupoDistribucion::with([
            'sedeCarrera.sede', 
            'cupoOferta.unidadClinica'
        ])->find($idDistribucion);

        if (!$distribucion) {
            return response()->json(['error' => 'Distribución no encontrada'], 404);
        }

        return view('grupos._tabla_grupos', compact('grupos', 'distribucion'))->render();
    }
}
