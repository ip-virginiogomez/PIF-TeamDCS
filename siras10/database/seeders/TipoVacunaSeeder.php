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
                'duracion' => 1,
                'fechaCreacion' => now(),
            ],
            [
                'nombreVacuna' => 'Hepatitis B',
                'duracion' => 3,
                'fechaCreacion' => now(),
            ],
        ];
        foreach ($tipos as $tipo) {
            TipoVacuna::create($tipo);
        }
    }
}
