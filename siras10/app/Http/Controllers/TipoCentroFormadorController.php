<?php

namespace App\Http\Controllers;

use App\Models\TipoCentroFormador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipoCentroFormadorController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:tipos-centro-formador.read')->only('index');
        $this->middleware('can:tipos-centro-formador.create')->only(['create', 'store']);
        $this->middleware('can:tipos-centro-formador.update')->only(['edit', 'update']);
        $this->middleware('can:tipos-centro-formador.delete')->only('destroy');
    }

    public function index()
    {
        $columnasDisponibles = ['idTipoCentroFormador', 'nombreTipo', 'fechaCreacion'];
        $sortBy = request()->get('sort_by', 'idTipoCentroFormador');
        $sortDirection = request()->get('sort_direction', 'desc');

        if (! in_array($sortBy, $columnasDisponibles)) {
            $sortBy = 'idTipoCentroFormador';
        }

        $query = TipoCentroFormador::query();

        if (strpos($sortBy, '.') !== false) {
            [$tableRelacion, $columna] = explode('.', $sortBy);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        $tiposCentro = $query->paginate(10);

        if (request()->ajax()) {
            return view('tipos-centro._tabla', [
                'tiposCentro' => $tiposCentro,
                'sortBy' => $sortBy,
                'sortDirection' => $sortDirection,
            ])->render();
        }

        return view('tipos-centro.index', [
            'tiposCentro' => $tiposCentro,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombreTipo' => 'required|string|max:45',
            'fechaCreacion' => 'nullable|date',
        ], [
            'nombreTipo.required' => 'El nombre del tipo de centro formador es obligatorio.',
            'nombreTipo.string' => 'El nombre del tipo de centro formador debe ser una cadena de texto.',
            'nombreTipo.max' => 'El nombre del tipo de centro formador no puede tener más de 45 caracteres.',
            'fechaCreacion.date' => 'La fecha de creación debe ser una fecha válida.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $data = $request->all();
            if (empty($data['fechaCreacion'])) {
                $data['fechaCreacion'] = now();
            }

            $tipocentro = TipoCentroFormador::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Tipo de Centro Formador creado exitosamente.',
                'tipocentro' => $tipocentro,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el tipo de centro formador.',
            ], 500);
        }
    }

    public function edit(string $id)
    {
        $tipos_centro_formador = TipoCentroFormador::findOrFail($id);

        return response()->json($tipos_centro_formador);
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nombreTipo' => 'required|string|max:45',
            'fechaCreacion' => 'nullable|date',
        ], [
            'nombreTipo.required' => 'El nombre del tipo de centro formador es obligatorio.',
            'nombreTipo.string' => 'El nombre del tipo de centro formador debe ser una cadena de texto.',
            'nombreTipo.max' => 'El nombre del tipo de centro formador no puede tener más de 45 caracteres.',
            'fechaCreacion.date' => 'La fecha de creación debe ser una fecha válida.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $tipos_centro_formador = TipoCentroFormador::findOrFail($id);
            $tipos_centro_formador->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Tipo de Centro Formador actualizado exitosamente.',
                'tipocentro' => $tipos_centro_formador,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el tipo de centro formador.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(TipoCentroFormador $tipos_centro_formador)
    {
        $tipos_centro_formador->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tipo de Centro Formador eliminado exitosamente.',
            ]);
        }

        return redirect()->route('tipos-centro-formador.index')
            ->with('success', 'Tipo de Centro Formador eliminado exitosamente.');
    }
}
