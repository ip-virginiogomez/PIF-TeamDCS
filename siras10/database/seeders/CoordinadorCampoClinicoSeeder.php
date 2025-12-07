<?php

namespace Database\Seeders;

use App\Models\CoordinadorCampoClinico;
use Illuminate\Database\Seeder;

class CoordinadorCampoClinicoSeeder extends Seeder
{
    public function run(): void
    {
        $coordinadores = [
            [
                'runUsuario' => '2-2',
                'idCentroFormador' => 7,
                'fechaInicio' => '2025-01-01',
                'fechaFin' => '2026-12-31',
            ],
            [
                'runUsuario' => '3-3',
                'idCentroFormador' => 5,
                'fechaInicio' => '2025-01-01',
                'fechaFin' => '2026-12-31',
            ],
            [
                'runUsuario' => '7-7',
                'idCentroFormador' => 2,
                'fechaInicio' => '2025-01-01',
                'fechaFin' => '2026-12-31',
            ],
        ];

        foreach ($coordinadores as $coordinador) {
            CoordinadorCampoClinico::create($coordinador);
        }
    }
}
