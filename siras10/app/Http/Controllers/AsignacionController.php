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
        $this->middleware('permission:asignaciones.delete')->only('quitarCentroCampoClinico', 'quitarCentroRad');
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
        // Obtener los centros asignados con los datos de la tabla pivote
        $asignados = $usuario->centrosFormadores;
        
        $idsAsignados = $asignados->pluck('idCentroFormador');
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
        $request->validate([
            'centro_id' => 'required|integer',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        $centroId = $request->input('centro_id');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        if ($usuario->centrosFormadores()->find($centroId)) {
            return response()->json(['success' => false, 'message' => 'Este centro ya está asignado.'], 409);
        }

        $usuario->centrosFormadores()->attach($centroId, [
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'fechaCreacion' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function quitarCentroCampoClinico(Usuario $usuario, CentroFormador $centro)
    {
        $usuario->centrosFormadores()->detach($centro->idCentroFormador);

        return response()->json(['success' => true]);
    }

    public function getCentrosRad(Usuario $usuario)
    {
        // Obtener los centros asignados con los datos de la tabla pivote
        $asignados = $usuario->centroSalud;

        $idsAsignados = $asignados->pluck('idCentroSalud');
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
        $request->validate([
            'centro_id' => 'required|integer',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        $centroId = $request->input('centro_id');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        if ($usuario->centroSalud()->find($centroId)) {
            return response()->json(['success' => false, 'message' => 'Este centro ya está asignado.'], 409);
        }

        $usuario->centroSalud()->attach($centroId, [
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'fechaCreacion' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function quitarCentroRad(Usuario $usuario, CentroSalud $centro)
    {
        $usuario->centroSalud()->detach($centro->idCentroSalud);

        return response()->json(['success' => true]);
    }
}
