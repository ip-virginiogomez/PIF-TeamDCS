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
        // Aplicaremos los permisos cuando los creemos en el seeder
        // $this->middleware('can:cupo-ofertas.read')->only('index');
        // ...
    }

    public function index(Request $request)
    {
        // Cargamos las relaciones para mostrar la información en la tabla
        $cupoOfertas = CupoOferta::with(['periodo', 'unidadClinica', 'tipoPractica', 'carrera'])->paginate(10);

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
            'cantCupos' => 'required|integer|min:1',
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
            'cantCupos' => 'required|integer|min:1',
            'fechaEntrada' => 'required|date',
            'fechaSalida' => 'required|date|after_or_equal:fechaEntrada',
            'horaEntrada' => 'required',
            'horaSalida' => 'required',
        ]);

        $cupoOferta->update($request->all());

        return response()->json(['success' => true, 'message' => 'Oferta de cupo actualizada exitosamente.']);
    }

    public function destroy(CupoOferta $cupoOferta)
    {
        $cupoOferta->delete();

        return response()->json(['success' => true, 'message' => 'Oferta de cupo eliminada exitosamente.']);
    }
}
