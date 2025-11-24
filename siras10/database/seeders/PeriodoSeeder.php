<?php

namespace Database\Seeders;

use App\Models\Periodo;
use Illuminate\Database\Seeder;

class PeriodoSeeder extends Seeder
{
    public function run(): void
    {
        $periodos = [
            [
                'Año' => 2025,
                'fechaCreacion' => now(),
                'fechaInicio' => '2025-01-15',
                'fechaFin' => '2025-12-31',
            ],
            [
                'Año' => 2026,
                'fechaCreacion' => now(),
                'fechaInicio' => '2026-01-15',
                'fechaFin' => '2026-12-31',
            ],
        ];
        foreach ($periodos as $periodo) {
            Periodo::create($periodo);
        }
    }
}
