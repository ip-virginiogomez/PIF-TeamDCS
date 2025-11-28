<?php

namespace Database\Seeders;

use App\Models\DocenteCarrera;
use Illuminate\Database\Seeder;

class DocenteCarreraSeeder extends Seeder
{
    public function run(): void
    {
        $docente_carreras =
        [
            [
                'runDocente' => '12345678-9',
                'idSedeCarrera' => '3',
            ],
            [
                'runDocente' => '98765432-1',
                'idSedeCarrera' => '1',
            ],
            [
                'runDocente' => '11223344-5',
                'idSedeCarrera' => '2',
            ],
            [
                'runDocente' => '55667788-0',
                'idSedeCarrera' => '4',
            ],
            [
                'runDocente' => '66778899-2',
                'idSedeCarrera' => '3',
            ],
            [
                'runDocente' => '77889900-3',
                'idSedeCarrera' => '2',
            ],
            [
                'runDocente' => '88990011-4',
                'idSedeCarrera' => '1',
            ],
            [
                'runDocente' => '99001122-5',
                'idSedeCarrera' => '7',
            ],
            [
                'runDocente' => '10111213-6',
                'idSedeCarrera' => '6',
            ],
            [
                'runDocente' => '12131415-7',
                'idSedeCarrera' => '5',
            ],
            [
                'runDocente' => '13141516-8',
                'idSedeCarrera' => '4',
            ],
            [
                'runDocente' => '14151617-9',
                'idSedeCarrera' => '3',
            ],
            [
                'runDocente' => '15161718-0',
                'idSedeCarrera' => '2',
            ],
            [
                'runDocente' => '18806025-0',
                'idSedeCarrera' => '1',
            ],
        ];

        foreach ($docente_carreras as $docente_carrera) {
            DocenteCarrera::create($docente_carrera);
        }
    }
}
