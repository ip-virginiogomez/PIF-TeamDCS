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
        $columnasDisponibles = ['idUnidadClinica', 'nombreUnidad', 'centroSalud.nombreCentro', 'fechaCreacion'];
        $sortBy = $request->get('sort_by', 'idUnidadClinica');
        $sortDirection = $request->get('sort_direction', 'desc');
        $search = $request->input('search');

        if (! in_array($sortBy, $columnasDisponibles)) {
            $sortBy = 'idUnidadClinica';
        }

        $query = UnidadClinica::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombreUnidad', 'like', "%{$search}%")
                    ->orWhereHas('centroSalud', function ($q2) use ($search) {
                        $q2->where('nombreCentro', 'like', "%{$search}%");
                    });
            });
        }

        if (strpos($sortBy, '.') !== false) {
            [$tableRelacion, $columna] = explode('.', $sortBy);
            if ($tableRelacion === 'centroSalud') {
                $query->join('centro_salud', 'unidad_clinica.idCentroSalud', '=', 'centro_salud.idCentroSalud')
                    ->orderBy('centro_salud.'.$columna, $sortDirection)
                    ->select('unidad_clinica.*');
            }
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        $unidadesClinicas = $query->with('centroSalud')->paginate(10);

        if ($request->ajax()) {
            return View::make('unidad-clinicas._tabla', [
                'unidadesClinicas' => $unidadesClinicas,
                'sortBy' => $sortBy,
                'sortDirection' => $sortDirection,
            ])->render();
        }

        // Cargamos los Centros de Salud para el <select> del modal
        $centrosSalud = CentroSalud::orderBy('nombreCentro')->get();

        return view('unidad-clinicas.index', [
            'unidadesClinicas' => $unidadesClinicas,
            'centrosSalud' => $centrosSalud,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
        ]);
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
