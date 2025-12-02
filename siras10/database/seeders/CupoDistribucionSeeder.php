<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CupoDistribucionSeeder extends Seeder
{
    public function run(): void
    {
        $cupos = [
            [
                'idCupoOferta' => 1,
                'idSedeCarrera' => 1,
                'cantCupos' => 2,
            ],
        ];
        foreach ($cupos as $cupo) {
            \App\Models\CupoDistribucion::create($cupo);
        }
    }
}
