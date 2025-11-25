<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Docente;
use App\Models\CupoOferta;
use App\Models\Usuario;
use App\Models\CentroFormador;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAlumnos = Alumno::count();
        $totalDocentes = Docente::count();
        $totalCupos = CupoOferta::count();
        $totalUsuarios = Usuario::count();
        $totalCentrosFormadores = CentroFormador::count();

        // Datos para gráfico: Cupos ofertados por carrera
        $cuposPorCarrera = \App\Models\Carrera::withCount(['cupoOfertas as total_cupos' => function ($query) {
            $query->select(\DB::raw('COALESCE(SUM(cantCupos),0)'));
        }])->get();

        $cuposPorCarreraLabels = $cuposPorCarrera->pluck('nombreCarrera');
        $cuposPorCarreraData = $cuposPorCarrera->pluck('total_cupos');

        return view('dashboard', [
            'totalAlumnos' => $totalAlumnos,
            'totalDocentes' => $totalDocentes,
            'totalCupos' => $totalCupos,
            'totalUsuarios' => $totalUsuarios,
            'totalCentrosFormadores' => $totalCentrosFormadores,
            'cuposPorCarreraLabels' => $cuposPorCarreraLabels,
            'cuposPorCarreraData' => $cuposPorCarreraData,
        ]);
    }
}
