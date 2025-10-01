<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TipoCentroFormadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Universidad'],
            ['nombre' => 'Instituto Profesional'],
            ['nombre' => 'Centro de Formación Técnica'],
        ];
    }
}
