<?php

namespace Database\Seeders;

use App\Models\TipoPersonalSalud;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TipoPersonalSaludSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $tipos = [
            [
                'cargo' => 'Coordinador Técnico RAD',
                'fechaCreacion' => $now,
                'descripcion' => 'Profesional encargado de coordinar las actividades técnicas relacionadas con la Red de Atención Domiciliaria (RAD).',
            ],
            [
                'cargo' => 'Coordinador de Campos Clínicos',
                'fechaCreacion' => $now,
                'descripcion' => 'Profesional encargado de coordinar las actividades relacionadas con los campos clínicos en un centro formador.',
            ],
            [
                'cargo' => 'Encargado de Campos Clínicos',
                'fechaCreacion' => $now,
                'descripcion' => 'Profesional encargado de coordinar las actividades relacionadas con los campos clínicos en un centro de salud.',

            ],
        ];

        foreach ($tipos as $tipo) {
            TipoPersonalSalud::create($tipo);
        }
    }
}
