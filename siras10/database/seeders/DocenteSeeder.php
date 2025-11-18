<?php

namespace Database\Seeders;

use App\Models\Docente;
use Illuminate\Database\Seeder;

class DocenteSeeder extends Seeder
{
    public function run(): void
    {
        $docentes = [
            [
                'runDocente' => '12345678-9',
                'nombresDocente' => 'Juan',
                'apellidoPaterno' => 'Pérez',
                'apellidoMaterno' => 'Gómez',
                'correo' => 'juan@example.com',
                'fechaCreacion' => now(),
                'fechaNacto' => '1980-05-15',
                'profesion' => 'Enfermero',
            ],
            [
                'runDocente' => '98765432-1',
                'nombresDocente' => 'María',
                'apellidoPaterno' => 'López',
                'apellidoMaterno' => 'Fernández',
                'correo' => 'maria@example.com',
                'fechaCreacion' => now(),
                'fechaNacto' => '1985-08-20',
                'profesion' => 'Médico',
            ],
            [
                'runDocente' => '11223344-5',
                'nombresDocente' => 'Carlos',
                'apellidoPaterno' => 'Sánchez',
                'apellidoMaterno' => 'Ramírez',
                'correo' => 'carlos@example.com',
                'fechaCreacion' => now(),
                'fechaNacto' => '1990-12-10',
                'profesion' => 'Fisioterapeuta',
            ],
            [
                'runDocente' => '55667788-0',
                'nombresDocente' => 'Ana',
                'apellidoPaterno' => 'Torres',
                'apellidoMaterno' => 'Vargas',
                'correo' => 'ana@example.com',
                'fechaCreacion' => now(),
                'fechaNacto' => '1992-07-25',
                'profesion' => 'Psicóloga',
            ],
            [
                'runDocente' => '66778899-2',
                'nombresDocente' => 'Luis',
                'apellidoPaterno' => 'Martínez',
                'apellidoMaterno' => 'Díaz',
                'correo' => 'luis@example.com',
                'fechaCreacion' => now(),
                'fechaNacto' => '1988-03-30',
                'profesion' => 'Odontólogo',
            ],
            [
                'runDocente' => '77889900-3',
                'nombresDocente' => 'Sofía',
                'apellidoPaterno' => 'García',
                'apellidoMaterno' => 'Molina',
                'correo' => 'sofia@example.com',
                'fechaCreacion' => now(),
                'fechaNacto' => '1991-11-05',
                'profesion' => 'Nutrióloga',
            ],
            [
                'runDocente' => '88990011-4',
                'nombresDocente' => 'Diego',
                'apellidoPaterno' => 'Ruiz',
                'apellidoMaterno' => 'Cruz',
                'correo' => 'diego@example.com',
                'fechaCreacion' => now(),
                'fechaNacto' => '1987-09-12',
                'profesion' => 'Médico',
            ],
            [
                'runDocente' => '99001122-5',
                'nombresDocente' => 'Elena',
                'apellidoPaterno' => 'Flores',
                'apellidoMaterno' => 'Soto',
                'correo' => 'elena@example.com',
                'fechaCreacion' => now(),
                'fechaNacto' => '1993-04-18',
                'profesion' => 'Enfermera',
            ],
            [
                'runDocente' => '10111213-6',
                'nombresDocente' => 'Miguel',
                'apellidoPaterno' => 'Castro',
                'apellidoMaterno' => 'Ortega',
                'correo' => 'miguel@example.com',
                'fechaCreacion' => now(),
                'fechaNacto' => '1989-06-22',
                'profesion' => 'Terapeuta',
            ],
            [
                'runDocente' => '12131415-7',
                'nombresDocente' => 'Laura',
                'apellidoPaterno' => 'Ramos',
                'apellidoMaterno' => 'Vega',
                'correo' => 'laura@example.com',
                'fechaCreacion' => now(),
                'fechaNacto' => '1994-10-10',
                'profesion' => 'Psicóloga',
            ],
            [
                'runDocente' => '13141516-8',
                'nombresDocente' => 'Andrés',
                'apellidoPaterno' => 'Morales',
                'apellidoMaterno' => 'Silva',
                'correo' => 'andres@example.com',
                'fechaCreacion' => now(),
                'fechaNacto' => '1995-02-14',
                'profesion' => 'Ingeniero',
            ],
            [
                'runDocente' => '14151617-9',
                'nombresDocente' => 'Camila',
                'apellidoPaterno' => 'Jiménez',
                'apellidoMaterno' => 'Navarro',
                'correo' => 'camila@example.com',
                'fechaCreacion' => now(),
                'fechaNacto' => '1996-08-19',
                'profesion' => 'Arquitecta',
            ],
            [
                'runDocente' => '15161718-0',
                'nombresDocente' => 'Fernando',
                'apellidoPaterno' => 'Silva',
                'apellidoMaterno' => 'Rojas',
                'correo' => 'fernando@example.com',
                'fechaCreacion' => now(),
                'fechaNacto' => '1990-12-01',
                'profesion' => 'Ingeniero',
            ],
            [
                'runDocente' => '18806025-0',
                'nombresDocente' => 'Camilo',
                'apellidoPaterno' => 'Córdova',
                'apellidoMaterno' => 'Álvarez',
                'correo' => 'camilo@example.com',
                'fechaCreacion' => now(),
                'fechaNacto' => '1994-12-30',
                'profesion' => 'Ingeniero',
            ],
        ];
        foreach ($docentes as $docenteData) {
            Docente::create($docenteData);
        }
    }
}
