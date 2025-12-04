<?php

namespace Database\Seeders;

use App\Models\TipoVacuna;
use Illuminate\Database\Seeder;

class TipoVacunaSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            [
                'nombreVacuna' => 'Influenza',
                'duracion' => 365,
                'fechaCreacion' => now(),
            ],
            [
                'nombreVacuna' => 'Hepatitis B',
                'duracion' => 3650,
                'fechaCreacion' => now(),
            ],
        ];
        foreach ($tipos as $tipo) {
            TipoVacuna::create($tipo);
        }
    }
}
