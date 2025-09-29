<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Submenu;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class MenuAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $estructuraMenu = [
            'Gestión Académica' => [
                'alumnos',
                'carreras',
                'asignaturas',
                'docentes',
            ],
            'Gestión Centro Formador' => [
                'centros-formadores',
                'tipos-centro-formador',
            ],
            'Gestión de Usuarios' => [
                'usuarios',
                'roles',
            ],
        ];

        $acciones = ['create', 'read', 'update', 'delete'];

        foreach ($estructuraMenu as $nombreMenu => $submenus) {

            $menu = Menu::firstOrCreate(['nombreMenu' => $nombreMenu]);

            foreach ($submenus as $nombreSubmenu) {

                Submenu::firstOrCreate([
                    'nombreSubmenu' => $nombreSubmenu,
                    'idMenu' => $menu->idMenu,
                ]);

                foreach ($acciones as $accion) {
                    Permission::firstOrCreate(['name' => $nombreSubmenu.'.'.$accion]);
                }
            }
        }

        $rolAdmin = \Spatie\Permission\Models\Role::where('name', 'Admin')->first();
        if ($rolAdmin) {
            $rolAdmin->givePermissionTo(Permission::all());
        }
    }
}
