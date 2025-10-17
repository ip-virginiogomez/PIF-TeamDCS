<?php

namespace App\Http\Controllers;

use App\Models\TipoPractica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class TipoPracticaController extends Controller
{
    // Aplicar seguridad con middleware de permisos
    public function __construct()
    {
        $this->middleware('can:tipos-practica.read')->only('index');
        $this->middleware('can:tipos-practica.create')->only('store');
        $this->middleware('can:tipos-practica.update')->only(['edit', 'update']);
        $this->middleware('can:tipos-practica.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $tiposPractica = TipoPractica::paginate(10);

        if ($request->ajax()) {
            return View::make('tipos-practica._tabla', compact('tiposPractica'))->render();
        }

        return view('tipos-practica.index', compact('tiposPractica'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombrePractica' => 'required|string|max:45|unique:tipo_practica,nombrePractica',
        ]);

        TipoPractica::create($request->all());

        return response()->json(['success' => true, 'message' => 'Tipo de Práctica creado exitosamente.']);
    }

    // Devuelve los datos de un registro en JSON para el modal de edición
    public function edit(TipoPractica $tipo_practica)
    {
        return response()->json($tipo_practica);
    }

    public function update(Request $request, TipoPractica $tipo_practica)
    {
        $request->validate([
            'nombrePractica' => 'required|string|max:45|unique:tipo_practica,nombrePractica,'.$tipo_practica->idTipoPractica.',idTipoPractica',
        ]);

        $tipo_practica->update($request->all());

        return response()->json(['success' => true, 'message' => 'Tipo de Práctica actualizado exitosamente.']);
    }

    public function destroy(TipoPractica $tipo_practica)
    {
        $tipo_practica->delete();

        return response()->json(['success' => true, 'message' => 'Tipo de Práctica eliminado exitosamente.']);
    }
}
