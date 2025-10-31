<?php

namespace App\Http\Controllers;

use App\Models\CentroFormador;
use App\Models\TipoCentroFormador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CentroFormadorController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:centros-formadores.read')->only('index');
        $this->middleware('can:centros-formadores.create')->only(['create', 'store']);
        $this->middleware('can:centros-formadores.update')->only(['edit', 'update']);
        $this->middleware('can:centros-formadores.delete')->only('destroy');
    }

    public function index()
    {
        $columnasDisponibles = ['idCentroFormador', 'tipoCentroFormador.nombreTipo', 'nombreCentroFormador', 'fechaCreacion'];

        $sortBy = request()->get('sort_by', 'idCentroFormador');
        $sortDirection = request()->get('sort_direction', 'asc');

        if (! in_array($sortBy, $columnasDisponibles)) {
            $sortBy = 'idCentroFormador';
        }

        $query = CentroFormador::query();

        if (strpos($sortBy, '.') !== false) {
            [$tableRelacion, $columna] = explode('.', $sortBy);
            if (tableRelacion === 'tipoCentroFormador') {
                $query->join('tipo_centro_formador', 'centro_formador.idTipoCentroFormador', '=', 'tipo_centro_formador.idTipoCentroFormador')
                    ->orderBy('tipo_centro_formador.'.$columna, $sortDirection)
                    ->select('centro_formador.*');
            }
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        $centrosFormadores = $query->with(['tipoCentroFormador'])->paginate(10);

        if (request()->ajax()) {
            return view('centros-formadores._tabla', [
                'centrosFormadores' => $centrosFormadores,
                'sortBy' => $sortBy,
                'sortDirection' => $sortDirection,
            ])->render();
        }

        $tipoCentroFormador = TipoCentroFormador::all();

        return view('centros-formadores.index', [
            'centrosFormadores' => $centrosFormadores,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
            'tipoCentroFormador' => $tipoCentroFormador,
        ]);
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nombreCentroFormador' => 'required|string|max:45',
            'idTipoCentroFormador' => 'required|exists:tipo_centro_formador,idTipoCentroFormador',
            'fechaCreacion' => 'nullable|date'
        ],[
            'nombreCentroFormador.required' => 'El nombre del centro formador es obligatorio.',
            'idTipoCentroFormador.required' => 'Debe seleccionar un tipo de centro formador.',
            'idTipoCentroFormador.exists' => 'El tipo de centro formador seleccionado no es válido.',
            'fechaCreacion.date' => 'La fecha de creación debe ser una fecha válida.',
        ]);
        if ($validator-> fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try{
            $data = $request->all();

            if (empty($data['fechaCreacion'])) {
                $data['fechaCreacion'] = now()->format('Y-m-d');
            }

            $centroFormador = CentroFormador::create($data);
            $centroFormador->load(['tipoCentroFormador']);

            return response()->json([
                'success' => true,
                'message' => 'Centro Formador creado exitosamente.',
                'centroFormador' => $centroFormador,
            ]);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el centro formador: '.$e->getMessage(),
            ], 500);
        }
    }

    public function show(CentroFormador $centros_formadore)
    {
        $centroFormador = CentroFormador::with(['tipoCentroFormador'])->findOrFail($id);

        return response()->json($centroFormador);
    }

    public function edit(string $id)
    {
        $centroFormador = CentroFormador::findOrFail($id);

        return response()->json($centroFormador);
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(),[
            'nombreCentroFormador' => 'required|string|max:45',
            'idTipoCentroFormador' => 'required|exists:tipo_centro_formador,idTipoCentroFormador',
            'fechaCreacion' => 'nullable|date'
        ],[
            'nombreCentroFormador.required' => 'El nombre del centro formador es obligatorio.',
            'idTipoCentroFormador.required' => 'Debe seleccionar un tipo de centro formador.',
            'idTipoCentroFormador.exists' => 'El tipo de centro formador seleccionado no es válido.',
            'fechaCreacion.date' => 'La fecha de creación debe ser una fecha válida.',
        ]);
        if ($validator-> fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try{
            $centroFormador = CentroFormador::findOrFail($id);
            $centroFormador->update($request->all());
            $centroFormador->load(['tipoCentroFormador']);

            return response()->json([
                'success' => true,
                'message' => 'Centro Formador actualizado exitosamente.',
                'centroFormador' => $centroFormador,
            ]);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el centro formador: '.$e->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $centroFormador = CentroFormador::findOrFail($id);
            $centroFormador->delete();

            return response()->json([
                'success' => true,
                'message' => 'Centro Formador eliminado exitosamente.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el centro formador: '.$e->getMessage(),
            ], 500);
        }
    }
}
