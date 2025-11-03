<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión - SIRAS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts y Estilos -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-dcs-blue-900">

    <!-- Fondo con gradiente -->
    <div class="absolute inset-0 bg-gradient-to-br from-dcs-blue-800 to-dcs-blue-900"></div>

    <!-- Contenedor principal -->
    <div class="relative min-h-screen flex flex-col items-center justify-center px-4">

        <!-- Tarjeta de Login -->
        <div class="w-full max-w-md p-8 space-y-6 bg-dcs-blue-900/50 backdrop-blur-lg rounded-xl shadow-2xl">
            
            <!-- Encabezado de la tarjeta -->
            <div class="text-center">
                <h2 class="text-3xl font-bold text-white">
                    Bienvenido
                </h2>
                <p class="mt-2 text-sm text-gray-400">
                    Ingresa tus credenciales para acceder a SIRAS.
                </p>
            </div>

            <!-- Mensaje de estado (ej. link de reseteo de contraseña enviado) -->
            <x-auth-session-status class="mb-4 text-green-400" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Correo Electrónico -->
                <div>
                    <x-input-label for="correo" value="Correo Electrónico" class="text-gray-300" />
                    <x-text-input id="correo" class="block mt-1 w-full bg-dcs-blue-800/50 border-dcs-blue-700 text-white focus:border-green-500 focus:ring-green-500" type="email" name="correo" :value="old('correo')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('correo')" class="mt-2" />
                </div>

                <!-- Contraseña -->
                <div class="mt-4">
                    <x-input-label for="password" value="Contraseña" class="text-gray-300" />
                    <x-text-input id="password" class="block mt-1 w-full bg-dcs-blue-800/50 border-dcs-blue-700 text-white focus:border-green-500 focus:ring-green-500"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Recordarme y Olvidé mi contraseña -->
                <div class="flex items-center justify-between mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded bg-dcs-blue-800 border-dcs-blue-700 text-green-600 shadow-sm focus:ring-green-500 focus:ring-offset-dcs-blue-900" name="remember">
                        <span class="ms-2 text-sm text-gray-400">{{ __('Recordarme') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-400 hover:text-green-400 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" href="{{ route('password.request') }}">
                            {{ __('¿Olvidaste tu contraseña?') }}
                        </a>
                    @endif
                </div>

                <!-- Botón de Iniciar Sesión -->
                <div class="flex items-center justify-end pt-4 mt-4">
                    <x-primary-button class="w-full justify-center text-base py-3 bg-green-600 hover:bg-green-500 focus:bg-green-700 active:bg-green-800 focus:ring-green-500">
                        {{ __('Acceder') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
        
        <div class="text-center mt-8">
            <a href="{{ route('inicio') }}" class="text-sm text-gray-400 hover:text-white transition-colors">
                ← Volver a la página de inicio
            </a>
        </div>

    </div>
</body>
</html>