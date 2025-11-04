<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario; 
use App\Models\CentroFormador;

class AsignacionController extends Controller
{
    public function index()
    {
        $coordinadores = Usuario::role('Coordinador Campo Clinico')->get();

        return view('asignacion.index', compact('coordinadores'));
    }

    public function getCentrosDeCoordinador(Usuario $usuario)
    {
        // 1. Obtiene los IDs de los centros YA asignados
        $idsAsignados = $usuario->centrosFormadores()
            ->pluck('centro_formador.idCentroFormador');

        // 2. Obtiene los modelos completos de los centros asignados
        $asignados = CentroFormador::whereIn('idCentroFormador', $idsAsignados)->get();

        // 3. Obtiene los centros que aún NO están asignados (para el <select>)
        $disponibles = CentroFormador::whereNotIn('idCentroFormador', $idsAsignados)->get();

        return response()->json([
            'asignados' => $asignados,
            'disponibles' => $disponibles,
        ]);
    }

    public function asignarCentro(Request $request, Usuario $usuario)
    {
        $request->validate(['centro_id' => 'required|integer']);

        $centroId = $request->input('centro_id');

        if ($usuario->centrosFormadores()->find($centroId)) {
            return response()->json(['success' => false, 'message' => 'Este centro ya está asignado.'], 409);
        }

        $usuario->centrosFormadores()->attach($centroId);

        return response()->json(['success' => true]);
    }

    public function quitarCentro(Usuario $usuario, CentroFormador $centro)
    {
        $usuario->centrosFormadores()->detach($centro->idCentroFormador);

        return response()->json(['success' => true]);
    }
}

