<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Usuario::firstOrCreate(
            ['runUsuario' => '1-1'],
            [
                'nombreUsuario' => 'admin',
                'apellidoPaterno' => 'Villouta',
                'apellidoMaterno' => 'Urra',
                'correo' => 'admin@siras.com',
                'contrasenia' => Hash::make('admin'),
                'fechaCreacion' => now(),
            ]
        );

        $admin->assignRole('Admin');

        $users = [
            [
                'runUsuario' => '2-2',
                'nombreUsuario' => 'ipvg',
                'apellidoPaterno' => 'coordinador',
                'correo' => 'ipvg@gmail.com',
                'fechaCreacion' => now(),
                'contrasenia' => Hash::make('12345678'),
                'idTipoPersonalSalud' => 2,
            ],
            [
                'runUsuario' => '3-3',
                'nombreUsuario' => 'st',
                'apellidoPaterno' => 'coordinador',
                'correo' => 'st@gmail.com',
                'fechaCreacion' => now(),
                'contrasenia' => Hash::make('12345678'),
                'idTipoPersonalSalud' => 2,
            ],
            [
                'runUsuario' => '4-4',
                'nombreUsuario' => 'Nuevo Horizonte',
                'apellidoPaterno' => 'RAD',
                'correo' => 'RAD1@gmail.com',
                'fechaCreacion' => now(),
                'contrasenia' => Hash::make('12345678'),
                'idTipoPersonalSalud' => 1,
            ],
            [
                'runUsuario' => '5-5',
                'nombreUsuario' => 'Norte',
                'apellidoPaterno' => 'RAD',
                'correo' => 'RAD2@gmail.com',
                'fechaCreacion' => now(),
                'contrasenia' => Hash::make('12345678'),
                'idTipoPersonalSalud' => 1,
            ],
            [
                'runUsuario' => '6-6',
                'nombreUsuario' => 'Francisco',
                'apellidoPaterno' => 'Guzmán',
                'correo' => 'francisco.guzman@gmail.com',
                'fechaCreacion' => now(),
                'contrasenia' => Hash::make('12345678'),
                'idTipoPersonalSalud' => 3,
            ],
            [
                'runUsuario' => '7-7',
                'nombreUsuario' => 'udec',
                'apellidoPaterno' => 'coordinador',
                'correo' => 'udec@gmail.com',
                'fechaCreacion' => now(),
                'contrasenia' => Hash::make('12345678'),
                'idTipoPersonalSalud' => 2,
            ],
        ];

        foreach ($users as $usuarioData) {
            $user = Usuario::firstOrCreate(
                ['runUsuario' => $usuarioData['runUsuario']],
                $usuarioData
            );

            if ($user->idTipoPersonalSalud == 1) {
                $user->assignRole('Técnico RAD');
            } elseif ($user->idTipoPersonalSalud == 2) {
                $user->assignRole('Coordinador Campo Clínico');
            } elseif ($user->idTipoPersonalSalud == 3) {
                $user->assignRole('Encargado Campo Clínico');
            }
        }
    }
}
