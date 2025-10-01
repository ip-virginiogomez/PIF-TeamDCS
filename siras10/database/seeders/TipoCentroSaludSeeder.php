<?php

namespace Database\Seeders;

use App\Models\TipoCentroSalud;
use Illuminate\Database\Seeder;

class TipoCentroSaludSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiposCentroSalud = [
            [
                'nombreTipo' => 'Centro de Salud Familiar',
                'acronimo' => 'CESFAM',
                'fechaCreacion' => now(),
            ],
            [
                'nombreTipo' => 'Centro Comunitario de Salud Familiar',
                'acronimo' => 'CECOSF',
                'fechaCreacion' => now(),
            ],
            [
                'nombreTipo' => 'Servicio de Alta Resolutividad',
                'acronimo' => 'SAR',
                'fechaCreacion' => now(),
            ],
            [
                'nombreTipo' => 'Posta de Salud Rural',
                'acronimo' => 'Posta',
                'fechaCreacion' => now(),
            ],
        ];

        foreach ($tiposCentroSalud as $tipo) {
            TipoCentroSalud::create($tipo);
        }
    }
}
