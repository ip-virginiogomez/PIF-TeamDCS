<?php

namespace Database\Seeders;

use App\Models\CupoOferta;
use Illuminate\Database\Seeder;

class CupoOfertaSeeder extends Seeder
{
    public function run(): void
    {
        $cupoOfertas = [
            [
                'idPeriodo' => 1,
                'idUnidadClinica' => 1,
                'idTipoPractica' => 1,
                'idCarrera' => 1,
                'cantCupos' => 5,
                'fechaEntrada' => '2025-09-01',
                'fechaSalida' => '2025-09-30',
                'fechaCreacion' => now(),
            ],
            [
                'idPeriodo' => 1,
                'idUnidadClinica' => 3,
                'idTipoPractica' => 2,
                'idCarrera' => 2,
                'cantCupos' => 2,
                'fechaEntrada' => '2025-10-01',
                'fechaSalida' => '2025-10-31',
                'fechaCreacion' => now(),
            ],
            [
                'idPeriodo' => 1,
                'idUnidadClinica' => 3,
                'idTipoPractica' => 1,
                'idCarrera' => 2,
                'cantCupos' => 4,
                'fechaEntrada' => '2025-12-01',
                'fechaSalida' => '2025-12-31',
                'fechaCreacion' => now(),
            ],
            [
                'idPeriodo' => 1,
                'idUnidadClinica' => 4,
                'idTipoPractica' => 2,
                'idCarrera' => 1,
                'cantCupos' => 5,
                'fechaEntrada' => '2025-05-01',
                'fechaSalida' => '2025-05-31',
                'fechaCreacion' => now(),
            ],
            [
                'idPeriodo' => 1,
                'idUnidadClinica' => 2,
                'idTipoPractica' => 2,
                'idCarrera' => 2,
                'cantCupos' => 4,
                'fechaEntrada' => '2025-03-01',
                'fechaSalida' => '2025-03-31',
                'fechaCreacion' => now(),
            ],
            [
                'idPeriodo' => 1,
                'idUnidadClinica' => 9,
                'idTipoPractica' => 2,
                'idCarrera' => 2,
                'cantCupos' => 6,
                'fechaEntrada' => '2025-01-01',
                'fechaSalida' => '2025-01-31',
                'fechaCreacion' => now(),
            ],
        ];

        foreach ($cupoOfertas as $cupoOferta) {
            CupoOferta::firstOrCreate(
                [
                    'idPeriodo' => $cupoOferta['idPeriodo'],
                    'idUnidadClinica' => $cupoOferta['idUnidadClinica'],
                    'idTipoPractica' => $cupoOferta['idTipoPractica'],
                    'idCarrera' => $cupoOferta['idCarrera'],
                    'fechaEntrada' => $cupoOferta['fechaEntrada'],
                    'fechaSalida' => $cupoOferta['fechaSalida'],
                ],
                $cupoOferta
            );
        }
    }
}
