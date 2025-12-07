<?php

namespace Database\Seeders;

use App\Models\Personal;
use Illuminate\Database\Seeder;

class PersonalSeeder extends Seeder
{
    public function run(): void
    {
        $personales = [
            [
                'runUsuario' => '4-4',
                'idCentroSalud' => 1,
                'fechaInicio' => '2025-01-01',
                'fechaFin' => '2026-12-31',
            ],
            [
                'runUsuario' => '5-5',
                'idCentroSalud' => 3,
                'fechaInicio' => '2025-01-01',
                'fechaFin' => '2026-12-31',
            ],
        ];

        foreach ($personales as $personal) {
            Personal::create($personal);
        }
    }
}
