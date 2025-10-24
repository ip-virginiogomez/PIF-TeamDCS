<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use app\Models\TipoPractica;

class TipoPracticaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $tiposPractica = [
            [
                'nombrePractica' => 'Práctica Curricular',
                'fechaCreacion' => now(),
            ],
            [
                'nombrePractica' => 'Internado',
                'fechaCreacion' => now(),
            ]
        ];
        foreach ($tiposPractica as $tipo) {
            \App\Models\TipoPractica::create($tipo);
        }
    }
}
