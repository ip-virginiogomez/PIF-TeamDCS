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

    public function index()
    {
        $usuarios = Usuario::with('roles')->paginate(10);

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Role::all();

        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'runUsuario' => 'required|string|max:10|unique:usuarios,runUsuario',
            'nombreUsuario' => 'required|string|max:45|unique:usuarios,nombreUsuario',
            'correo' => 'required|email|max:45|unique:usuarios,correo',
            'contrasenia' => 'required|string|min:8|confirmed',
            'nombres' => 'required|string|max:45',
            'apellidoPaterno' => 'required|string|max:45',
            'apellidoMaterno' => 'required|string|max:45',
            'roles' => 'required|array',
        ]);

        $usuario = Usuario::create([
            'runUsuario' => $request->runUsuario,
            'nombreUsuario' => $request->nombreUsuario,
            'correo' => $request->correo,
            'contrasenia' => Hash::make($request->contrasenia),
            'nombres' => $request->nombres,
            'apellidoPaterno' => $request->apellidoPaterno,
            'apellidoMaterno' => $request->apellidoMaterno,
            'fechaCreacion' => now(),
        ]);

        $usuario->assignRole($request->roles);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function edit(Usuario $usuario)
    {
        $roles = Role::all();

        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, Usuario $usuario)
    {

        $request->validate([
            'runUsuario' => 'required|string|max:10|unique:usuarios,runUsuario,'.$usuario->runUsuario.',runUsuario',
            'nombreUsuario' => 'required|string|max:45|unique:usuarios,nombreUsuario,'.$usuario->runUsuario.',runUsuario',
            'correo' => 'required|email|max:45|unique:usuarios,correo,'.$usuario->runUsuario.',runUsuario',
            'contrasenia' => 'nullable|string|min:8|confirmed',
            'nombres' => 'required|string|max:45',
            'apellidoPaterno' => 'required|string|max:45',
            'apellidoMaterno' => 'required|string|max:45',
            'roles' => 'required|array',
        ]);

        $data = $request->except('contrasenia');

        if ($request->filled('contrasenia')) {
            $data['contrasenia'] = Hash::make($request->contrasenia);
        }

        $usuario->update($data);

        $usuario->syncRoles($request->roles);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(Usuario $usuario)
    {
        if (auth()->user()->runUsuario == $usuario->runUsuario) {
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
