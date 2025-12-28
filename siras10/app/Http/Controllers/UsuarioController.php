<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Intervention\Image\Laravel\Facades\Image;

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
        $sortDirection = $request->query('sort_direction', 'desc');
        $search = $request->query('search');

        $query = Usuario::with('roles');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('runUsuario', 'like', "%{$search}%")
                    ->orWhere('nombreUsuario', 'like', "%{$search}%")
                    ->orWhere('apellidoPaterno', 'like', "%{$search}%")
                    ->orWhere('apellidoMaterno', 'like', "%{$search}%")
                    ->orWhereHas('roles', function ($qRole) use ($search) {
                        $qRole->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $usuarios = $query->orderBy($sortBy, $sortDirection)
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
            'runUsuario' => [
                'required',
                'string',
                'max:10',
                \Illuminate\Validation\Rule::unique('usuarios', 'runUsuario')->whereNull('deleted_at'),
            ],
            'nombreUsuario' => 'required|string|max:45',
            'apellidoPaterno' => 'required|string|max:45',
            'apellidoMaterno' => 'nullable|string|max:45',
            'correo' => [
                'required',
                'email',
                'max:45',
                \Illuminate\Validation\Rule::unique('usuarios', 'correo')->whereNull('deleted_at'),
            ],
            'telefono' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'contrasenia' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
        ]);

        $usuarioData = [
            'runUsuario' => $validated['runUsuario'],
            'nombreUsuario' => $validated['nombreUsuario'],
            'apellidoPaterno' => $validated['apellidoPaterno'],
            'apellidoMaterno' => $validated['apellidoMaterno'] ?? null,
            'correo' => $validated['correo'],
            'telefono' => $validated['telefono'] ?? null,
            'contrasenia' => Hash::make($validated['contrasenia']),
            'fechaCreacion' => now(),
        ];

        // Manejo de foto
        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = storage_path('app/public/fotos/usuarios/' . $filename);
            if (!file_exists(storage_path('app/public/fotos/usuarios'))) {
                mkdir(storage_path('app/public/fotos/usuarios'), 0755, true);
            }
            Image::read($image)->scaleDown(width: 1024)->save($path, 90);
            $usuarioData['foto'] = 'fotos/usuarios/' . $filename;
        }

        // Verificar si existe un usuario eliminado (Soft Delete)
        $usuario = Usuario::withTrashed()->where('runUsuario', $usuarioData['runUsuario'])->first();

        if ($usuario) {
            if ($usuario->trashed()) {
                $usuario->restore();
            }
            // Actualizar datos, incluyendo contraseña si se proporciona
            $usuario->update($usuarioData);
        } else {
            $usuario = Usuario::create($usuarioData);
        }

        $usuario->syncRoles($validated['roles']);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'Usuario creado exitosamente.',
                'usuario' => $usuario->load('roles'),
            ], 201);
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
            'correo' => [
                'required',
                'email',
                'max:45',
                \Illuminate\Validation\Rule::unique('usuarios', 'correo')->ignore($usuario->runUsuario, 'runUsuario')->whereNull('deleted_at'),
            ],
            'telefono' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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

        // Manejo de foto
        if ($request->hasFile('foto')) {
            // Eliminar foto anterior si existe
            if ($usuario->foto) {
                Storage::disk('public')->delete($usuario->foto);
            }
            $image = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = storage_path('app/public/fotos/usuarios/' . $filename);
            if (!file_exists(storage_path('app/public/fotos/usuarios'))) {
                mkdir(storage_path('app/public/fotos/usuarios'), 0755, true);
            }
            Image::read($image)->scaleDown(width: 1024)->save($path, 90);
            $data['foto'] = 'fotos/usuarios/' . $filename;
        }

        if ($request->filled('contrasenia')) {
            $data['contrasenia'] = Hash::make($validated['contrasenia']);
        }

        $usuario->update($data);
        $usuario->syncRoles($validated['roles']);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'Usuario actualizado exitosamente.',
                'usuario' => $usuario->fresh()->load('roles'),
            ]);
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(Request $request, $runUsuario)
    {
        if (Auth::user()->runUsuario == $runUsuario) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'No puedes eliminar tu propio usuario.'], 403);
            }

            return redirect()->route('usuarios.index')->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $usuario = Usuario::findOrFail($runUsuario);

        // Eliminar la foto si existe
        if ($usuario->foto) {
            Storage::disk('public')->delete($usuario->foto);
        }

        $usuario->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Usuario eliminado exitosamente.']);
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
