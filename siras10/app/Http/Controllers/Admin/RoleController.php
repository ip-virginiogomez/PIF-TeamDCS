<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Muestra una lista de todos los roles.
     */
    public function index()
    {
        $roles = Role::paginate(10);

        return view('roles.index', compact('roles'));
    }

    /**
     * Muestra el formulario para crear un nuevo rol.
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Guarda un nuevo rol en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        Role::create(['name' => $request->name]);

        return redirect()->route('roles.index')->with('success', 'Rol creado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un rol.
     */
    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    /**
     * Actualiza un rol en la base de datos.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$role->id,
        ]);

        $role->update(['name' => $request->name]);

        return redirect()->route('roles.index')->with('success', 'Rol actualizado exitosamente.');
    }

    /**
     * Elimina un rol de la base de datos.
     */
    public function destroy(Role $role)
    {
        // Evitar que el rol de Admin sea eliminado
        if ($role->name == 'Admin') {
            return back()->with('error', 'No se puede eliminar el rol de Administrador.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Rol eliminado exitosamente.');
    }

    public function showPermissionMatrix(Request $request)
    {
        $roles = Role::orderBy('name')->get();
        $menus = Menu::orderBy('nombreMenu')->get();
        $permissions = Permission::all()->groupBy(fn ($p) => explode('.', $p->name)[0]);

        $selectedRole = null;
        $selectedUser = null;

        if ($request->filled('role_id')) {
            $selectedRole = Role::findById($request->input('role_id'));
        }

        // NUEVO: Si se envía un run de usuario, lo buscamos
        if ($request->filled('user_run')) {
            $selectedUser = Usuario::where('runUsuario', $request->input('user_run'))->first();
        }

        return view('roles.permission-matrix', compact('roles', 'menus', 'permissions', 'selectedRole', 'selectedUser'));
    }

    /**
     * Guarda la asignación de permisos directos para un USUARIO específico.
     */
    public function syncPermissionsFromMatrix(Request $request)
    {
        // 1. Validamos que nos llegue el RUN del usuario
        $request->validate([
            'user_run' => 'required|exists:usuarios,runUsuario',
            'permissions' => 'nullable|array',
        ]);

        // 2. Buscamos al usuario
        $user = Usuario::where('runUsuario', $request->input('user_run'))->firstOrFail();

        // 3. Sincronizamos SUS permisos directos
        // Esto no afecta los permisos que hereda de su rol.
        $user->syncPermissions($request->input('permissions', []));

        // 4. Redirigimos de vuelta a la misma página, con el rol y usuario ya seleccionados
        return redirect()->route('roles.permission_matrix', [
            'role_id' => $user->roles->first()->id ?? '',
            'user_run' => $user->runUsuario,
        ])
            ->with('success', 'Permisos actualizados para el usuario '.$user->nombres);
    }

    public function getUsersByRole(Role $role)
    {
        // Buscamos los usuarios asociados a ese rol y seleccionamos solo los datos que necesitamos
        $users = $role->users()->select('runUsuario', 'nombres', 'apellidoPaterno')->get();

        return response()->json($users);
    }
}
