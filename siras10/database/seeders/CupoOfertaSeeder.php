<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CupoOferta;

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
                'cantCupos' => 4,
                'fechaEntrada' => '2024-07-01',
                'fechaSalida' => '2024-07-31',
                'horaEntrada' => '08:00:00',
                'horaSalida' => '16:00:00',
                'fechaCreacion' => now(),
            ],
            [
                'idPeriodo' => 1,
                'idUnidadClinica' => 2,
                'idTipoPractica' => 2,
                'idCarrera' => 2,
                'cantCupos' => 3,
                'fechaEntrada' => '2024-08-01',
                'fechaSalida' => '2024-08-31',
                'horaEntrada' => '09:00:00',
                'horaSalida' => '17:00:00',
                'fechaCreacion' => now(),
            ],
            [
                'idPeriodo' => 2,
                'idUnidadClinica' => 1,
                'idTipoPractica' => 1,
                'idCarrera' => 1,
                'cantCupos' => 5,
                'fechaEntrada' => '2025-09-01',
                'fechaSalida' => '2025-09-30',
                'horaEntrada' => '08:30:00',
                'horaSalida' => '16:30:00',
                'fechaCreacion' => now(),
            ],
            [
                'idPeriodo' => 2,
                'idUnidadClinica' => 3,
                'idTipoPractica' => 2,
                'idCarrera' => 2,
                'cantCupos' => 2,
                'fechaEntrada' => '2025-10-01',
                'fechaSalida' => '2025-10-31',
                'horaEntrada' => '10:00:00',
                'horaSalida' => '18:00:00',
                'fechaCreacion' => now(),
            ],
            [
                'idPeriodo' => 1,
                'idUnidadClinica' => 2,
                'idTipoPractica' => 2,
                'idCarrera' => 1,
                'cantCupos' => 6,
                'fechaEntrada' => '2024-11-01',
                'fechaSalida' => '2024-11-30',
                'horaEntrada' => '07:00:00',
                'horaSalida' => '15:00:00',
                'fechaCreacion' => now(),
            ],
            [
                'idPeriodo' => 2,
                'idUnidadClinica' => 3,
                'idTipoPractica' => 1,
                'idCarrera' => 2,
                'cantCupos' => 4,
                'fechaEntrada' => '2025-12-01',
                'fechaSalida' => '2025-12-31',
                'horaEntrada' => '11:00:00',
                'horaSalida' => '19:00:00',
                'fechaCreacion' => now(),
            ],
            [
                'idPeriodo' => 1,
                'idUnidadClinica' => 1,
                'idTipoPractica' => 1,
                'idCarrera' => 2,
                'cantCupos' => 3,
                'fechaEntrada' => '2024-06-01',
                'fechaSalida' => '2024-06-30',
                'horaEntrada' => '08:15:00',
                'horaSalida' => '16:15:00',
                'fechaCreacion' => now(),
            ],
            [
                'idPeriodo' => 2,
                'idUnidadClinica' => 4,
                'idTipoPractica' => 2,
                'idCarrera' => 1,
                'cantCupos' => 5,
                'fechaEntrada' => '2025-05-01',
                'fechaSalida' => '2025-05-31',
                'horaEntrada' => '09:30:00',
                'horaSalida' => '17:30:00',
                'fechaCreacion' => now(),
            ],
            [
                'idPeriodo' => 1,
                'idUnidadClinica' => 5,
                'idTipoPractica' => 1,
                'idCarrera' => 1,
                'cantCupos' => 2,
                'fechaEntrada' => '2024-04-01',
                'fechaSalida' => '2024-04-30',
                'horaEntrada' => '10:15:00',
                'horaSalida' => '18:15:00',
                'fechaCreacion' => now(),
            ],
            [
                'idPeriodo' => 2,
                'idUnidadClinica' => 2,
                'idTipoPractica' => 2,
                'idCarrera' => 2,
                'cantCupos' => 4,
                'fechaEntrada' => '2025-03-01',
                'fechaSalida' => '2025-03-31',
                'horaEntrada' => '07:30:00',
                'horaSalida' => '15:30:00',
                'fechaCreacion' => now(),
            ],
            [
                'idPeriodo' => 1,
                'idUnidadClinica' => 10,
                'idTipoPractica' => 1,
                'idCarrera' => 1,
                'cantCupos' => 3,
                'fechaEntrada' => '2024-02-01',
                'fechaSalida' => '2024-02-28',
                'horaEntrada' => '12:00:00',
                'horaSalida' => '20:00:00',
                'fechaCreacion' => now(),
            ],
            [
                'idPeriodo' => 2,
                'idUnidadClinica' => 9,
                'idTipoPractica' => 2,
                'idCarrera' => 2,
                'cantCupos' => 6,
                'fechaEntrada' => '2025-01-01',
                'fechaSalida' => '2025-01-31',
                'horaEntrada' => '08:45:00',
                'horaSalida' => '16:45:00',
                'fechaCreacion' => now(),
            ]
        ];
        foreach ($cupoOfertas as $cupoOferta) {
            CupoOferta::create($cupoOferta);
        }
    }
}
