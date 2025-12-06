<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarreraController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:carreras.read')->only('index');
        $this->middleware('can:carreras.create')->only(['create', 'store']);
        $this->middleware('can:carreras.update')->only(['edit', 'update']);
        $this->middleware('can:carreras.delete')->only('destroy');
    }

    public function index()
    {
        $columnasDisponibles = ['idCarrera', 'nombreCarrera', 'fechaCreacion'];

        $sortBy = request()->get('sort_by', 'idCarrera');
        $sortDirection = request()->get('sort_direction', 'desc');
        $search = request()->input('search');

        if (! in_array($sortBy, $columnasDisponibles)) {
            $sortBy = 'idCarrera';
        }

        $query = Carrera::query();

        if ($search) {
            $query->where('nombreCarrera', 'like', "%{$search}%");
        }

        if (strpos($sortBy, '.') !== false) {
            [$tableRelacion, $columna] = explode('.', $sortBy);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        $carreras = $query->paginate(10);

        if (request()->ajax()) {
            return view('carreras._tabla', [
                'carreras' => $carreras,
                'sortBy' => $sortBy,
                'sortDirection' => $sortDirection,
            ])->render();
        }

        return view('carreras.index', [
            'carreras' => $carreras,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombreCarrera' => 'required|string|max:45|unique:carrera,nombreCarrera',
        ], [
            'nombreCarrera.required' => 'El nombre de la carrera es obligatorio.',
            'nombreCarrera.string' => 'El nombre de la carrera debe ser una cadena de texto.',
            'nombreCarrera.unique' => 'Ya existe una carrera con ese nombre.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación',
                'errors' => $validator->errors(),
            ], 422);
        }
        try {
            $data = $request->all();
            if (empty($data['fechaCreacion'])) {
                $data['fechaCreacion'] = now();
            }
            $carrera = Carrera::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Carrera creada exitosamente.',
                'data' => $carrera,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la carrera.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit(Carrera $carrera)
    {
        return response()->json($carrera);
    }

    public function update(Request $request, Carrera $carrera)
    {
        $validator = Validator::make($request->all(), [
            'nombreCarrera' => 'required|string|max:45|unique:carrera,nombreCarrera,'.$carrera->idCarrera.',idCarrera',
        ], [
            'nombreCarrera.required' => 'El nombre de la carrera es obligatorio.',
            'nombreCarrera.string' => 'El nombre de la carrera debe ser una cadena de texto.',
            'nombreCarrera.unique' => 'Ya existe una carrera con ese nombre.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación',
                'errors' => $validator->errors(),
            ], 422);
        }
        try {
            $data = $validator->validated();
            $carrera->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Carrera actualizada exitosamente.',
                'data' => $carrera,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la carrera.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Carrera $carrera)
    {
        $carrera->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Carrera eliminada exitosamente.',
            ]);
        }

        return redirect()->route('carreras.index')->with('success', 'Carrera eliminada exitosamente.');
    }
}
