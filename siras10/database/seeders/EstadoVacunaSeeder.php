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
                'nombreEstado' => 'Expirado',
                'descripcion' => 'Vacuna que ha expirado debido a la fecha de vencimiento',
                'fechaCreacion' => now(),
            ],
            [
                'nombreEstado' => 'Activo',
                'descripcion' => 'Vacuna activa y válida, cumple con los requisitos',
                'fechaCreacion' => now(),
            ],
        ];
        foreach ($estados as $estado) {
            EstadoVacuna::create($estado);
        }
    }
}
