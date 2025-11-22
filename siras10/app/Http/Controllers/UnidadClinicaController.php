<?php

namespace App\Http\Controllers;

use App\Models\CentroSalud;
use App\Models\UnidadClinica; // Importamos el modelo relacionado
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class UnidadClinicaController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:unidad-clinicas.read')->only('index');
        $this->middleware('can:unidad-clinicas.create')->only('store');
        $this->middleware('can:unidad-clinicas.update')->only(['edit', 'update']);
        $this->middleware('can:unidad-clinicas.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $unidadesClinicas = UnidadClinica::with('centroSalud')->orderBy('idUnidadClinica', 'desc')->paginate(10);

        if ($request->ajax()) {
            return View::make('unidad-clinicas._tabla', compact('unidadesClinicas'))->render();
        }

        // Cargamos los Centros de Salud para el <select> del modal
        $centrosSalud = CentroSalud::orderBy('nombreCentro')->get();

        return view('unidad-clinicas.index', compact('unidadesClinicas', 'centrosSalud'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombreUnidad' => 'required|string|max:45',
            'idCentroSalud' => 'required|exists:centro_salud,idCentroSalud',
        ]);

        UnidadClinica::create($request->all());

        return response()->json(['success' => true, 'message' => 'Unidad Clínica creada exitosamente.']);
    }

    public function edit(UnidadClinica $unidad_clinica)
    {
        return response()->json($unidad_clinica);
    }

    public function update(Request $request, UnidadClinica $unidad_clinica)
    {
        $request->validate([
            'nombreUnidad' => 'required|string|max:45',
            'idCentroSalud' => 'required|exists:centro_salud,idCentroSalud',
        ]);

        $unidad_clinica->update($request->all());

        return response()->json(['success' => true, 'message' => 'Unidad Clínica actualizada exitosamente.']);
    }

    public function destroy(UnidadClinica $unidad_clinica)
    {
        $unidad_clinica->delete();

        return response()->json(['success' => true, 'message' => 'Unidad Clínica eliminada exitosamente.']);
    }
}
