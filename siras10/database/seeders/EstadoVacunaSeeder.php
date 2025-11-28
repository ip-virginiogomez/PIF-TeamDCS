<?php

namespace Database\Seeders;

use App\Models\EstadoVacuna;
use Illuminate\Database\Seeder;

class EstadoVacunaSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            [
                'nombreEstado' => 'Pendiente',
                'descripcion' => 'Documentación de la vacuna pendiente de revisión',
                'fechaCreacion' => now(),
            ],
            [
                'nombreEstado' => 'Expirada',
                'descripcion' => 'Vacuna que ha expirado debido a la fecha de vencimiento',
                'fechaCreacion' => now(),
            ],
            [
                'nombreEstado' => 'Rechazada',
                'descripcion' => 'Documento de la vacuna ha sido rechazado tras revisión',
                'fechaCreacion' => now(),
            ],
            [
                'nombreEstado' => 'Aprobada',
                'descripcion' => 'Vacuna aprobada y válida, cumple con los requisitos',
                'fechaCreacion' => now(),
            ],
        ];
        foreach ($estados as $estado) {
            EstadoVacuna::create($estado);
        }
    }
}
