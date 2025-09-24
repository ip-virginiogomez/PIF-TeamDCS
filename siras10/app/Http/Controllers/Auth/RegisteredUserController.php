<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario; // <-- CAMBIO 1: Usar tu modelo
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // CAMBIO 2: Validar los campos de tu tabla y formulario
        $request->validate([
            'runUsuario' => ['required', 'string', 'max:10', 'unique:'.Usuario::class],
            'nombreUsuario' => ['required', 'string', 'max:45'],
            'correo' => ['required', 'string', 'lowercase', 'email', 'max:45', 'unique:'.Usuario::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // CAMBIO 3: Crear el usuario con las columnas correctas
        $user = Usuario::create([
            'runUsuario' => $request->runUsuario,
            'nombreUsuario' => $request->nombreUsuario,
            'correo' => $request->correo,
            'contrasenia' => Hash::make($request->password), // Tu columna se llama 'contrasenia'
            'nombres' => $request->nombreUsuario,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
