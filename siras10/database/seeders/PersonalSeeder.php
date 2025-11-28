<?php

namespace Database\Seeders;

use App\Models\Personal;
use Illuminate\Database\Seeder;

class PersonalSeeder extends Seeder
{
    public function run(): void
    {
        $personales = [
            [
                'runUsuario' => '4-4',
                'idCentroSalud' => 1,
            ],
            [
                'runUsuario' => '5-5',
                'idCentroSalud' => 3,
            ],
        ];

        foreach ($personales as $personal) {
            Personal::create($personal);
        }
    }
}
