<?php

namespace App\Http\Controllers;

use App\Models\TipoCentroFormador;
use Illuminate\Http\Request;

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
        $tipos = TipoCentroFormador::paginate(10);

        return view('admin.tipos-centro.index', compact('tipos'));
    }

    public function create()
    {
        return view('admin.tipos-centro.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombreTipo' => 'required|string|max:45',
            'acronimo' => 'nullable|string|max:10',
        ]);

        TipoCentroFormador::create($request->all());

        return redirect()->route('tipos-centro-formador.index')
            ->with('success', 'Tipo de Centro Formador creado exitosamente.');
    }

    public function show(TipoCentroFormador $tipos_centro_formador)
    {
        return view('admin.tipos-centro.show', compact('tipos_centro_formador'));
    }

    public function edit(TipoCentroFormador $tipos_centro_formador)
    {
        return view('admin.tipos-centro.edit', compact('tipos_centro_formador'));
    }

    public function update(Request $request, TipoCentroFormador $tipos_centro_formador)
    {
        $request->validate([
            'nombreTipo' => 'required|string|max:45',
            'acronimo' => 'nullable|string|max:10',
        ]);

        $tipos_centro_formador->update($request->all());

        return redirect()->route('tipos-centro-formador.index')
            ->with('success', 'Tipo de Centro Formador actualizado exitosamente.');
    }

    public function destroy(TipoCentroFormador $tipos_centro_formador)
    {
        $tipos_centro_formador->delete();

        return redirect()->route('tipos-centro-formador.index')
            ->with('success', 'Tipo de Centro Formador eliminado exitosamente.');
    }
}
