<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Llamamos a los seeders que hemos creado en el orden que necesitamos
        $this->call([
            RolesAndPermissionsSeeder::class,
            MenuAndPermissionsSeeder::class,
            CiudadSeeder::class,
            TipoCentroSaludSeeder::class,
            CarreraSeeder::class,
            TipoCentroFormadorSeeder::class,
            CentroFormadorSeeder::class,
        ]);
    }
}
