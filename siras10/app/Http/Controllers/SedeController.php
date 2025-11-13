<?php

namespace App\Http\Controllers;

use App\Models\CentroFormador;
use App\Models\Sede;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SedeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:sede.read')->only('index');
        $this->middleware('permission:sede.create')->only('create', 'store');
        $this->middleware('permission:sede.update')->only('edit', 'update');
        $this->middleware('permission:sede.delete')->only('destroy');
    }
    
    public function index(Request $request)
    {
        $columnasDisponibles = ['idSede', 'nombreSede', 'direccion', 'centroFormador.nombreCentroFormador', 'fechaCreacion', 'numContacto'];

        $sortBy = request()->get('sort_by', 'idSede');
        $sortDirection = request()->get('sort_direction', 'asc');

        if (! in_array($sortBy, $columnasDisponibles)) {
            $sortBy = 'idSede';
        }

        $query = Sede::query();

        if (strpos($sortBy, '.') !== false) {
            [$tableRelacion, $columna] = explode('.', $sortBy);
            if ($tableRelacion === 'centroFormador') {
                $query->join('centro_formador', 'sede.idCentroFormador', '=', 'centro_formador.idCentroFormador')
                    ->orderBy('centro_formador.'.$columna, $sortDirection)
                    ->select('sede.*');
            }
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        $sedes = $query->with(['centroFormador'])->paginate(10);

        if ($request->ajax()) {
            return view('sede._tabla', [
                'sedes' => $sedes,
                'sortBy' => $sortBy,
                'sortDirection' => $sortDirection,
            ])->render();
        }

        $centrosFormadores = CentroFormador::all();

        return view('sede.index', [
            'sedes' => $sedes,
            'centrosFormadores' => $centrosFormadores,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombreSede' => 'required|string|max:255',
            'direccion' => 'required|string|max:500',
            'idCentroFormador' => 'required|exists:centro_formador,idCentroFormador',
            'fechaCreacion' => 'nullable|date',
            'numContacto' => [
                'nullable',
                'string',
                'max:12',
                'regex:/^\+[0-9]{1,11}$/',
            ],
        ], [
            'nombreSede.required' => 'El nombre de la sede es obligatorio.',
            'direccion.required' => 'La dirección es obligatoria.',
            'idCentroFormador.required' => 'Debe seleccionar un centro formador.',
            'idCentroFormador.exists' => 'El centro formador seleccionado no es válido.',
            'fechaCreacion.date' => 'La fecha de creación debe ser una fecha válida.',
            'numContacto.max' => 'El número de contacto debe tener exactamente 12 caracteres.',
            'numContacto.regex' => 'El número de contacto debe tener el formato +[código de país][número].',
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
                $data['fechaCreacion'] = now()->format('Y-m-d');
            }

            $sede = Sede::create($data);
            $sede->load(['centroFormador']);

            return response()->json([
                'success' => true,
                'message' => 'Sede creada exitosamente.',
                'sede' => $sede,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la sede: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sede = Sede::with(['centroFormador'])->findOrFail($id);

        return response()->json($sede);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $sede = Sede::findOrFail($id);

        return response()->json($sede);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nombreSede' => 'required|string|max:255',
            'direccion' => 'required|string|max:500',
            'idCentroFormador' => 'required|exists:centro_formador,idCentroFormador',
            'fechaCreacion' => 'nullable|date',
            'numContacto' => [
                'nullable',
                'string',
                'max:12',
                'regex:/^\+[0-9]{1,11}$/',
            ],
        ], [
            'nombreSede.required' => 'El nombre de la sede es obligatorio.',
            'direccion.required' => 'La dirección es obligatoria.',
            'idCentroFormador.required' => 'Debe seleccionar un centro formador.',
            'idCentroFormador.exists' => 'El centro formador seleccionado no es válido.',
            'fechaCreacion.date' => 'La fecha de creación debe ser una fecha válida.',
            'numContacto.max' => 'El número de contacto debe tener exactamente 12 caracteres.',
            'numContacto.regex' => 'El número de contacto debe tener el formato +[código de país][número].',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $sede = Sede::findOrFail($id);
            $sede->update($request->all());
            $sede->load(['centroFormador']);

            return response()->json([
                'success' => true,
                'message' => 'Sede actualizada exitosamente.',
                'sede' => $sede,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la sede: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $sede = Sede::findOrFail($id);
            $sede->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sede eliminada exitosamente.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la sede: '.$e->getMessage(),
            ], 500);
        }
    }
}
