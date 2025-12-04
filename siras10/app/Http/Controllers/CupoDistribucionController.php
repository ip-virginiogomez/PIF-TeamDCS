<?php

namespace App\Http\Controllers;

use App\Models\CupoDistribucion;
use App\Models\CupoOferta;
use App\Models\SedeCarrera;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CupoDistribucionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:cupo-distribuciones.read')->only('index');
        $this->middleware('permission:cupo-distribuciones.create')->only('create', 'store');
        $this->middleware('permission:cupo-distribuciones.update')->only('edit', 'update');
        $this->middleware('permission:cupo-distribuciones.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $ofertaId = $request->query('oferta_id');
        if (! $ofertaId) {
            return redirect()->route('cupo-ofertas.index')->with('error', 'Debe seleccionar una oferta.');
        }

        $oferta = CupoOferta::findOrFail($ofertaId);
        $oferta->load('periodo', 'unidadClinica.centroSalud', 'carrera', 'tipoPractica');

        // Capturar parámetros de ordenamiento
        $sortBy = $request->query('sort_by', 'idCupoDistribucion');
        $sortDirection = $request->query('sort_direction', 'desc');

        // Aplicar ordenamiento
        $query = CupoDistribucion::where('idCupoOferta', $oferta->idCupoOferta)
            ->with([
                'sedeCarrera.sede.centroFormador',
                'sedeCarrera.carrera',
            ]);

        // Ordenar según la columna seleccionada
        switch ($sortBy) {
            case 'sedeCarrera.sede.centroFormador.nombreCentroFormador':
                $query->join('sede_carrera', 'cupo_distribucion.idSedeCarrera', '=', 'sede_carrera.idSedeCarrera')
                    ->join('sede', 'sede_carrera.idSede', '=', 'sede.idSede')
                    ->join('centro_formador', 'sede.idCentroFormador', '=', 'centro_formador.idCentroFormador')
                    ->orderBy('centro_formador.nombreCentroFormador', $sortDirection)
                    ->select('cupo_distribucion.*');
                break;
            case 'sedeCarrera.carrera.nombreCarrera':
                $query->join('sede_carrera', 'cupo_distribucion.idSedeCarrera', '=', 'sede_carrera.idSedeCarrera')
                    ->join('carrera', 'sede_carrera.idCarrera', '=', 'carrera.idCarrera')
                    ->orderBy('carrera.nombreCarrera', $sortDirection)
                    ->select('cupo_distribucion.*');
                break;
            default:
                $query->orderBy($sortBy, $sortDirection);
                break;
        }

        $distribuciones = $query->get();

        $cuposRestantes = $this->_recalcularCupos($oferta->idCupoOferta);

        // Filtrar Sede/Carreras que coincidan con la Carrera Base de la Oferta
        $sedesCarreras = SedeCarrera::with('sede.centroFormador', 'carrera')
            ->where('idCarrera', $oferta->idCarrera)
            ->orderBy('idSedeCarrera')
            ->get();

        if ($request->ajax()) {
            return view('cupo-distribucion._tabla', compact('distribuciones', 'oferta', 'sortBy', 'sortDirection'));
        }

        return view('cupo-distribucion.index', compact(
            'oferta',
            'distribuciones',
            'cuposRestantes',
            'sedesCarreras',
            'sortBy',
            'sortDirection'
        ));
    }

    public function store(Request $request)
    {
        $oferta = CupoOferta::findOrFail($request->idCupoOferta);
        $cuposRestantes = $this->_recalcularCupos($oferta->idCupoOferta);

        $datosValidados = $request->validate([
            'idCupoOferta' => 'required|exists:cupo_oferta,idCupoOferta',
            'idSedeCarrera' => [
                'required',
                'exists:sede_carrera,idSedeCarrera',
                Rule::unique('cupo_distribucion')->where(fn ($query) => $query->where('idCupoOferta', $request->idCupoOferta)),
            ],
            'cantCupos' => [
                'required', 'integer', 'min:1', 'max:'.$cuposRestantes,
            ],
        ], [
            'idSedeCarrera.unique' => 'Esta Sede/Carrera ya tiene cupos asignados.',
            'cantCupos.max' => 'La cantidad no puede superar los cupos restantes (:max).',
        ]);

        CupoDistribucion::create($datosValidados);
        $nuevosRestantes = $this->_recalcularCupos($oferta->idCupoOferta);

        return response()->json(['message' => 'Distribución asignada con éxito.', 'cuposRestantes' => $nuevosRestantes]);
    }

    public function edit($id)
    {
        try {
            $distribucion = CupoDistribucion::findOrFail($id);

            return response()->json($distribucion);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Registro no encontrado.'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        // Cargar la distribución sin el scope global para evitar conflictos
        $distribucion = CupoDistribucion::withoutGlobalScopes()->findOrFail($id);

        // Cargar explícitamente la relación cupoOferta
        $distribucion->load('cupoOferta');
        $oferta = $distribucion->cupoOferta;

        if (! $oferta) {
            return response()->json(['error' => 'No se encontró la oferta de cupos asociada.'], 404);
        }

        $cuposRestantes = $this->_recalcularCupos($oferta->idCupoOferta);
        $cuposDisponiblesParaEditar = $cuposRestantes + $distribucion->cantCupos;

        $datosValidados = $request->validate([
            'idSedeCarrera' => [
                'required',
                'exists:sede_carrera,idSedeCarrera',
                Rule::unique('cupo_distribucion')->where(fn ($query) => $query->where('idCupoOferta', $distribucion->idCupoOferta))->ignore($distribucion->idCupoDistribucion, 'idCupoDistribucion'),
            ],
            'cantCupos' => [
                'required', 'integer', 'min:1', 'max:'.$cuposDisponiblesParaEditar,
            ],
        ], [
            'idSedeCarrera.unique' => 'Esta Sede/Carrera ya tiene cupos asignados.',
            'cantCupos.max' => 'La cantidad no puede superar los cupos disponibles (:max).',
        ]);

        $distribucion->update($datosValidados);
        $nuevosRestantes = $this->_recalcularCupos($oferta->idCupoOferta);

        return response()->json(['message' => 'Distribución actualizada con éxito.', 'cuposRestantes' => $nuevosRestantes]);
    }

    public function destroy($id)
    {
        try {
            $distribucion = CupoDistribucion::findOrFail($id);
            $idOferta = $distribucion->idCupoOferta;

            $distribucion->delete();

            $cuposRestantes = $this->_recalcularCupos($idOferta);

            return response()->json([
                'message' => 'Registro eliminado con éxito.',
                'cuposRestantes' => $cuposRestantes,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function _recalcularCupos(int $idCupoOferta): int
    {
        $oferta = CupoOferta::findOrFail($idCupoOferta);
        $totalOfertado = $oferta->cantCupos;
        $totalDistribuido = CupoDistribucion::where('idCupoOferta', $idCupoOferta)->sum('cantCupos');

        return $totalOfertado - $totalDistribuido;
    }
}
