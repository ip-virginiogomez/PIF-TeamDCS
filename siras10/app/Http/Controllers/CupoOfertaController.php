<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\CupoOferta;
use App\Models\Periodo;
use App\Models\TipoPractica;
use App\Models\UnidadClinica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CupoOfertaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:cupo-ofertas.read')->only('index');
        $this->middleware('permission:cupo-ofertas.create')->only('create', 'store');
        $this->middleware('permission:cupo-ofertas.update')->only('edit', 'update');
        $this->middleware('permission:cupo-ofertas.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $idPeriodo = $request->input('idPeriodo');
        $idTipoPractica = $request->input('idTipoPractica');
        $idCarrera = $request->input('idCarrera');

        // Cargamos las relaciones para mostrar la información en la tabla
        $query = CupoOferta::with(['periodo', 'unidadClinica.centroSalud', 'tipoPractica', 'carrera'])
            ->withSum('cupoDistribuciones', 'cantCupos');

        if ($search) {
            $query->whereHas('unidadClinica', function ($q) use ($search) {
                $q->where('nombreUnidad', 'like', "%{$search}%")
                  ->orWhereHas('centroSalud', function ($q2) use ($search) {
                      $q2->where('nombreCentro', 'like', "%{$search}%");
                  });
            });
        }

        if ($idPeriodo) {
            $query->where('idPeriodo', $idPeriodo);
        }

        if ($idTipoPractica) {
            $query->where('idTipoPractica', $idTipoPractica);
        }

        if ($idCarrera) {
            $query->where('idCarrera', $idCarrera);
        }

        $cupoOfertas = $query->orderBy('idCupoOferta', 'desc')
            ->paginate(10);

        // Si es una petición AJAX, devolvemos solo la tabla
        if ($request->ajax()) {
            return View::make('cupo-ofertas._tabla', compact('cupoOfertas'))->render();
        }

        // Para la carga inicial, también necesitamos los datos para los selectores del modal
        $periodos = Periodo::all();
        $unidadesClinicas = UnidadClinica::all();
        $tiposPractica = TipoPractica::all();
        $carreras = Carrera::all();

        return view('cupo-ofertas.index', compact('cupoOfertas', 'periodos', 'unidadesClinicas', 'tiposPractica', 'carreras'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idPeriodo' => 'required|exists:periodo,idPeriodo',
            'idUnidadClinica' => 'required|exists:unidad_clinica,idUnidadClinica',
            'idTipoPractica' => 'required|exists:tipo_practica,idTipoPractica',
            'idCarrera' => 'required|exists:carrera,idCarrera',
            'cantCupos' => 'required|integer|min:1|max:99',
            'fechaEntrada' => 'required|date',
            'fechaSalida' => 'required|date|after_or_equal:fechaEntrada',
            'horaEntrada' => 'required',
            'horaSalida' => 'required',
        ]);

        CupoOferta::create($request->all());

        return response()->json(['success' => true, 'message' => 'Oferta de cupo creada exitosamente.']);
    }

    public function edit(CupoOferta $cupoOferta)
    {
        return response()->json($cupoOferta);
    }

    public function update(Request $request, CupoOferta $cupoOferta)
    {
        $request->validate([
            'idPeriodo' => 'required|exists:periodo,idPeriodo',
            'idUnidadClinica' => 'required|exists:unidad_clinica,idUnidadClinica',
            'idTipoPractica' => 'required|exists:tipo_practica,idTipoPractica',
            'idCarrera' => 'required|exists:carrera,idCarrera',
            'cantCupos' => 'required|integer|min:1|max:99',
            'fechaEntrada' => 'required|date',
            'fechaSalida' => 'required|date|after_or_equal:fechaEntrada',
            'horaEntrada' => 'required',
            'horaSalida' => 'required',
        ]);

        // Validar que la nueva cantidad de cupos no sea menor a los cupos ya distribuidos
        $cuposDistribuidos = $cupoOferta->cupoDistribuciones()->sum('cantCupos');
        $nuevosCupos = $request->input('cantCupos');

        if ($nuevosCupos < $cuposDistribuidos) {
            return response()->json([
                'success' => false,
                'message' => "No se puede reducir la cantidad de cupos a {$nuevosCupos} porque ya hay {$cuposDistribuidos} cupos distribuidos.",
                'errors' => [
                    'cantCupos' => ["Ya existen {$cuposDistribuidos} cupos asignados. No puede ofertar menos cupos de los ya distribuidos."],
                ],
            ], 422);
        }

        $cupoOferta->update($request->all());

        return response()->json(['success' => true, 'message' => 'Oferta de cupo actualizada exitosamente.']);
    }

    public function destroy(CupoOferta $cupoOferta)
    {
        $cupoOferta->delete();

        return response()->json(['success' => true, 'message' => 'Oferta de cupo eliminada exitosamente.']);
    }
}
