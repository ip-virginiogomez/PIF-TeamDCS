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
            ],
            [
                'nombreUnidad' => 'Sala ERA',
                'fechaCreacion' => now(),
            ],
            [
                'nombreUnidad' => 'Sala IRA',
                'fechaCreacion' => now(),
            ],
            [
                'nombreUnidad' => 'Dental',
                'fechaCreacion' => now(),
            ],
            [
                'nombreUnidad' => 'Rehabilitación',
                'fechaCreacion' => now(),
            ],
        ];
        foreach ($unidadesClinicas as $unidad) {
            \App\Models\UnidadClinica::create($unidad);
        }
    }
}
