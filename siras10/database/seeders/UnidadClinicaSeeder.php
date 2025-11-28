<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UnidadClinicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $unidadesClinicas = [
            [
                'nombreUnidad' => 'Procedimientos',
                'fechaCreacion' => now(),
                'idCentroSalud' => 1,
            ],
            [
                'nombreUnidad' => 'Sala ERA',
                'fechaCreacion' => now(),
                'idCentroSalud' => 1,
            ],
            [
                'nombreUnidad' => 'Sala IRA',
                'fechaCreacion' => now(),
                'idCentroSalud' => 1,
            ],
            [
                'nombreUnidad' => 'Dental',
                'fechaCreacion' => now(),
                'idCentroSalud' => 1,
            ],
            [
                'nombreUnidad' => 'Rehabilitación',
                'fechaCreacion' => now(),
                'idCentroSalud' => 1,
            ],
            [
                'nombreUnidad' => 'Procedimientos',
                'fechaCreacion' => now(),
                'idCentroSalud' => 2,
            ],
            [
                'nombreUnidad' => 'Sala ERA',
                'fechaCreacion' => now(),
                'idCentroSalud' => 2,
            ],
            [
                'nombreUnidad' => 'Sala IRA',
                'fechaCreacion' => now(),
                'idCentroSalud' => 2,
            ],
            [
                'nombreUnidad' => 'Dental',
                'fechaCreacion' => now(),
                'idCentroSalud' => 2,
            ],
            [
                'nombreUnidad' => 'Rehabilitación',
                'fechaCreacion' => now(),
                'idCentroSalud' => 2,
            ],
            [
                'nombreUnidad' => 'Procedimientos',
                'fechaCreacion' => now(),
                'idCentroSalud' => 3,
            ],
            [
                'nombreUnidad' => 'Sala ERA',
                'fechaCreacion' => now(),
                'idCentroSalud' => 3,
            ],
            [
                'nombreUnidad' => 'Sala IRA',
                'fechaCreacion' => now(),
                'idCentroSalud' => 3,
            ],
            [
                'nombreUnidad' => 'Dental',
                'fechaCreacion' => now(),
                'idCentroSalud' => 3,
            ],
            [
                'nombreUnidad' => 'Rehabilitación',
                'fechaCreacion' => now(),
                'idCentroSalud' => 3,
            ],
            [
                'nombreUnidad' => 'Procedimientos',
                'fechaCreacion' => now(),
                'idCentroSalud' => 4,
            ],
            [
                'nombreUnidad' => 'Sala ERA',
                'fechaCreacion' => now(),
                'idCentroSalud' => 4,
            ],
            [
                'nombreUnidad' => 'Sala IRA',
                'fechaCreacion' => now(),
                'idCentroSalud' => 4,
            ],
            [
                'nombreUnidad' => 'Dental',
                'fechaCreacion' => now(),
                'idCentroSalud' => 4,
            ],
            [
                'nombreUnidad' => 'Rehabilitación',
                'fechaCreacion' => now(),
                'idCentroSalud' => 4,
            ],
            [
                'nombreUnidad' => 'Procedimientos',
                'fechaCreacion' => now(),
                'idCentroSalud' => 5,
            ],
            [
                'nombreUnidad' => 'Sala ERA',
                'fechaCreacion' => now(),
                'idCentroSalud' => 5,
            ],
            [
                'nombreUnidad' => 'Sala IRA',
                'fechaCreacion' => now(),
                'idCentroSalud' => 5,
            ],
            [
                'nombreUnidad' => 'Dental',
                'fechaCreacion' => now(),
                'idCentroSalud' => 5,
            ],
            [
                'nombreUnidad' => 'Rehabilitación',
                'fechaCreacion' => now(),
                'idCentroSalud' => 5,
            ],
            [
                'nombreUnidad' => 'Procedimientos',
                'fechaCreacion' => now(),
                'idCentroSalud' => 6,
            ],
            [
                'nombreUnidad' => 'Sala ERA',
                'fechaCreacion' => now(),
                'idCentroSalud' => 6,
            ],
            [
                'nombreUnidad' => 'Sala IRA',
                'fechaCreacion' => now(),
                'idCentroSalud' => 6,
            ],
            [
                'nombreUnidad' => 'Dental',
                'fechaCreacion' => now(),
                'idCentroSalud' => 6,
            ],
            [
                'nombreUnidad' => 'Rehabilitación',
                'fechaCreacion' => now(),
                'idCentroSalud' => 6,
            ],
            [
                'nombreUnidad' => 'Procedimientos',
                'fechaCreacion' => now(),
                'idCentroSalud' => 7,
            ],
            [
                'nombreUnidad' => 'Sala ERA',
                'fechaCreacion' => now(),
                'idCentroSalud' => 7,
            ],
            [
                'nombreUnidad' => 'Sala IRA',
                'fechaCreacion' => now(),
                'idCentroSalud' => 7,
            ],
            [
                'nombreUnidad' => 'Dental',
                'fechaCreacion' => now(),
                'idCentroSalud' => 7,
            ],
            [
                'nombreUnidad' => 'Rehabilitación',
                'fechaCreacion' => now(),
                'idCentroSalud' => 7,
            ],

        ];
        foreach ($unidadesClinicas as $unidad) {
            \App\Models\UnidadClinica::create($unidad);
        }
    }
}
