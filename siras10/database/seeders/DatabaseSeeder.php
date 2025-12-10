<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            InitialSetupSeeder::class,
            // TipoPersonalSaludSeeder::class,
            UserSeeder::class,
            // PeriodoSeeder::class,
            // CiudadSeeder::class,
            // TipoCentroSaludSeeder::class,
            // CarreraSeeder::class,
            // TipoCentroFormadorSeeder::class,
            // CentroFormadorSeeder::class,
            // CoordinadorCampoClinicoSeeder::class,
            // SedeSeeder::class,
            // SedeCarreraSeeder::class,
            // CentroSaludSeeder::class,
            // PersonalSeeder::class,
            // TipoPracticaSeeder::class,
            // UnidadClinicaSeeder::class,
            // AlumnoSeeder::class,
            // AlumnoCarreraSeeder::class,
            // DocenteSeeder::class,
            // EstadoVacunaSeeder::class,
            // TipoVacunaSeeder::class,
            // CupoOfertaSeeder::class,
            // CupoOfertaHorarioSeeder::class,
            // DocenteCarreraSeeder::class,
            // AsignaturaSeeder::class,
            // CupoDistribucionSeeder::class,
            // GrupoSeeder::class,
        ]);
    }
}
