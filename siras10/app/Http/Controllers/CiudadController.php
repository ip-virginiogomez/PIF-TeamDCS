<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use Illuminate\Http\Request;

class CiudadController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:ciudades.read')->only('index');
        $this->middleware('can:ciudades.create')->only(['create', 'store']);
        $this->middleware('can:ciudades.update')->only(['edit', 'update']);
        $this->middleware('can:ciudades.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $sortBy = $request->query('sort_by', 'nombreCiudad');
        $sortDirection = $request->query('sort_direction', 'asc');
        $search = $request->query('search');

        $query = Ciudad::query();

        if ($search) {
            $query->where('nombreCiudad', 'like', "%{$search}%");
        }

        $ciudades = $query->orderBy($sortBy, $sortDirection)
            ->paginate(10);

        if ($request->ajax()) {
            return view('ciudad._tabla', compact('ciudades', 'sortBy', 'sortDirection'));
        }

        return view('ciudad.index', compact('ciudades', 'sortBy', 'sortDirection'));
    }

    public function create()
    {
        return view('ciudad.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombreCiudad' => [
                'required',
                'string',
                'max:100',
                \Illuminate\Validation\Rule::unique('ciudad', 'nombreCiudad')->whereNull('deleted_at'),
            ],
        ], [
            'nombreCiudad.required' => 'El nombre de la ciudad es obligatorio.',
            'nombreCiudad.unique' => 'Esta ciudad ya existe.',
        ]);

        $validated['fechacreacion'] = now();

        $ciudad = Ciudad::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Ciudad creada exitosamente',
                'data' => $ciudad,
            ], 201);
        }

        return redirect()->route('ciudad.index')
            ->with('success', 'Ciudad creada exitosamente');
    }

    public function show($id)
    {
        $ciudad = Ciudad::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $ciudad,
        ]);
    }

    public function edit($id)
    {
        $ciudad = Ciudad::findOrFail($id);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'ciudad' => $ciudad,
            ]);
        }

        return view('ciudad.edit', compact('ciudad'));
    }

    public function update(Request $request, $id)
    {
        $ciudad = Ciudad::findOrFail($id);

        $validated = $request->validate([
            'nombreCiudad' => [
                'required',
                'string',
                'max:100',
                \Illuminate\Validation\Rule::unique('ciudad', 'nombreCiudad')
                    ->ignore($id, 'idCiudad')
                    ->whereNull('deleted_at'),
            ],
        ], [
            'nombreCiudad.required' => 'El nombre de la ciudad es obligatorio.',
            'nombreCiudad.unique' => 'Esta ciudad ya existe.',
        ]);

        $ciudad->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Ciudad actualizada exitosamente',
                'data' => $ciudad,
            ]);
        }

        return redirect()->route('ciudad.index')
            ->with('success', 'Ciudad actualizada exitosamente');
    }

    public function destroy($id)
    {
        try {
            $ciudad = Ciudad::findOrFail($id);

            // Verificar si tiene centros de salud asociados
            if ($ciudad->centroSaluds()->count() > 0) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se puede eliminar esta ciudad porque tiene centros de salud asociados',
                    ], 400);
                }

                return redirect()->route('ciudad.index')
                    ->with('error', 'No se puede eliminar esta ciudad porque tiene centros de salud asociados');
            }

            $ciudad->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ciudad eliminada exitosamente',
                ]);
            }

            return redirect()->route('ciudad.index')
                ->with('success', 'Ciudad eliminada exitosamente');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la ciudad: '.$e->getMessage(),
                ], 500);
            }

            return redirect()->route('ciudad.index')
                ->with('error', 'Error al eliminar la ciudad');
        }
    }
}
