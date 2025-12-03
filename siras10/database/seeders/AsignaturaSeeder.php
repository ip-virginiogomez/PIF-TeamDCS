<?php

namespace Database\Seeders;

use App\Models\Asignatura;
use Illuminate\Database\Seeder;

class AsignaturaSeeder extends Seeder
{
    public function run(): void
    {
        $asignaturas = [
            [
                'nombreAsignatura' => 'Práctico Integrado',
                'idTipoPractica' => 1,
                'idSedeCarrera' => 1,
                'fechaCreacion' => now(),
                'codAsignatura' => 'PI101',
                'Semestre' => 3,
            ],
        ];
        foreach ($asignaturas as $asignatura) {
            Asignatura::create($asignatura);
        }
    }
}
