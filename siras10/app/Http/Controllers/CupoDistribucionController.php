<?php

namespace App\Http\Controllers;

use App\Models\CupoDistribucion;
use App\Models\CupoOferta;
// ¡CAMBIO! Necesitamos el modelo SedeCarrera (o el que corresponda)
use App\Models\SedeCarrera; // Asegúrate que este es el modelo correcto
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CupoDistribucionController extends Controller
{
    public function __construct()
    {
        // ... (Los middleware can están bien) ...
    }

    public function index(Request $request)
    {
        $ofertaId = $request->query('oferta_id');
        if (! $ofertaId) {
            return redirect()->route('cupo-ofertas.index')->with('error', 'Debe seleccionar una oferta.');
        }

        $oferta = CupoOferta::findOrFail($ofertaId);
        $oferta->load('periodo', 'unidadClinica.centroSalud', 'carrera', 'tipoPractica');

        // ¡CAMBIO AQUÍ! Carga la relación correcta (asumiendo que existe en el modelo CupoDistribucion)
        $distribuciones = CupoDistribucion::where('idCupoOferta', $oferta->idCupoOferta)
            ->with('sedeCarrera.sede.centroFormador', 'sedeCarrera.carrera') // Ajusta según tus relaciones
            ->orderBy('idCupoDistribucion', 'desc')
            ->get();

        $cuposRestantes = $this->_recalcularCupos($oferta->idCupoOferta);

        $sedesCarreras = SedeCarrera::with('sede.centroFormador', 'carrera')
            ->orderBy('idSedeCarrera')
            ->get();

        if ($request->ajax()) {
            return view('cupo-distribucion._tabla', compact('distribuciones', 'oferta'));
        }

        return view('cupo-distribucion.index', compact(
            'oferta',
            'distribuciones',
            'cuposRestantes',
            'sedesCarreras'
        ));
    }

    public function store(Request $request)
    {
        $oferta = CupoOferta::findOrFail($request->idCupoOferta);
        $cuposRestantes = $this->_recalcularCupos($oferta->idCupoOferta);

        // ¡CAMBIO AQUÍ! Validar 'idSedeCarrera'
        $datosValidados = $request->validate([
            'idCupoOferta' => 'required|exists:cupo_oferta,idCupoOferta',
            'idSedeCarrera' => [ // ¡CAMBIO!
                'required',
                'exists:sede_carrera,idSedeCarrera', // ¡CAMBIO! Tabla y columna
                Rule::unique('cupo_distribucion')->where(fn ($query) => $query->where('idCupoOferta', $request->idCupoOferta)),
            ],
            'cantCupos' => [
                'required', 'integer', 'min:1', 'max:'.$cuposRestantes,
            ],
        ], [
            // ¡CAMBIO! Mensaje de error
            'idSedeCarrera.unique' => 'Esta Sede/Carrera ya tiene cupos asignados.',
            'cantCupos.max' => 'La cantidad no puede superar los cupos restantes (:max).',
        ]);

        CupoDistribucion::create($datosValidados);
        $nuevosRestantes = $this->_recalcularCupos($oferta->idCupoOferta);

        return response()->json(['message' => 'Distribución asignada con éxito.', 'cuposRestantes' => $nuevosRestantes]);
    }

    public function edit(CupoDistribucion $distribucion)
    {
        // Esto debería funcionar si el modelo tiene 'idSedeCarrera'
        return response()->json($distribucion);
    }

    public function update(Request $request, CupoDistribucion $distribucion)
    {
        $oferta = $distribucion->cupoOferta;
        $cuposRestantes = $this->_recalcularCupos($oferta->idCupoOferta);
        $cuposDisponiblesParaEditar = $cuposRestantes + $distribucion->cantCupos;

        // ¡CAMBIO AQUÍ! Validar 'idSedeCarrera'
        $datosValidados = $request->validate([
            'idSedeCarrera' => [ // ¡CAMBIO!
                'required',
                'exists:sede_carrera,idSedeCarrera', // ¡CAMBIO!
                Rule::unique('cupo_distribucion')->where(fn ($query) => $query->where('idCupoOferta', $distribucion->idCupoOferta))->ignore($distribucion->idCupoDistribucion, 'idCupoDistribucion'),
            ],
            'cantCupos' => [
                'required', 'integer', 'min:1', 'max:'.$cuposDisponiblesParaEditar,
            ],
        ], [
            // ¡CAMBIO! Mensaje de error
            'idSedeCarrera.unique' => 'Esta Sede/Carrera ya tiene cupos asignados.',
            'cantCupos.max' => 'La cantidad no puede superar los cupos disponibles (:max).',
        ]);

        $distribucion->update($datosValidados);
        $nuevosRestantes = $this->_recalcularCupos($oferta->idCupoOferta);

        return response()->json(['message' => 'Distribución actualizada con éxito.', 'cuposRestantes' => $nuevosRestantes]);
    }

    // ... (destroy y _recalcularCupos están bien) ...
    public function destroy(CupoDistribucion $distribucion)
    {
        $idOfertaPadre = $distribucion->idCupoOferta;
        $distribucion->delete();
        $nuevosRestantes = $this->_recalcularCupos($idOfertaPadre);

        return response()->json(['message' => 'Distribución eliminada con éxito.', 'cuposRestantes' => $nuevosRestantes]);
    }

    private function _recalcularCupos(int $idCupoOferta): int
    {
        $oferta = CupoOferta::findOrFail($idCupoOferta);
        $totalOfertado = $oferta->cantCupos;
        $totalDistribuido = CupoDistribucion::where('idCupoOferta', $idCupoOferta)->sum('cantCupos');

        return $totalOfertado - $totalDistribuido;
    }
}
