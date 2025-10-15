<?php

namespace Database\Seeders;

use App\Models\CentroSalud;
use App\Models\Ciudad;
use App\Models\TipoCentroSalud;
use Illuminate\Database\Seeder;

class CentroSaludSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Primero verificamos que existan ciudades y tipos de centro de salud
        $ciudades = Ciudad::all();
        $tiposCentro = TipoCentroSalud::all();

        if ($ciudades->isEmpty() || $tiposCentro->isEmpty()) {
            $this->command->info('Por favor, ejecuta primero los seeders de Ciudad y TipoCentroSalud');
            return;
        }

        $centrosSalud = [
            [
                'nombreCentro' => 'Nuevo Horizonte',
                'direccion' => 'Calle Aguas Calientes #2350, Villa Los Cóndores.',
                'director' => 'Carolina Arroyo Arriagada',                    // NUEVO CAMPO
                'correoDirector' => 'carolina.arroyo@dcslosangeles.cl',     // NUEVO CAMPO
                'idCiudad' => $ciudades->where('nombreCiudad', 'Los Ángeles')->first()?->idCiudad ?? 1,
                'idTipoCentroSalud' => $tiposCentro->where('acronimo', 'CESFAM')->first()?->idTipoCentroSalud ?? 1,
            ],
            [
                'nombreCentro' => 'Dos de Septiembre',
                'direccion' => 'Calle Condell #1150, población Dos de Septiembre.',
                'director' => 'Karen Maldonado Arcos',
                'correoDirector' => 'karen.maldonado@dcslosangeles.cl',
                'idCiudad' => $ciudades->where('nombreCiudad', 'Los Ángeles')->first()?->idCiudad ?? 1,
                'idTipoCentroSalud' => $tiposCentro->where('acronimo', 'CESFAM')->first()?->idTipoCentroSalud ?? 1,
            ],
            [
                'nombreCentro' => 'Norte',
                'direccion' => 'Avenida Los Ángeles #810, población Orompello.',
                'director' => 'Leslie Oberg Medina',
                'correoDirector' => 'leslie.oberg@dcslosangeles.cl',
                'idCiudad' => $ciudades->where('nombreCiudad', 'Los Ángeles')->first()?->idCiudad ?? 1,
                'idTipoCentroSalud' => $tiposCentro->where('acronimo', 'CESFAM')->first()?->idTipoCentroSalud ?? 1,
            ],
            [
                'nombreCentro' => 'Nororiente',
                'direccion' => 'Pasaje Santiago Morse #1585, Población Endesa.',
                'director' => 'Aurora Caro Riquelme',
                'correoDirector' => 'aurora.caro@dcslosangeles.cl',
                'idCiudad' => $ciudades->where('nombreCiudad', 'Los Ángeles')->first()?->idCiudad ?? 1,
                'idTipoCentroSalud' => $tiposCentro->where('acronimo', 'CESFAM')->first()?->idTipoCentroSalud ?? 1,
            ],
            [
                'nombreCentro' => 'Entre Ríos',
                'direccion' => 'Avenida Oriente #2201, Villa Parque Lauquén.',
                'director' => 'Sergio Benavides Cifuentes',
                'correoDirector' => 'sergio.benavides@dcslosangeles.cl',
                'idCiudad' => $ciudades->where('nombreCiudad', 'Los Ángeles')->first()?->idCiudad ?? 1,
                'idTipoCentroSalud' => $tiposCentro->where('acronimo', 'CESFAM')->first()?->idTipoCentroSalud ?? 1,
            ],
            [
                'nombreCentro' => 'Sur',
                'direccion' => 'Calle Juan Guzmán #437, población Escritores de Chile.',
                'director' => 'Victor Munoz Faundes',
                'correoDirector' => 'victor.munoz@dcslosangeles.cl',
                'idCiudad' => $ciudades->where('nombreCiudad', 'Los Ángeles')->first()?->idCiudad ?? 1,
                'idTipoCentroSalud' => $tiposCentro->where('acronimo', 'CESFAM')->first()?->idTipoCentroSalud ?? 1,
            ],
            [
                'nombreCentro' => 'Rural Santa Fe',
                'direccion' => 'Calle Noemí Escobar López S/N, Santa Fe.',
                'director' => 'Mauricio Belmar Polanco',
                'correoDirector' => 'mauricio.belmar@virginiogomez.cl',
                'idCiudad' => $ciudades->where('nombreCiudad', 'Santa Fe')->first()?->idCiudad ?? 2,
                'idTipoCentroSalud' => $tiposCentro->where('acronimo', 'CESFAM')->first()?->idTipoCentroSalud ?? 1,
            ],
        ];

        foreach ($centrosSalud as $centro) {
            CentroSalud::create($centro);
        }

        $this->command->info('CentroSalud seeders ejecutados exitosamente: '.count($centrosSalud).' centros creados.');
    }
}
