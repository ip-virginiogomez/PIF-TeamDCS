<?php

namespace App\Http\Controllers;

use App\Models\CentroFormador;
use App\Models\CentroSalud;
use App\Models\Usuario;
use Illuminate\Http\Request;

class AsignacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:asignaciones.read')->only('index', 'getCentrosCampoClinico', 'getCentrosRad');
        $this->middleware('permission:asignaciones.create')->only('asignarCentroCoordinador', 'asignarCentroRad');
        $this->middleware('permission:asignaciones.delete')->only('quitarCentroCoordinador', 'quitarCentroRad');
    }

    public function index(Request $request)
    {
        $grupo = $request->get('grupo', 'campo_clinico');

        if ($grupo === 'rad') {
            $coordinadores = Usuario::role('Técnico RAD')->get();
            $titulo = 'Coordinadores Técnicos RAD';
        } else {
            $grupo = 'campo_clinico';
            $coordinadores = Usuario::role('Coordinador Campo Clínico')->get();
            $titulo = 'Coordinadores de Campo Clínico';
        }

        return view('asignaciones.index', compact('coordinadores', 'grupo', 'titulo'));
    }

    public function getCentrosCampoClinico(Usuario $usuario)
    {
        $idsAsignados = $usuario->centrosFormadores()->pluck('centro_formador.idCentroFormador');
        $asignados = CentroFormador::whereIn('idCentroFormador', $idsAsignados)->get();
        $disponibles = CentroFormador::whereNotIn('idCentroFormador', $idsAsignados)->get();

        return response()->json([
            'asignados' => $asignados,
            'disponibles' => $disponibles,
            'idKey' => 'idCentroFormador',
            'nameKey' => 'nombreCentroFormador',
        ]);
    }

    public function asignarCentroCampoClinico(Request $request, Usuario $usuario)
    {
        $request->validate(['centro_id' => 'required|integer']);

        $centroId = $request->input('centro_id');

        if ($usuario->centrosFormadores()->find($centroId)) {
            return response()->json(['success' => false, 'message' => 'Este centro ya está asignado.'], 409);
        }

        $usuario->centrosFormadores()->attach($centroId);

        return response()->json(['success' => true]);
    }

    public function quitarCentroCoordinador(Usuario $usuario, CentroFormador $centro)
    {
        $usuario->centrosFormadores()->detach($centro->idCentroFormador);

        return response()->json(['success' => true]);
    }

    public function getCentrosRad(Usuario $usuario)
    {
        $idsAsignados = $usuario->centroSalud()->pluck('centro_salud.idCentroSalud');
        $asignados = CentroSalud::whereIn('idCentroSalud', $idsAsignados)->get();
        $disponibles = CentroSalud::whereNotIn('idCentroSalud', $idsAsignados)->get();

        return response()->json([
            'asignados' => $asignados,
            'disponibles' => $disponibles,
            'idKey' => 'idCentroSalud',
            'nameKey' => 'nombreCentro',
        ]);
    }

    public function asignarCentroRad(Request $request, Usuario $usuario)
    {
        $request->validate(['centro_id' => 'required|integer']);

        $centroId = $request->input('centro_id');

        if ($usuario->centroSalud()->find($centroId)) {
            return response()->json(['success' => false, 'message' => 'Este centro ya está asignado.'], 409);
        }

        $usuario->centroSalud()->attach($centroId);

        return response()->json(['success' => true]);
    }

    public function quitarCentroRad(Usuario $usuario, CentroSalud $centro)
    {
        $usuario->centroSalud()->detach($centro->idCentroSalud);

        return response()->json(['success' => true]);
    }
}
