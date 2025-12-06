<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PeriodoController extends Controller
{
    // Aplicar seguridad con middleware de permisos
    public function __construct()
    {
        $this->middleware('can:periodos.read')->only('index');
        $this->middleware('can:periodos.create')->only(['create', 'store']);
        $this->middleware('can:periodos.update')->only(['edit', 'update']);
        $this->middleware('can:periodos.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->get('sort_by', 'idPeriodo');
        $sortDirection = $request->get('sort_direction', 'desc');

        $query = Periodo::query();

        if ($search) {
            $query->where('Año', 'like', "%{$search}%");
        }

        $query->orderBy($sortBy, $sortDirection);

        $periodos = $query->paginate(10);

        // Si la petición es AJAX, devolvemos solo la vista de la tabla
        if ($request->ajax()) {
            return View::make('periodos._tabla', compact('periodos', 'sortBy', 'sortDirection'))->render();
        }

        return view('periodos.index', compact('periodos', 'sortBy', 'sortDirection'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Año' => 'required|integer|min:2020',
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date|after_or_equal:fechaInicio',
        ]);

        // Validar que no exista un periodo para el año especificado
        $añoSolicitado = $request->input('Año');
        $periodoExistente = Periodo::where('Año', $añoSolicitado)->first();

        if ($periodoExistente) {
            return response()->json([
                'success' => false,
                'message' => "Ya existe un periodo para el año {$añoSolicitado}. Solo se permite un periodo de oferta de cupos por año.",
                'errors' => [
                    'Año' => ["Ya existe un periodo para el año {$añoSolicitado}."],
                ],
            ], 422);
        }

        Periodo::create($request->all());

        return response()->json(['success' => true, 'message' => 'Período creado exitosamente.']);
    }

    // Devuelve los datos de un período en formato JSON para el modal de edición
    public function edit(Periodo $periodo)
    {
        return response()->json($periodo);
    }

    public function update(Request $request, Periodo $periodo)
    {
        $request->validate([
            'Año' => 'required|integer|min:2020',
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date|after_or_equal:fechaInicio',
        ]);

        // Validar que no exista otro periodo para el año especificado (excepto el actual)
        $añoSolicitado = $request->input('Año');
        $periodoExistente = Periodo::where('Año', $añoSolicitado)
            ->where('idPeriodo', '!=', $periodo->idPeriodo)
            ->first();

        if ($periodoExistente) {
            return response()->json([
                'success' => false,
                'message' => "Ya existe un periodo para el año {$añoSolicitado}. Solo se permite un periodo de oferta de cupos por año.",
                'errors' => [
                    'Año' => ["Ya existe un periodo para el año {$añoSolicitado}."],
                ],
            ], 422);
        }

        $periodo->update($request->all());

        return response()->json(['success' => true, 'message' => 'Período actualizado exitosamente.']);
    }

    public function destroy(Periodo $periodo)
    {
        $periodo->delete();

        return response()->json(['success' => true, 'message' => 'Período eliminado exitosamente.']);
    }
}
