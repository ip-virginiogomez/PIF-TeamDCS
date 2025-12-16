<?php

namespace App\Http\Controllers;

use App\Models\TipoCentroSalud;
use Illuminate\Http\Request;

class TipoCentroSaludController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:tipos-centro-salud.read')->only('index');
        $this->middleware('can:tipos-centro-salud.create')->only(['create', 'store']);
        $this->middleware('can:tipos-centro-salud.update')->only(['edit', 'update']);
        $this->middleware('can:tipos-centro-salud.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $sortBy = $request->query('sort_by', 'nombreTipo');
        $sortDirection = $request->query('sort_direction', 'asc');
        $search = $request->query('search');

        $query = TipoCentroSalud::query();

        if ($search) {
            $query->where('nombreTipo', 'like', "%{$search}%");
        }

        $tipos = $query->orderBy($sortBy, $sortDirection)
            ->paginate(10);

        if ($request->ajax()) {
            return view('tipo-centro-salud._tabla', compact('tipos', 'sortBy', 'sortDirection'));
        }

        return view('tipo-centro-salud.index', compact('tipos', 'sortBy', 'sortDirection'));
    }

    public function create()
    {
        return view('tipo-centro-salud.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombreTipo' => [
                'required',
                'string',
                'max:100',
                \Illuminate\Validation\Rule::unique('tipo_centro_salud', 'nombreTipo')->whereNull('deleted_at'),
            ],
        ], [
            'nombreTipo.required' => 'El nombre del tipo de centro es obligatorio.',
            'nombreTipo.unique' => 'Este tipo de centro ya existe.',
        ]);

        $validated['fechaCreacion'] = now();

        $tipo = TipoCentroSalud::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tipo de centro creado exitosamente',
                'data' => $tipo,
            ], 201);
        }

        return redirect()->route('tipo-centro-salud.index')
            ->with('success', 'Tipo de centro creado exitosamente');
    }

    public function show($id)
    {
        $tipo = TipoCentroSalud::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $tipo,
        ]);
    }

    public function edit($id)
    {
        $tipo = TipoCentroSalud::findOrFail($id);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'tipo' => $tipo,
            ]);
        }

        return view('tipo-centro-salud.edit', compact('tipo'));
    }

    public function update(Request $request, $id)
    {
        $tipo = TipoCentroSalud::findOrFail($id);

        $validated = $request->validate([
            'nombreTipo' => [
                'required',
                'string',
                'max:100',
                \Illuminate\Validation\Rule::unique('tipo_centro_salud', 'nombreTipo')
                    ->ignore($id, 'idTipoCentroSalud')
                    ->whereNull('deleted_at'),
            ],
        ], [
            'nombreTipo.required' => 'El nombre del tipo de centro es obligatorio.',
            'nombreTipo.unique' => 'Este tipo de centro ya existe.',
        ]);

        $tipo->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tipo de centro actualizado exitosamente',
                'data' => $tipo,
            ]);
        }

        return redirect()->route('tipo-centro-salud.index')
            ->with('success', 'Tipo de centro actualizado exitosamente');
    }

    public function destroy($id)
    {
        try {
            $tipo = TipoCentroSalud::findOrFail($id);

            // Verificar si tiene centros de salud asociados
            if ($tipo->centrosSalud()->count() > 0) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se puede eliminar este tipo de centro porque tiene centros de salud asociados',
                    ], 400);
                }

                return redirect()->route('tipo-centro-salud.index')
                    ->with('error', 'No se puede eliminar este tipo de centro porque tiene centros de salud asociados');
            }

            $tipo->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tipo de centro eliminado exitosamente',
                ]);
            }

            return redirect()->route('tipo-centro-salud.index')
                ->with('success', 'Tipo de centro eliminado exitosamente');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el tipo de centro: '.$e->getMessage(),
                ], 500);
            }

            return redirect()->route('tipo-centro-salud.index')
                ->with('error', 'Error al eliminar el tipo de centro');
        }
    }
}
