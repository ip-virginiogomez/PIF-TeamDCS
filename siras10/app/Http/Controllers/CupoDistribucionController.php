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

        $distribuciones = CupoDistribucion::where('idCupoOferta', $oferta->idCupoOferta)
            ->with([
                'sedeCarrera.sede.centroFormador', // Carga el Centro Formador
                'sedeCarrera.carrera',             // Carga la Carrera Base
            ])
            ->get();

        $cuposRestantes = $this->_recalcularCupos($oferta->idCupoOferta);

        // ====================================================================
        // ¡AQUÍ ESTÁ LA SOLUCIÓN!
        // ====================================================================
        $sedesCarreras = SedeCarrera::with('sede.centroFormador', 'carrera')
            // Filtra la lista para que solo muestre Sede/Carreras
            // que coincidan con la Carrera Base de la Oferta.
            ->where('idCarrera', $oferta->idCarrera) // <-- ¡ESTA ES LA LÍNEA AÑADIDA!
            ->orderBy('idSedeCarrera')
            ->get();
        // ====================================================================

        if ($request->ajax()) {
            return view('cupo-distribucion._tabla', compact('distribuciones', 'oferta'));
        }

        return view('cupo-distribucion.index', compact(
            'oferta',
            'distribuciones',
            'cuposRestantes',
            'sedesCarreras' // Esta variable ahora solo tiene las opciones filtradas
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

    public function edit($id)
    {
        try {
            // Buscamos la distribución por su ID
            $distribucion = CupoDistribucion::findOrFail($id);

            // Retornamos el modelo como JSON
            return response()->json($distribucion);

        } catch (\Exception $e) {
            // Si no lo encuentra, devuelve un error 404
            return response()->json(['error' => 'Registro no encontrado.'], 404);
        }
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

    public function destroy($id)
    {
        try {
            // 1. Encontrar el registro antes de borrarlo
            $distribucion = CupoDistribucion::findOrFail($id);
            // 2. Guardar el idCupoOferta antes de borrar
            $idOferta = $distribucion->idCupoOferta;

            // 3. Borrar el registro
            $distribucion->delete();

            // 4. Recalcular usando la variable guardada
            $cuposRestantes = $this->_recalcularCupos($idOferta);

            // 5. Devolver la respuesta correcta
            return response()->json([
                'message' => 'Registro eliminado con éxito.',
                'cuposRestantes' => $cuposRestantes,
            ]);

        } catch (\Exception $e) {
            // Manejar cualquier error
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
