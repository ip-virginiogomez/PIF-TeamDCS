<?php

namespace Database\Seeders;

use App\Models\CupoOferta;
use App\Models\CupoOfertaHorario;
use Illuminate\Database\Seeder;

class CupoOfertaHorarioSeeder extends Seeder
{
    public function run(): void
    {
        // Definir los horarios asociados a las ofertas específicas
        // Usamos los mismos criterios que en CupoOfertaSeeder para encontrar la oferta
        
        $horariosData = [
            [
                'criteria' => [
                    'idPeriodo' => 1,
                    'idUnidadClinica' => 1,
                    'idTipoPractica' => 1,
                    'idCarrera' => 1,
                ],
                'horarios' => [
                    ['diaSemana' => 'Lunes', 'horaEntrada' => '08:30:00', 'horaSalida' => '16:30:00'],
                    ['diaSemana' => 'Miércoles', 'horaEntrada' => '08:30:00', 'horaSalida' => '16:30:00'],
                    ['diaSemana' => 'Viernes', 'horaEntrada' => '08:30:00', 'horaSalida' => '13:30:00'],
                ]
            ],
            [
                'criteria' => [
                    'idPeriodo' => 1,
                    'idUnidadClinica' => 3,
                    'idTipoPractica' => 2,
                    'idCarrera' => 2,
                    'cantCupos' => 2, // Adding extra criteria to be sure
                ],
                'horarios' => [
                    ['diaSemana' => 'Martes', 'horaEntrada' => '10:00:00', 'horaSalida' => '18:00:00'],
                    ['diaSemana' => 'Jueves', 'horaEntrada' => '10:00:00', 'horaSalida' => '18:00:00'],
                ]
            ],
            [
                'criteria' => [
                    'idPeriodo' => 1,
                    'idUnidadClinica' => 3,
                    'idTipoPractica' => 1,
                    'idCarrera' => 2,
                ],
                'horarios' => [
                    ['diaSemana' => 'Lunes', 'horaEntrada' => '11:00:00', 'horaSalida' => '19:00:00'],
                    ['diaSemana' => 'Martes', 'horaEntrada' => '11:00:00', 'horaSalida' => '19:00:00'],
                    ['diaSemana' => 'Miércoles', 'horaEntrada' => '11:00:00', 'horaSalida' => '19:00:00'],
                    ['diaSemana' => 'Jueves', 'horaEntrada' => '11:00:00', 'horaSalida' => '19:00:00'],
                    ['diaSemana' => 'Viernes', 'horaEntrada' => '11:00:00', 'horaSalida' => '19:00:00'],
                ]
            ],
            [
                'criteria' => [
                    'idPeriodo' => 1,
                    'idUnidadClinica' => 4,
                    'idTipoPractica' => 2,
                    'idCarrera' => 1,
                ],
                'horarios' => [
                    ['diaSemana' => 'Lunes', 'horaEntrada' => '09:30:00', 'horaSalida' => '17:30:00'],
                    ['diaSemana' => 'Miércoles', 'horaEntrada' => '09:30:00', 'horaSalida' => '17:30:00'],
                ]
            ],
            [
                'criteria' => [
                    'idPeriodo' => 1,
                    'idUnidadClinica' => 2,
                    'idTipoPractica' => 2,
                    'idCarrera' => 2,
                ],
                'horarios' => [
                    ['diaSemana' => 'Sábado', 'horaEntrada' => '07:30:00', 'horaSalida' => '15:30:00'],
                ]
            ],
            [
                'criteria' => [
                    'idPeriodo' => 1,
                    'idUnidadClinica' => 9,
                    'idTipoPractica' => 2,
                    'idCarrera' => 2,
                ],
                'horarios' => [
                    ['diaSemana' => 'Lunes', 'horaEntrada' => '08:45:00', 'horaSalida' => '16:45:00'],
                    ['diaSemana' => 'Martes', 'horaEntrada' => '08:45:00', 'horaSalida' => '16:45:00'],
                    ['diaSemana' => 'Miércoles', 'horaEntrada' => '08:45:00', 'horaSalida' => '16:45:00'],
                    ['diaSemana' => 'Jueves', 'horaEntrada' => '08:45:00', 'horaSalida' => '16:45:00'],
                    ['diaSemana' => 'Viernes', 'horaEntrada' => '08:45:00', 'horaSalida' => '16:45:00'],
                ]
            ],
        ];

        foreach ($horariosData as $item) {
            // Buscar la oferta correspondiente
            $cupoOferta = CupoOferta::where($item['criteria'])->first();

            if ($cupoOferta) {
                foreach ($item['horarios'] as $horario) {
                    $cupoOferta->horarios()->firstOrCreate($horario);
                }
            }
        }
    }
}
