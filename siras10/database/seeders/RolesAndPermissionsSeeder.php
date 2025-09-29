<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        // Crear permisos de administrador
        Permission::create(['name' => 'gestionar-usuarios']);
        Permission::create(['name' => 'gestionar-roles-y-permisos']);
        Permission::create(['name' => 'gestionar-centros-formadores']);
        Permission::create(['name' => 'gestionar-tipos-centro-formador']);
        // Crear permisos de Coordinador de Campo Clínico
        Permission::create(['name' => 'gestionar-alumnos']);
        Permission::create(['name' => 'gestionar-carreras']);
        Permission::create(['name' => 'gestionar-asignaturas']);
        Permission::create(['name' => 'gestionar-docentes']);
        Permission::create(['name' => 'gestionar-grupos']);
        // Crear permisos de Coordinador de Campo Clínico
        Permission::create(['name' => 'ver-ofertas-practicas']);
        Permission::create(['name' => 'gestionar-dossiers']);
        Permission::create(['name' => 'gestionar-cupos']);
        Permission::create(['name' => 'ver-carreras']);
        Permission::create(['name' => 'ver-centros-formadores']);
        Permission::create(['name' => 'ver-malla-curricular']);
        Permission::create(['name' => 'ver-pautas-evaluacion']);
        Permission::create(['name' => 'ver-demanda-cupos']);
        Permission::create(['name' => 'gestionar-convenios']);
        Permission::create(['name' => 'exportar-datos']);

        // Rol Encargado Campo Clínico
        $rolEncargado = Role::create(['name' => 'Encargado Campo Clínico']);
        $rolEncargado->givePermissionTo([
            'gestionar-alumnos',
            'gestionar-carreras',
            'gestionar-asignaturas',
            'gestionar-docentes',
            'gestionar-grupos',
            'ver-carreras',
        ]);

        // Rol Coordinador de Campo Clínico
        $rolCoordinador = Role::create(['name' => 'Coordinador Campo Clínico']);
        $rolCoordinador->givePermissionTo([
            'ver-ofertas-practicas',
            'gestionar-dossiers',
            'gestionar-cupos',
            'ver-carreras',

            'ver-centros-formadores',
            'ver-malla-curricular',
            'ver-pautas-evaluacion',
            'ver-demanda-cupos',
            'gestionar-convenios',
            'exportar-datos',
        ]);

        // Rol Admin (obtiene todos los permisos existentes)
        $rolAdmin = Role::create(['name' => 'Admin']);
        $rolAdmin->givePermissionTo(Permission::all());
    }
}
