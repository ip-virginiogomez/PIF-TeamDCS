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

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombreCarrera' => 'required|string|max:45|unique:carrera,nombreCarrera',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $e->errors(),
                ], 422);
            }
            throw $e;
        }

        // Establecer la fecha de creación automáticamente
        $data = $request->all();
        $data['fechaCreacion'] = now()->format('Y-m-d');

        $carrera = Carrera::create($data);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Carrera creada exitosamente.',
                'data' => $carrera,
            ]);
        }

        return redirect()->route('carreras.index')->with('success', 'Carrera creada exitosamente.');
    }

    public function edit(Carrera $carrera)
    {
        return response()->json($carrera);
    }

    public function update(Request $request, Carrera $carrera)
    {
        try {
            $request->validate([
                'nombreCarrera' => 'required|string|max:45|unique:carrera,nombreCarrera,'.$carrera->idCarrera.',idCarrera',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $e->errors(),
                ], 422);
            }
            throw $e;
        }

        // Solo actualizar el nombre de la carrera, mantener la fecha de creación original
        $carrera->update(['nombreCarrera' => $request->nombreCarrera]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Carrera actualizada exitosamente.',
                'data' => $carrera,
            ]);
        }

        return redirect()->route('carreras.index')->with('success', 'Carrera actualizada exitosamente.');
    }

    public function destroy(Carrera $carrera)
    {
        $carrera->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Carrera eliminada exitosamente.',
            ]);
        }

        return redirect()->route('carreras.index')->with('success', 'Carrera eliminada exitosamente.');
    }
}
