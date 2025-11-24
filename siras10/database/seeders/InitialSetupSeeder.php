<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Submenu;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InitialSetupSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Limpiar caché
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Crear Menús, Submenús y Permisos
        $menus = [
            'Gestión Académica' => [
                'alumnos' => ['create', 'read', 'update', 'delete'],
                'docentes' => ['create', 'read', 'update', 'delete'],
                'carreras' => ['create', 'read', 'update', 'delete'],
                'sede-carrera' => ['create', 'read', 'update', 'delete'],
                'asignaturas' => ['create', 'read', 'update', 'delete'],
            ],
            'Gestión Centro Formador' => [
                'centros-formadores' => ['create', 'read', 'update', 'delete'],
                'tipos-centro-formador' => ['create', 'read', 'update', 'delete'],
                'sede' => ['create', 'read', 'update', 'delete'],
                'convenios' => ['create', 'read', 'update', 'delete'],
            ],
            'Gestión de Salud' => [
                'centro-salud' => ['create', 'read', 'update', 'delete'],
                'unidad-clinicas' => ['create', 'read', 'update', 'delete'],
            ],
            'Gestión de Prácticas' => [
                'periodos' => ['create', 'read', 'update', 'delete'],
                'cupo-ofertas' => ['create', 'read', 'update', 'delete'],
                'tipos-practica' => ['create', 'read', 'update', 'delete'],
                'cupo-distribuciones' => ['create', 'read', 'update', 'delete'],
                'grupos' => ['create', 'read', 'update', 'delete'],
            ],
            'Gestión de Usuarios' => [
                'usuarios' => ['create', 'read', 'update', 'delete'],
                'roles' => ['create', 'read', 'update', 'delete'],
                'asignaciones' => ['create', 'read', 'update', 'delete'],
                'permisos' => ['read'],
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

        // 3. Crear Roles
        $rolCoordinador = Role::create(['name' => 'Coordinador Campo Clínico']);
        $rolEncargado = Role::create(['name' => 'Encargado Campo Clínico']);
        $rolTecnicoRAD = Role::create(['name' => 'Técnico RAD']);
        $rolAdmin = Role::create(['name' => 'Admin']);

        // 4. Asignar todos los permisos existentes al rol de Admin
        $rolAdmin->givePermissionTo(Permission::all());
    }
}
