<?php

namespace App\Http\Controllers;

use App\Models\CentroFormador;
use App\Models\TipoCentroFormador; // Importamos el modelo relacionado
use Illuminate\Http\Request;

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
        $centros = CentroFormador::with('tipoCentroFormador')->paginate(10);

        return view('centros-formadores.index', compact('centros'));
    }

    public function create()
    {
        $tipos = TipoCentroFormador::all();

        return view('centros-formadores.create', compact('tipos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombreCentroFormador' => 'required|string|max:45',
            'idTipoCentroFormador' => ['required', 'exists:App\Models\TipoCentroFormador,idTipoCentroFormador'],
        ]);

        CentroFormador::create($request->all());

        return redirect()->route('centros-formadores.index')
            ->with('success', 'Centro Formador creado exitosamente.');
    }

    public function show(CentroFormador $centros_formadore)
    {
        return view('centros-formadores.show', compact('centros_formadore'));
    }

    public function edit(CentroFormador $centros_formadore)
    {
        $tipos = TipoCentroFormador::all();

        return view('centros-formadores.edit', compact('centros_formadore', 'tipos'));
    }

    public function update(Request $request, CentroFormador $centros_formadore)
    {
        $request->validate([
            'nombreCentroFormador' => 'required|string|max:45',
            'idTipoCentroFormador' => ['required', 'exists:App\Models\TipoCentroFormador,idTipoCentroFormador'],
        ]);

        $centros_formadore->update($request->all());

        return redirect()->route('centros-formadores.index')
            ->with('success', 'Centro Formador actualizado exitosamente.');
    }

    public function destroy(CentroFormador $centros_formadore)
    {
        $centros_formadore->delete();

        return redirect()->route('centros-formadores.index')
            ->with('success', 'Centro Formador eliminado exitosamente.');
    }
}
