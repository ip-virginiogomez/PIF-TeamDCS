<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CentroFormadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $centrosFormador = [
            [
                'nombreCentroFormador' => 'Universidad San Sebastián',
                'idTipoCentroFormador' => 1,
                'fechaCreacion' => now(),
            ],
            [
                'nombreCentroFormador' => 'Universidad de Concepción',
                'idTipoCentroFormador' => 1,
                'fechaCreacion' => now(),
            ],
            [
                'nombreCentroFormador' => 'Universidad del Desarrollo',
                'idTipoCentroFormador' => 1,
                'fechaCreacion' => now(),
            ],
            [
                'nombreCentroFormador' => 'Universidad Católica de la Santísima Concepción',
                'idTipoCentroFormador' => 1,
                'fechaCreacion' => now(),
            ],
            [
                'nombreCentroFormador' => 'Universidad Santo Tomás',
                'idTipoCentroFormador' => 1,
                'fechaCreacion' => now(),
            ],
            [
                'nombreCentroFormador' => 'Instituto Profesional AIEP',
                'idTipoCentroFormador' => 2,
                'fechaCreacion' => now(),
            ],
            [
                'nombreCentroFormador' => 'Instituto Profesional Dr. Virginio Gomez',
                'idTipoCentroFormador' => 2,
                'fechaCreacion' => now(),
            ],
            [
                'nombreCentroFormador' => 'Liceo Técnico Juanita Fernández',
                'idTipoCentroFormador' => 3,
                'fechaCreacion' => now(),
            ],
        ];
        foreach ($centrosFormador as $centro) {
            \App\Models\CentroFormador::create($centro);
        }
    }
}
