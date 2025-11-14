<?php

namespace Database\Seeders;

use App\Models\Sede;
use Illuminate\Database\Seeder;

class SedeSeeder extends Seeder
{
    public function run(): void
    {
        $sedes = [
            [
                'nombreSede' => 'Sede Central (Concepción)',
                'direccion' => 'Autopista Tplco 7120, Concepción',
                'idCentroFormador' => 2,
                'fechaCreacion' => now(),
                'numContacto' => '+56412111000',
            ],
            [
                'nombreSede' => 'Campus Los Ángeles',
                'direccion' => 'Av. Alemania 123, Los Ángeles',
                'idCentroFormador' => 7,
                'fechaCreacion' => now(),
                'numContacto' => '+5643222333',
            ],
            [
                'nombreSede' => 'Sede Chillán',
                'direccion' => 'Av. Libertad 456, Chillán',
                'idCentroFormador' => 7,
                'fechaCreacion' => now(),
                'numContacto' => '+5642244555',
            ],
            [
                'nombreSede' => 'Campus Clínico Talcahuano',
                'direccion' => 'Av. Colón 789, Talcahuano',
                'idCentroFormador' => 5,
                'fechaCreacion' => now(),
                'numContacto' => '+5641266777',
            ],
            [
                'nombreSede' => 'Sede Angol',
                'direccion' => 'Calle Ficticia 101, Angol',
                'idCentroFormador' => 5,
                'fechaCreacion' => now(),
                'numContacto' => '+5645288999',
            ],
            [
                'nombreSede' => 'Edificio Postgrado',
                'direccion' => 'O\'Higgins 500, Concepción',
                'idCentroFormador' => 2,
                'fechaCreacion' => now(),
                'numContacto' => '+56412111001',
            ],
            [
                'nombreSede' => 'Campus San Pedro de la Paz',
                'direccion' => 'Av. Los Carrera 202, San Pedro de la Paz',
                'idCentroFormador' => 1,
                'fechaCreacion' => now(),
                'numContacto' => '+56412111002',
            ],
            [
                'nombreSede' => 'Sede Lota',
                'direccion' => 'Calle Lota 303, Lota',
                'idCentroFormador' => 3,
                'fechaCreacion' => now(),
                'numContacto' => '+56412111003',
            ],
            [
                'nombreSede' => 'Campus Hualpén',
                'direccion' => 'Av. Hualpén 404, Hualpén',
                'idCentroFormador' => 4,
                'fechaCreacion' => now(),
                'numContacto' => '+56412111004',
            ],
            [
                'nombreSede' => 'Sede Coronel',
                'direccion' => 'Calle Coronel 505, Coronel',
                'idCentroFormador' => 6,
                'fechaCreacion' => now(),
                'numContacto' => '+56412111005',
            ],
            [
                'nombreSede' => 'Escuela Secundaria Los Ángeles',
                'direccion' => 'Patricio Castro 1051, Los Ángeles',
                'idCentroFormador' => 8,
                'fechaCreacion' => now(),
                'numContacto' => '+56412111006',
            ]
        ];
        foreach ($sedes as $sede) {
            Sede::create($sede);
        }
    }
}
