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
        $periodos = Periodo::orderBy('idPeriodo', 'desc')->paginate(10);

        // Si la petición es AJAX, devolvemos solo la vista de la tabla
        if ($request->ajax()) {
            return View::make('periodos._tabla', compact('periodos'))->render();
        }

        return view('periodos.index', compact('periodos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Año' => 'required|integer|min:2020',
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date|after_or_equal:fechaInicio',
        ]);

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

        $periodo->update($request->all());

        return response()->json(['success' => true, 'message' => 'Período actualizado exitosamente.']);
    }

    public function destroy(Periodo $periodo)
    {
        $periodo->delete();

        return response()->json(['success' => true, 'message' => 'Período eliminado exitosamente.']);
    }
}
