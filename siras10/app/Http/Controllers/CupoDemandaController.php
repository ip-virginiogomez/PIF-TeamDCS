<?php

namespace App\Http\Controllers;

use App\Models\CupoDemanda;
use App\Models\Periodo;
use App\Models\SedeCarrera;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CupoDemandaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:cupo-demandas.read')->only('index');
        $this->middleware('permission:cupo-demandas.create')->only('store');
        $this->middleware('permission:cupo-demandas.update')->only('update');
        $this->middleware('permission:cupo-demandas.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $periodos = Periodo::orderBy('nombrePeriodo', 'desc')->get();
        $periodoId = $request->query('periodo_id', $periodos->first()->idPeriodo ?? null);

        $query = CupoDemanda::with(['periodo', 'sedeCarrera.sede.centroFormador', 'sedeCarrera.carrera']);

        if ($periodoId) {
            $query->where('idPeriodo', $periodoId);
        }

        $demandas = $query->get();
        $sedesCarreras = SedeCarrera::with(['sede.centroFormador', 'carrera'])->get();

        if ($request->ajax()) {
            return view('cupo-demanda._tabla', compact('demandas'));
        }

        return view('cupo-demanda.index', compact('demandas', 'periodos', 'periodoId', 'sedesCarreras'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idPeriodo' => 'required|exists:periodo,idPeriodo',
            'idSedeCarrera' => [
                'required',
                'exists:sede_carrera,idSedeCarrera',
                Rule::unique('cupo_demanda')->where(function ($query) use ($request) {
                    return $query->where('idPeriodo', $request->idPeriodo);
                }),
            ],
            'cuposSolicitados' => 'required|integer|min:1',
        ]);

        CupoDemanda::create($request->all());

        return response()->json(['message' => 'Demanda creada correctamente.']);
    }

    public function edit($id)
    {
        $demanda = CupoDemanda::findOrFail($id);
        return response()->json($demanda);
    }

    public function update(Request $request, $id)
    {
        $demanda = CupoDemanda::findOrFail($id);

        $request->validate([
            'cuposSolicitados' => 'required|integer|min:1',
        ]);

        $demanda->update($request->only('cuposSolicitados'));

        return response()->json(['message' => 'Demanda actualizada correctamente.']);
    }

    public function destroy($id)
    {
        $demanda = CupoDemanda::findOrFail($id);
        $demanda->delete();

        return response()->json(['message' => 'Demanda eliminada correctamente.']);
    }
}
