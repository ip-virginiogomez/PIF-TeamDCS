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
                'nombreAsignatura' => 'Enfermería Básica',
                'idTipoPractica' => 1,
                'idSedeCarrera' => 1,
                'fechaCreacion' => now(),
                'codAsignatura' => 'ENF101',
                'Semestre' => 1,
            ],
            [
                'nombreAsignatura' => 'Cuidados del Adulto Mayor',
                'idTipoPractica' => 1,
                'idSedeCarrera' => 1,
                'fechaCreacion' => now(),
                'codAsignatura' => 'ENF201',
                'Semestre' => 2,
            ],
            [
                'nombreAsignatura' => 'Práctica Clínica I',
                'idTipoPractica' => 2,
                'idSedeCarrera' => 1,
                'fechaCreacion' => now(),
                'codAsignatura' => 'ENF301',
                'Semestre' => 3,
            ],
        ];
        foreach ($asignaturas as $asignatura) {
            Asignatura::create($asignatura);
        }
    }
}
