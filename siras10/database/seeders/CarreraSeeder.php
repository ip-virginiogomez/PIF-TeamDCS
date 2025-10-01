<?php

namespace Database\Seeders;

use App\Models\Carrera;
use App\Models\CentroFormador;
use Illuminate\Database\Seeder; // Importante importar el otro modelo

class CarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Paso 1: Define y crea las carreras ÚNICAS.
        // Usamos el nombre como clave para evitar duplicados y facilitar la búsqueda.
        $carreras = [
            'Medicina' => Carrera::create(['nombre' => 'Medicina']),
            'Enfermería' => Carrera::create(['nombre' => 'Enfermería']),
            'Kinesiología' => Carrera::create(['nombre' => 'Kinesiología']),
            'Obstetricia' => Carrera::create(['nombre' => 'Obstetricia']),
            'Técnico en Enfermería' => Carrera::create(['nombre' => 'Técnico en Enfermería']),
            'Técnico en Laboratorio Clínico' => Carrera::create(['nombre' => 'Técnico en Laboratorio Clínico']),
        ];

        // Paso 2: Define las vinculaciones.
        // Aquí decimos qué carrera se imparte en qué centro (usando los IDs).
        $vinculaciones = [
            // Universidad 1 (id = 1)
            1 => ['Medicina', 'Enfermería', 'Kinesiología'],

            // Universidad 2 (id = 2)
            2 => ['Medicina', 'Enfermería', 'Obstetricia'],

            // Instituto Técnico (id = 3)
            3 => ['Técnico en Enfermería', 'Técnico en Laboratorio Clínico'],
        ];

        // Paso 3: Recorre las vinculaciones y adjúntalas en la tabla pivote.
        foreach ($vinculaciones as $centroId => $nombresCarreras) {
            $centro = CentroFormador::find($centroId);

            if ($centro) {
                foreach ($nombresCarreras as $nombreCarrera) {
                    // Usamos el método attach() de la relación para crear el vínculo.
                    $centro->carreras()->attach($carreras[$nombreCarrera]->id);
                }
            }
        }
    }
}
