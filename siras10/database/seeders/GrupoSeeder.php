<?php

namespace Database\Seeders;

use App\Models\Grupo;
use Illuminate\Database\Seeder;

class GrupoSeeder extends Seeder
{
    public function run(): void
    {
        $grupos = [
            [
                'idCupoDistribucion' => 1,
                'idDocenteCarrera' => 14,
                'idAsignatura' => 1,
                'idAsignatura' => 1,
                'fechaCreacion' => now(),
                'nombreGrupo' => 'Grupo 1',
                'fechaInicio' => '2025-09-01',
                'fechaFin' => '2025-09-15',
            ],
        ];
        foreach ($grupos as $grupo) {
            \App\Models\Grupo::create($grupo);
        }
    }
}
