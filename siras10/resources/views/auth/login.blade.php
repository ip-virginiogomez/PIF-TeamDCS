<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión - SIRAS</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts y Estilos -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">

    <!-- Fondo con gradiente y patrón -->
    <div class="absolute inset-0 bg-gradient-to-br from-dcs-blue-600 via-dcs-blue-700 to-dcs-blue-900"></div>
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PHBhdGggZD0iTTM2IDM0djItaDJWMzZoLTJ6bTAgNGgtMnYyaDJ2LTJ6bS0yLTJoMnYtMmgtMnYyem0wIDBoLTJ2Mmgydi0yem0wIDRoLTJ2Mmgydi0yem0wLThoLTJ2Mmgydi0yem0wIDBoMnYtMmgtMnYyem0wLTRoMnYtMmgtMnYyem0tMiAwaDJ2LTJoLTJ2MnoiLz48L2c+PC9nPjwvc3ZnPg==')] opacity-20"></div>

    <!-- Contenedor principal -->
    <div class="relative min-h-screen flex flex-col items-center justify-center px-4 py-8">

        <!-- Tarjeta de Login -->
        <div class="w-full max-w-md p-8 space-y-6 bg-white/95 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/20">
            
            <!-- Encabezado de la tarjeta -->
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900">
                    Bienvenido a SIRAS
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Ingresa tus credenciales para acceder al sistema
                </p>
            </div>

            <!-- Mensaje de estado (ej. link de reseteo de contraseña enviado) -->
            <x-auth-session-status class="mb-4 text-green-600" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Correo Electrónico -->
                <div>
                    <x-input-label for="correo" value="Correo Electrónico" class="text-gray-700 font-medium" />
                    <div class="relative mt-2">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <x-text-input id="correo" class="block w-full pl-10 pr-3 py-3 bg-gray-100 border-gray-300 text-gray-900 placeholder-gray-500 focus:border-dcs-blue-500 focus:ring-dcs-blue-500 focus:bg-white rounded-lg transition-colors" type="email" name="correo" :value="old('correo')" placeholder="tu.correo@ejemplo.com" required autofocus autocomplete="username" />
                    </div>
                    <x-input-error :messages="$errors->get('correo')" class="mt-2" />
                </div>

                <!-- Contraseña -->
                <div>
                    <x-input-label for="password" value="Contraseña" class="text-gray-700 font-medium" />
                    <div class="relative mt-2">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <x-text-input id="password" class="block w-full pl-10 pr-3 py-3 bg-gray-100 border-gray-300 text-gray-900 placeholder-gray-500 focus:border-dcs-blue-500 focus:ring-dcs-blue-500 focus:bg-white rounded-lg transition-colors"
                                        type="password"
                                        name="password"
                                        placeholder="••••••••"
                                        required autocomplete="current-password" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Recordarme y Olvidé mi contraseña -->
                <div class="flex items-center justify-between pt-2">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" class="rounded bg-white border-gray-300 text-dcs-blue-600 shadow-sm focus:ring-dcs-blue-500 focus:ring-offset-0" name="remember">
                        <span class="ms-2 text-sm text-gray-700 font-medium">{{ __('Recordarme') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-dcs-blue-600 hover:text-dcs-blue-800 font-medium transition-colors duration-150" href="{{ route('password.request') }}">
                            {{ __('¿Olvidaste tu contraseña?') }}
                        </a>
                    @endif
                </div>

                <!-- Botón de Iniciar Sesión -->
                <div class="pt-2">
                    <x-primary-button class="w-full justify-center text-base py-3 shadow-lg hover:shadow-xl !text-white">
                        <svg class="w-5 h-5 mr-2 stroke-current text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        <span class="text-white font-semibold">{{ __('Iniciar Sesión') }}</span>
                    </x-primary-button>
                </div>
            </form>
        </div>
        
        <!-- Volver al inicio -->
        <div class="text-center mt-8">
            <a href="{{ route('inicio') }}" class="inline-flex items-center text-sm text-white/80 hover:text-white font-medium transition-colors duration-150 group">
                <svg class="w-4 h-4 mr-2 transition-transform duration-150 group-hover:-translate-x-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver a la página de inicio
            </a>
        </div>

        <!-- Footer minimalista -->
        <div class="absolute bottom-4 left-0 right-0 text-center">
            <p class="text-xs text-white/60">
                © {{ date('Y') }} SIRAS - Sistema Informático de Rotaciones Académicas de Salud
            </p>
        </div>

    </div>
</body>
</html>