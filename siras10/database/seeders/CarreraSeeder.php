<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder; // Importante importar el otro modelo

class CarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carreras = [
            [
                'nombreCarrera' => 'Medicina',
                'fechaCreacion' => now(),
            ],
            [
                'nombreCarrera' => 'Enfermería',
                'fechaCreacion' => now(),
            ],
            [
                'nombreCarrera' => 'Obstetricia',
                'fechaCreacion' => now(),
            ],
            [
                'nombreCarrera' => 'Química y Farmacia',
                'fechaCreacion' => now(),
            ],
            [
                'nombreCarrera' => 'Terapia Ocupacional',
                'fechaCreacion' => now(),
            ],
            [
                'nombreCarrera' => 'Kinesiología',
                'fechaCreacion' => now(),
            ],
            [
                'nombreCarrera' => 'Técnico en Enfermería',
                'fechaCreacion' => now(),
            ],
        ];

        foreach ($carreras as $carrera) {
            \App\Models\Carrera::create($carrera);
        }
    }
}
