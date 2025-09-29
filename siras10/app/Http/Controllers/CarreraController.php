<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use Illuminate\Http\Request;

class CarreraController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:carreras.read')->only('index');
        $this->middleware('can:carreras.create')->only(['create', 'store']);
        $this->middleware('can:carreras.update')->only(['edit', 'update']);
        $this->middleware('can:carreras.delete')->only('destroy');
    }

    public function index()
    {
        $carreras = Carrera::paginate(10);

        // AJUSTE AQUÍ
        return view('admin.carreras.index', compact('carreras'));
    }

    public function create()
    {
        // AJUSTE AQUÍ
        return view('admin.carreras.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombreCarrera' => 'required|string|max:45|unique:carrera,nombreCarrera',
            'fechaCreacion' => 'required|date',
        ]);

        Carrera::create($request->all());

        return redirect()->route('carreras.index')->with('success', 'Carrera creada exitosamente.');
    }

    public function edit(Carrera $carrera)
    {
        // AJUSTE AQUÍ
        return view('admin.carreras.edit', compact('carrera'));
    }

    public function update(Request $request, Carrera $carrera)
    {
        $request->validate([
            'nombreCarrera' => 'required|string|max:45|unique:carrera,nombreCarrera,'.$carrera->idCarrera.',idCarrera',
            'fechaCreacion' => 'required|date',
        ]);

        $carrera->update($request->all());

        return redirect()->route('carreras.index')->with('success', 'Carrera actualizada exitosamente.');
    }

    public function destroy(Carrera $carrera)
    {
        $carrera->delete();

        return redirect()->route('carreras.index')->with('success', 'Carrera eliminada exitosamente.');
    }
}
