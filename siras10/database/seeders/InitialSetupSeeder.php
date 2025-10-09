<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Submenu;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

// 1. Asegúrate de que el nombre de la clase coincida con el nombre del archivo
class InitialSetupSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Lógica para crear Menús y Permisos (traída del otro seeder)
        $menus = [
            'Gestión Académica' => [
                'alumnos' => ['create', 'read', 'update', 'delete'],
                'carreras' => ['create', 'read', 'update', 'delete'],
                'asignaturas' => ['create', 'read', 'update', 'delete'],
                'docentes' => ['create', 'read', 'update', 'delete'],
            ],
            'Gestión de Centros' => [
                'centros-formadores' => ['create', 'read', 'update', 'delete'],
                'tipos-centro-formador' => ['create', 'read', 'update', 'delete'],
            ],
            'Gestión de Usuarios' => [
                'usuarios' => ['create', 'read', 'update', 'delete'],
                'roles' => ['create', 'read', 'update', 'delete'],
            ],
        ];

        foreach ($menus as $nombreMenu => $submenus) {
            $menu = Menu::firstOrCreate(['nombreMenu' => $nombreMenu]);
            foreach ($submenus as $nombreSubmenu => $acciones) {
                Submenu::firstOrCreate([
                    'nombreSubmenu' => $nombreSubmenu,
                    'idMenu' => $menu->idMenu,
                ]);
                foreach ($acciones as $accion) {
                    Permission::firstOrCreate(['name' => $nombreSubmenu.'.'.$accion]);
                }
            }
        }

        // 3. Crear Roles (como ya lo tenías)
        $rolCoordinador = Role::create(['name' => 'Coordinador Campo Clínico']);
        $rolEncargado = Role::create(['name' => 'Encargado Campo Clínico']);
        $rolAdmin = Role::create(['name' => 'Admin']);

        // 4. Asignar Permisos a Roles
        $rolAdmin->givePermissionTo(Permission::all());

    }
}
