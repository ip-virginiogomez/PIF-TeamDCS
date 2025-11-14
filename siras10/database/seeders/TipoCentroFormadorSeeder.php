<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TipoCentroFormadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiposCentroFormador = [
            [
                'nombreTipo' => 'Universidad',
                'fechaCreacion' => now(),
            ],
            [
                'nombreTipo' => 'Instituto Profesional',
                'fechaCreacion' => now(),
            ],
            [
                'nombreTipo' => 'Centro de Formación Técnica',
                'fechaCreacion' => now(),
            ],
            [
                'nombreTipo' => 'Liceo Técnico',
                'fechaCreacion' => now(),
            ],
        ];
        foreach ($tiposCentroFormador as $tipo) {
            \App\Models\TipoCentroFormador::create($tipo);
        }
    }
}
