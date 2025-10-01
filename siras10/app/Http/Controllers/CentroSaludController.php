<?php

namespace App\Http\Controllers;

use App\Models\CentroSalud;
use App\Models\Ciudad;
use App\Models\TipoCentroSalud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CentroSaludController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $centrosSalud = CentroSalud::with(['ciudad', 'tipoCentroSalud'])->get();
        $ciudades = Ciudad::all();
        $tiposCentro = TipoCentroSalud::all();

        return view('centro-salud.index', compact('centrosSalud', 'ciudades', 'tiposCentro'));
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
            'nombreCentro' => 'required|string|max:255',
            'direccion' => 'required|string|max:500',
            'numContacto' => 'required|string|max:20',
            'idCiudad' => 'required|exists:ciudad,idCiudad',
            'idTipoCentroSalud' => 'required|exists:tipo_centro_salud,idTipoCentroSalud',
        ], [
            'nombreCentro.required' => 'El nombre del centro es obligatorio.',
            'direccion.required' => 'La dirección es obligatoria.',
            'numContacto.required' => 'El número de contacto es obligatorio.',
            'idCiudad.required' => 'Debe seleccionar una ciudad.',
            'idCiudad.exists' => 'La ciudad seleccionada no es válida.',
            'idTipoCentroSalud.required' => 'Debe seleccionar un tipo de centro.',
            'idTipoCentroSalud.exists' => 'El tipo de centro seleccionado no es válido.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $centroSalud = CentroSalud::create($request->all());
            $centroSalud->load(['ciudad', 'tipoCentroSalud']);

            return response()->json([
                'success' => true,
                'message' => 'Centro de salud creado exitosamente.',
                'centro' => $centroSalud,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el centro de salud: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $centroSalud = CentroSalud::with(['ciudad', 'tipoCentroSalud'])->findOrFail($id);

        return response()->json($centroSalud);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $centroSalud = CentroSalud::findOrFail($id);

        return response()->json($centroSalud);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nombreCentro' => 'required|string|max:255',
            'direccion' => 'required|string|max:500',
            'numContacto' => 'required|string|max:20',
            'idCiudad' => 'required|exists:ciudad,idCiudad',
            'idTipoCentroSalud' => 'required|exists:tipo_centro_salud,idTipoCentroSalud',
        ], [
            'nombreCentro.required' => 'El nombre del centro es obligatorio.',
            'direccion.required' => 'La dirección es obligatoria.',
            'numContacto.required' => 'El número de contacto es obligatorio.',
            'idCiudad.required' => 'Debe seleccionar una ciudad.',
            'idCiudad.exists' => 'La ciudad seleccionada no es válida.',
            'idTipoCentroSalud.required' => 'Debe seleccionar un tipo de centro.',
            'idTipoCentroSalud.exists' => 'El tipo de centro seleccionado no es válido.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $centroSalud = CentroSalud::findOrFail($id);
            $centroSalud->update($request->all());
            $centroSalud->load(['ciudad', 'tipoCentroSalud']);

            return response()->json([
                'success' => true,
                'message' => 'Centro de salud actualizado exitosamente.',
                'centro' => $centroSalud,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el centro de salud: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $centroSalud = CentroSalud::findOrFail($id);
            $centroSalud->delete();

            return response()->json([
                'success' => true,
                'message' => 'Centro de salud eliminado exitosamente.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el centro de salud: '.$e->getMessage(),
            ], 500);
        }
    }
}
