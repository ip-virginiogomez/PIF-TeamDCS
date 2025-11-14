<?php

namespace Database\Seeders;

use App\Models\AlumnoCarrera;
use Illuminate\Database\Seeder;

class AlumnoCarreraSeeder extends Seeder
{
    public function run(): void
    {
        $alumnoCarreras = [
            [
                'runAlumno' => '20618693-3',
                'idSedeCarrera' => 1,
            ],
            [
                'runAlumno' => '21514142-k',
                'idSedeCarrera' => 3,
            ],
            [
                'runAlumno' => '20917244-5',
                'idSedeCarrera' => 4,
            ],
            [
                'runAlumno' => '19876543-2',
                'idSedeCarrera' => 2,
            ],
            [
                'runAlumno' => '20765432-1',
                'idSedeCarrera' => 5,
            ],
            [
                'runAlumno' => '20345678-9',
                'idSedeCarrera' => 6,
            ],
            [
                'runAlumno' => '21098765-4',
                'idSedeCarrera' => 7,
            ],
            [
                'runAlumno' => '20456789-0',
                'idSedeCarrera' => 8,
            ],
            [
                'runAlumno' => '21123456-7',
                'idSedeCarrera' => 9,
            ],
            [
                'runAlumno' => '20234567-8',
                'idSedeCarrera' => 10,
            ],
            [
                'runAlumno' => '21234567-9',
                'idSedeCarrera' => 1,
            ],
            [
                'runAlumno' => '20123456-0',
                'idSedeCarrera' => 2,
            ],
            [
                'runAlumno' => '21345678-1',
                'idSedeCarrera' => 11,
            ]
        ];
        foreach ($alumnoCarreras as $alumnoCarrera) {
            AlumnoCarrera::create($alumnoCarrera);
        }
    }
}
