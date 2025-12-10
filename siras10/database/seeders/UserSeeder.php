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

    }
}
