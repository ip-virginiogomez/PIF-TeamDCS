<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:usuarios.read')->only('index');
        $this->middleware('can:usuarios.create')->only(['create', 'store']);
        $this->middleware('can:usuarios.update')->only(['edit', 'update']);
        $this->middleware('can:usuarios.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $sortBy = $request->query('sort_by', 'runUsuario');
        $sortDirection = $request->query('sort_direction', 'asc');

        $usuarios = Usuario::with('roles')
            ->orderBy($sortBy, $sortDirection)
            ->paginate(10);

        $roles = Role::all();

        if ($request->ajax()) {
            return view('usuarios._tabla', compact('usuarios', 'sortBy', 'sortDirection'));
        }

        return view('usuarios.index', compact('usuarios', 'roles', 'sortBy', 'sortDirection'));
    }

    public function create()
    {
        $roles = Role::all();

        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'runUsuario' => 'required|string|max:10|unique:usuarios,runUsuario',
            'nombreUsuario' => 'required|string|max:45',
            'apellidoPaterno' => 'required|string|max:45',
            'apellidoMaterno' => 'nullable|string|max:45',
            'correo' => 'required|email|max:45|unique:usuarios,correo',
            'telefono' => 'nullable|string|max:20',
            'contrasenia' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
        ]);

        $usuario = Usuario::create([
            'runUsuario' => $validated['runUsuario'],
            'nombreUsuario' => $validated['nombreUsuario'],
            'apellidoPaterno' => $validated['apellidoPaterno'],
            'apellidoMaterno' => $validated['apellidoMaterno'] ?? null,
            'correo' => $validated['correo'],
            'telefono' => $validated['telefono'] ?? null,
            'contrasenia' => Hash::make($validated['contrasenia']),
            'fechaCreacion' => now(),
        ]);

        $usuario->syncRoles($validated['roles']);

        if ($request->ajax()) {
            return response()->json(['message' => 'Usuario creado exitosamente.']);
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function edit($runUsuario)
    {
        try {
            $usuario = Usuario::with('roles')->findOrFail($runUsuario);
            $roles = Role::all();

            return response()->json([
                'usuario' => $usuario,
                'roles' => $roles,
                'usuario_roles' => $usuario->roles->pluck('name')->toArray(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Usuario no encontrado.'], 404);
        }
    }

    public function update(Request $request, $runUsuario)
    {
        $usuario = Usuario::findOrFail($runUsuario);

        $validated = $request->validate([
            'nombreUsuario' => 'required|string|max:45',
            'apellidoPaterno' => 'required|string|max:45',
            'apellidoMaterno' => 'nullable|string|max:45',
            'correo' => 'required|email|max:45|unique:usuarios,correo,'.$usuario->runUsuario.',runUsuario',
            'telefono' => 'nullable|string|max:20',
            'contrasenia' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
        ]);

        $data = [
            'nombreUsuario' => $validated['nombreUsuario'],
            'apellidoPaterno' => $validated['apellidoPaterno'],
            'apellidoMaterno' => $validated['apellidoMaterno'] ?? null,
            'correo' => $validated['correo'],
            'telefono' => $validated['telefono'] ?? null,
        ];

        if ($request->filled('contrasenia')) {
            $data['contrasenia'] = Hash::make($validated['contrasenia']);
        }

        $usuario->update($data);
        $usuario->syncRoles($validated['roles']);

        if ($request->ajax()) {
            return response()->json(['message' => 'Usuario actualizado exitosamente.']);
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy($runUsuario)
    {
        if (Auth::user()->runUsuario == $runUsuario) {
            if (request()->ajax()) {
                return response()->json(['message' => 'No puedes eliminar tu propio usuario.'], 403);
            }

            return redirect()->route('usuarios.index')->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $usuario = Usuario::findOrFail($runUsuario);
        $usuario->delete();

        if (request()->ajax()) {
            return response()->json(['message' => 'Usuario eliminado exitosamente.']);
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
