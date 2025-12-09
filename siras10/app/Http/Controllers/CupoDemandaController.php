<?php

namespace App\Http\Controllers;

use App\Models\CupoDemanda;
use App\Models\Periodo;
use App\Models\SedeCarrera;
use App\Models\Sede;
use App\Models\Carrera;
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
        $periodos = Periodo::orderBy('Año', 'desc')->get();
        
        // Priorizar el periodo del año actual
        $currentYear = date('Y');
        $defaultPeriod = $periodos->first(function($periodo) use ($currentYear) {
            return $periodo->Año == $currentYear;
        }) ?? $periodos->first();
        
        $periodoId = $request->query('periodo_id', $defaultPeriod->idPeriodo ?? null);
        $sortBy = $request->get('sort_by');
        $sortDirection = $request->get('sort_direction', 'asc');

        $query = CupoDemanda::with(['periodo', 'sedeCarrera.sede.centroFormador', 'sedeCarrera.carrera'])
            ->leftJoin('asignatura', function($join) {
                $join->on('cupo_demanda.idSedeCarrera', '=', 'asignatura.idSedeCarrera')
                     ->on('cupo_demanda.asignatura', '=', 'asignatura.nombreAsignatura')
                     ->whereNull('asignatura.deleted_at');
            })
            ->leftJoin('tipo_practica', function($join) {
                $join->on('asignatura.idTipoPractica', '=', 'tipo_practica.idTipoPractica')
                     ->whereNull('tipo_practica.deleted_at');
            })
            ->select('cupo_demanda.*', 'tipo_practica.nombrePractica as nombreTipoPractica');

        if ($periodoId) {
            $query->where('cupo_demanda.idPeriodo', $periodoId);
        }

        // Ordenamiento
        if ($sortBy) {
            if ($sortBy === 'sede') {
                $query->join('sede_carrera', 'cupo_demanda.idSedeCarrera', '=', 'sede_carrera.idSedeCarrera')
                      ->join('sede', 'sede_carrera.idSede', '=', 'sede.idSede')
                      ->orderBy('sede.nombreSede', $sortDirection);
            } elseif ($sortBy === 'carrera') {
                $query->join('sede_carrera', 'cupo_demanda.idSedeCarrera', '=', 'sede_carrera.idSedeCarrera')
                      ->join('carrera', 'sede_carrera.idCarrera', '=', 'carrera.idCarrera')
                      ->orderBy('carrera.nombreCarrera', $sortDirection);
            } elseif ($sortBy === 'asignatura') {
                $query->orderBy('cupo_demanda.asignatura', $sortDirection);
            } elseif ($sortBy === 'tipo_practica') {
                $query->orderBy('tipo_practica.nombrePractica', $sortDirection);
            } elseif ($sortBy === 'cupos') {
                $query->orderBy('cupo_demanda.cuposSolicitados', $sortDirection);
            }
        } else {
            $query->orderBy('cupo_demanda.created_at', 'desc');
        }

        $demandas = $query->paginate(10);
        $sedesCarreras = SedeCarrera::with(['sede.centroFormador', 'carrera'])->get();

        if ($request->ajax()) {
            return view('cupo-demanda._tabla', compact('demandas', 'sortBy', 'sortDirection'));
        }

        return view('cupo-demanda.index', compact('demandas', 'periodos', 'periodoId', 'sedesCarreras', 'sortBy', 'sortDirection'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idPeriodo' => 'required|exists:periodo,idPeriodo',
            'demandas' => 'required|array|min:1',
            'demandas.*.idSedeCarrera' => 'required|exists:sede_carrera,idSedeCarrera',
            'demandas.*.asignatura' => 'required|string',
            'demandas.*.cuposSolicitados' => 'required|integer|min:1',
        ]);

        foreach ($request->demandas as $demanda) {
            CupoDemanda::updateOrCreate(
                [
                    'idPeriodo' => $request->idPeriodo,
                    'idSedeCarrera' => $demanda['idSedeCarrera'],
                    'asignatura' => $demanda['asignatura']
                ],
                [
                    'cuposSolicitados' => $demanda['cuposSolicitados']
                ]
            );
        }

        return response()->json(['message' => 'Demandas guardadas correctamente.']);
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
            'idSedeCarrera' => 'required|exists:sede_carrera,idSedeCarrera',
            'asignatura' => 'required|string',
            'cuposSolicitados' => 'required|integer|min:1',
        ]);

        $demanda->update($request->only('idSedeCarrera', 'asignatura', 'cuposSolicitados'));

        return response()->json(['message' => 'Demanda actualizada correctamente.']);
    }

    public function destroy($id)
    {
        $demanda = CupoDemanda::findOrFail($id);
        $demanda->delete();

        return response()->json(['message' => 'Demanda eliminada correctamente.']);
    }

    public function getAsignaturasBySedeCarrera($idSedeCarrera)
    {
        $sedeCarrera = SedeCarrera::with('asignaturas')->findOrFail($idSedeCarrera);
        return response()->json($sedeCarrera->asignaturas);
    }
}
