<?php

namespace Database\Seeders;

use App\Models\Alumno;
use Illuminate\Database\Seeder;

class AlumnoSeeder extends Seeder
{
    public function run(): void
    {
        $alumnos = [
            [
                'runAlumno' => '20618693-3',
                'nombres' => 'Vicente Eduardo',
                'apellidoPaterno' => 'Morales',
                'apellidoMaterno' => 'Martínez',
                'fechaNacto' => '2000-10-25',
                'fechaCreacion' => now(),
                'correo' => 'vicente.morales@gmail.com',
            ],
            [
                'runAlumno' => '21514142-k',
                'nombres' => 'Christian Alexis',
                'apellidoPaterno' => 'Silva',
                'apellidoMaterno' => 'Gonzales',
                'fechaNacto' => '2004-02-25',
                'fechaCreacion' => now(),
                'correo' => 'christian.silva@gmail.com',
            ],
            [
                'runAlumno' => '20917244-5',
                'nombres' => 'Sebastian Edmundo',
                'apellidoPaterno' => 'Villouta',
                'apellidoMaterno' => 'Urra',
                'fechaNacto' => '2001-11-22',
                'fechaCreacion' => now(),
                'correo' => 'sebastian.villouta@gmail.com',
            ],
            [
                'runAlumno' => '19876543-2',
                'nombres' => 'Alejandra Isabel',
                'apellidoPaterno' => 'Rojas',
                'apellidoMaterno' => 'Cárdenas',
                'fechaNacto' => '1999-06-30',
                'fechaCreacion' => now(),
                'correo' => 'alejandra.rojas@gmail.com',
            ],
            [
                'runAlumno' => '20765432-1',
                'nombres' => 'Matías Alonso',
                'apellidoPaterno' => 'Fuentes',
                'apellidoMaterno' => 'Salazar',
                'fechaNacto' => '2002-03-18',
                'fechaCreacion' => now(),
                'correo' => 'matias.fuentes@gmail.com',
            ],
            [
                'runAlumno' => '20345678-9',
                'nombres' => 'Isidora Valentina',
                'apellidoPaterno' => 'Castillo',
                'apellidoMaterno' => 'Olivares',
                'fechaNacto' => '2003-09-12',
                'fechaCreacion' => now(),
                'correo' => 'isidora.castillo@gmail.com',
            ],
            [
                'runAlumno' => '21098765-4',
                'nombres' => 'Benjamín Nicolás',
                'apellidoPaterno' => 'Vera',
                'apellidoMaterno' => 'Tapia',
                'fechaNacto' => '2001-12-05',
                'fechaCreacion' => now(),
                'correo' => 'benjamin.vera@gmail.com',
            ],
            [
                'runAlumno' => '20456789-0',
                'nombres' => 'Camila Fernanda',
                'apellidoPaterno' => 'Muñoz',
                'apellidoMaterno' => 'Leiva',
                'fechaNacto' => '2002-07-21',
                'fechaCreacion' => now(),
                'correo' => 'camila.munoz@gmail.com',
            ],
            [
                'runAlumno' => '21123456-7',
                'nombres' => 'Diego Andrés',
                'apellidoPaterno' => 'Riquelme',
                'apellidoMaterno' => 'Poblete',
                'fechaNacto' => '2000-04-14',
                'fechaCreacion' => now(),
                'correo' => 'diego.riquelme@gmail.com',
            ],
            [
                'runAlumno' => '20234567-8',
                'nombres' => 'Antonia María',
                'apellidoPaterno' => 'Soto',
                'apellidoMaterno' => 'Cruz',
                'fechaNacto' => '2003-08-09',
                'fechaCreacion' => now(),
                'correo' => 'antonia.soto@gmail.com',
            ],
            [
                'runAlumno' => '21234567-9',
                'nombres' => 'Joaquín Esteban',
                'apellidoPaterno' => 'Silva',
                'apellidoMaterno' => 'Mardones',
                'fechaNacto' => '2001-05-27',
                'fechaCreacion' => now(),
                'correo' => 'joaquin.silva@gmail.com',
            ],
            [
                'runAlumno' => '20123456-0',
                'nombres' => 'Valentina Sofía',
                'apellidoPaterno' => 'Acuña',
                'apellidoMaterno' => 'Vargas',
                'fechaNacto' => '2002-11-11',
                'fechaCreacion' => now(),
                'correo' => 'valentina.acuna@gmail.com',
            ],
            [
                'runAlumno' => '21345678-1',
                'nombres' => 'Lucas Gabriel',
                'apellidoPaterno' => 'Molina',
                'apellidoMaterno' => 'Ríos',
                'fechaNacto' => '2000-09-03',
                'fechaCreacion' => now(),
                'correo' => 'lucas.molina@gmail.com',
            ],
        ];

        foreach ($alumnos as $alumno) {
            Alumno::create($alumno);
        }
    }
}
