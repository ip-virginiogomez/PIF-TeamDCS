<?php

namespace Database\Seeders;

use App\Models\Ciudad;
use Illuminate\Database\Seeder;

class CiudadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ciudades = [
            [
                'nombreCiudad' => 'Los Ángeles',
                'fechacreacion' => now(),
            ],
            [
                'nombreCiudad' => 'Santa Fe',
                'fechacreacion' => now(),
            ],
            [
                'nombreCiudad' => 'El Peral',
                'fechacreacion' => now(),
            ],
        ];

        foreach ($ciudades as $ciudad) {
            Ciudad::create($ciudad);
        }
    }
}
