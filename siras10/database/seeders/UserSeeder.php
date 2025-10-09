<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creamos el usuario Administrador
        $admin = Usuario::create([
            'runUsuario' => '1-1',
            'nombreUsuario' => 'admin',
            'apellidoPaterno' => 'Villouta',
            'apellidoMaterno' => 'Urra',
            'correo' => 'admin@siras.com',
            'contrasenia' => Hash::make('admin'),
            'fechaCreacion' => now(),
        ]);

        // Le asignamos el rol de 'Admin'
        // Este rol ya debe existir gracias al InitialSetupSeeder
        $admin->assignRole('Admin');
    }
}
