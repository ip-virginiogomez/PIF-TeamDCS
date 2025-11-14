<?php

namespace Database\Seeders;

use App\Models\SedeCarrera;
use Illuminate\Database\Seeder;

class SedeCarreraSeeder extends Seeder
{
    public function run(): void
    {
        $sedeCarreras = [
            [
                'nombreSedeCarrera' => 'Técnico en Enfermería',
                'idSede' => 2,
                'idCarrera' => 2,
                'codigoCarrera' => 'IS-001',
                'fechaCreacion' => now(),
            ],
            [
                'nombreSedeCarrera' => 'Técnico en Enfermería',
                'idSede' => 3,
                'idCarrera' => 2,
                'codigoCarrera' => 'IS-002',
                'fechaCreacion' => now(),
            ],
            [
                'nombreSedeCarrera' => 'Técnico en Enfermería',
                'idSede' => 4,
                'idCarrera' => 2,
                'codigoCarrera' => 'IS-003',
                'fechaCreacion' => now(),
            ],
            [
                'nombreSedeCarrera' => 'Kinesiología',
                'idSede' => 5,
                'idCarrera' => 6,
                'codigoCarrera' => 'IS-004',
                'fechaCreacion' => now(),
            ],
            [
                'nombreSedeCarrera' => 'Medicina General',
                'idSede' => 1,
                'idCarrera' => 1,
                'codigoCarrera' => 'IS-005',
                'fechaCreacion' => now(),
            ],
            [
                'nombreSedeCarrera' => 'Odontología',
                'idSede' => 6,
                'idCarrera' => 4,
                'codigoCarrera' => 'IS-006',
                'fechaCreacion' => now(),
            ],
            [
                'nombreSedeCarrera' => 'Enfermería',
                'idSede' => 7,
                'idCarrera' => 3,
                'codigoCarrera' => 'IS-007',
                'fechaCreacion' => now(),
            ],
            [
                'nombreSedeCarrera' => 'Fonoaudiología',
                'idSede' => 1,
                'idCarrera' => 5,
                'codigoCarrera' => 'IS-008',
                'fechaCreacion' => now(),
            ],
            [
                'nombreSedeCarrera' => 'Tecnico en Enfermería',
                'idSede' => 11,
                'idCarrera' => 2,
                'codigoCarrera' => 'IS-009',
                'fechaCreacion' => now(),
            ],
            [
                'nombreSedeCarrera' => 'Nutrición y Dietética',
                'idSede' => 9,
                'idCarrera' => 5,
                'codigoCarrera' => 'IS-010',
                'fechaCreacion' => now(),
            ],
            [
                'nombreSedeCarrera' => 'Tecnico en Enfermería',
                'idSede' => 8,
                'idCarrera' => 2,
                'codigoCarrera' => 'IS-011',
                'fechaCreacion' => now(),
            ],
            [
                'nombreSedeCarrera' => 'Tecnico en Enfermería',
                'idSede' => 10,
                'idCarrera' => 2,
                'codigoCarrera' => 'IS-012',
                'fechaCreacion' => now(),
            ]
        ];

        foreach ($sedeCarreras as $sedeCarrera) {
            SedeCarrera::create($sedeCarrera);
        }
    }
}
