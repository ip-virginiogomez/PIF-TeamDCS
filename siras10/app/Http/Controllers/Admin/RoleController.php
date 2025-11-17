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
    public function index(Request $request)
    {
        $sortBy = $request->get('sortBy', 'name');
        $sortDirection = $request->get('sortDirection', 'asc');

        $roles = Role::orderBy($sortBy, $sortDirection)->paginate(10);

        // Esta es la comprobación correcta y robusta
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('roles._tabla', compact('roles', 'sortBy', 'sortDirection'))->render();
        }

        return view('roles.index', compact('roles', 'sortBy', 'sortDirection'));
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
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:roles,name',
            ]);

            Role::create(['name' => $validated['name']]);

            // =======================================================
            // ¡CAMBIO AQUÍ! Usamos wantsJson() que es más fiable
            // =======================================================
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Rol creado exitosamente.']);
            }

            return redirect()->route('roles.index')->with('success', 'Rol creado exitosamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // =======================================================
            // ¡CAMBIO AQUÍ! Usamos wantsJson()
            // =======================================================
            if ($request->wantsJson()) {
                return response()->json(['errors' => $e->errors()], 422);
            }
            throw $e;
        }
    }

    /**
     * Muestra el formulario para editar un rol.
     */
    public function edit(Role $role)
    {
        // =======================================================
        // ¡CAMBIO AQUÍ! Usamos wantsJson()
        // =======================================================
        if (request()->wantsJson()) {
            return response()->json([
                'role' => $role,
            ]);
        }

        return view('roles.edit', compact('role'));
    }

    /**
     * Actualiza un rol en la base de datos.
     */
    public function update(Request $request, Role $role)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:roles,name,'.$role->id,
            ]);

            $role->update(['name' => $validated['name']]);

            // =======================================================
            // ¡CAMBIO AQUÍ! Usamos wantsJson()
            // =======================================================
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Rol actualizado exitosamente.']);
            }

            return redirect()->route('roles.index')->with('success', 'Rol actualizado exitosamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // =======================================================
            // ¡CAMBIO AQUÍ! Usamos wantsJson()
            // =======================================================
            if ($request->wantsJson()) {
                return response()->json(['errors' => $e->errors()], 422);
            }
            throw $e;
        }
    }

    /**
     * Elimina un rol de la base de datos.
     */
    public function destroy(Role $role)
    {
        // Evitar que el rol de Admin sea eliminado
        if ($role->name == 'Admin') {
            // =======================================================
            // ¡CAMBIO AQUÍ! Usamos wantsJson()
            // =======================================================
            if (request()->wantsJson()) {
                return response()->json(['message' => 'No se puede eliminar el rol de Administrador.'], 403);
            }

            return back()->with('error', 'No se puede eliminar el rol de Administrador.');
        }

        $role->delete();

        // =======================================================
        // ¡CAMBIO AQUÍ! Usamos wantsJson()
        // =======================================================
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Rol eliminado exitosamente.']);
        }

        return redirect()->route('roles.index')->with('success', 'Rol eliminado exitosamente.');
    }

    // ... (El resto de tus métodos 'showPermissionMatrix', etc., están bien) ...

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

        if ($request->filled('user_run')) {
            $selectedUser = Usuario::where('runUsuario', $request->input('user_run'))->first();
        }

        return view('roles.permission-matrix', compact('roles', 'menus', 'permissions', 'selectedRole', 'selectedUser'));
    }

    public function syncPermissionsFromMatrix(Request $request)
    {
        $request->validate([
            'user_run' => 'required|exists:usuarios,runUsuario',
            'permissions' => 'nullable|array',
        ]);

        $user = Usuario::where('runUsuario', $request->input('user_run'))->firstOrFail();
        $user->syncPermissions($request->input('permissions', []));

        return redirect()->route('roles.permission_matrix', [
            'role_id' => $user->roles->first()->id ?? '',
            'user_run' => $user->runUsuario,
        ])
            ->with('success', 'Permisos actualizados para el usuario '.$user->nombres);
    }

    public function getUsersByRole(Role $role)
    {
        $users = $role->users()->select('runUsuario', 'nombreUsuario', 'apellidoPaterno')->get();

        return response()->json($users);
    }
}
