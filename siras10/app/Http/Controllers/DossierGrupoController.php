<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Grupo;
use Illuminate\Http\Request;

class DossierGrupoController extends Controller
{
    public function index($idGrupo)
    {
        $grupo = Grupo::with([
            'asignatura',
            'docenteCarrera.docente',
            'cupoDistribucion.cupoDemanda.sedeCarrera.sede.centroFormador',
            'cupoDistribucion.cupoOferta.unidadClinica.centroSalud',
            'cupoDistribucion.cupoOferta.tipoPractica',
            'alumnos',
        ])->findOrFail($idGrupo);

        return view('grupos.dossier', compact('grupo'));
    }

    public function buscarAlumnos(Request $request, Grupo $grupo)
    {
        $term = $request->input('query');

        if (! $term) {
            return response()->json([]);
        }

        // 1. Buscamos alumnos por RUN o Nombre
        $alumnos = Alumno::where(function ($q) use ($term) {
            $q->where('runAlumno', 'like', "%{$term}%")
                ->orWhere('nombres', 'like', "%{$term}%")
                ->orWhere('apellidoPaterno', 'like', "%{$term}%");
        })
                    // 2. Excluimos los que ya están en ESTE grupo
            ->whereDoesntHave('grupos', function ($q) use ($grupo) {
                // Usamos la tabla del modelo relacionado (Grupo) para evitar confusiones
                $q->where('Grupo.idGrupo', $grupo->idGrupo);
            })
            ->limit(10)
            ->get();

        return response()->json($alumnos);
    }

    public function agregarAlumno(Request $request, Grupo $grupo)
    {
        $request->validate([
            'runAlumno' => 'required|exists:Alumno,runAlumno',
        ]);

        // Guardamos en la tabla DossierGrupo
        $grupo->alumnos()->syncWithoutDetaching([$request->runAlumno]);

        return response()->json(['success' => true, 'message' => 'Alumno inscrito correctamente.']);
    }

    public function eliminarAlumno(Grupo $grupo, Alumno $alumno)
    {
        $grupo->alumnos()->detach($alumno->runAlumno);

        return response()->json(['success' => true, 'message' => 'Alumno eliminado del grupo.']);
    }
}
