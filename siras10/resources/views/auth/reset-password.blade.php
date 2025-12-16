<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restablecer Contraseña - SIRAS</title>

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

        <!-- Tarjeta de Restablecer Contraseña -->
        <div class="w-full max-w-md p-8 space-y-6 bg-white/95 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/20">
            
            <!-- Encabezado de la tarjeta -->
            <div class="text-center">
                <div class="mx-auto w-16 h-16 bg-dcs-blue-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-dcs-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">
                    Restablecer Contraseña
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Ingresa tu nueva contraseña para recuperar el acceso a tu cuenta
                </p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address (readonly) -->
                <div>
                    <x-input-label for="email" value="Correo Electrónico" class="text-gray-700 font-medium" />
                    <div class="relative mt-2">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <x-text-input id="email" class="block w-full pl-10 pr-3 py-3 bg-gray-50 border-gray-300 text-gray-600 rounded-lg cursor-not-allowed" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" readonly />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Nueva Contraseña -->
                <div>
                    <x-input-label for="password" value="Nueva Contraseña" class="text-gray-700 font-medium" />
                    <div class="relative mt-2">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <x-text-input id="password" class="block w-full pl-10 pr-3 py-3 bg-gray-100 border-gray-300 text-gray-900 placeholder-gray-500 focus:border-dcs-blue-500 focus:ring-dcs-blue-500 focus:bg-white rounded-lg transition-colors" type="password" name="password" placeholder="Mínimo 8 caracteres" required autocomplete="new-password" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirmar Contraseña -->
                <div>
                    <x-input-label for="password_confirmation" value="Confirmar Nueva Contraseña" class="text-gray-700 font-medium" />
                    <div class="relative mt-2">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <x-text-input id="password_confirmation" class="block w-full pl-10 pr-3 py-3 bg-gray-100 border-gray-300 text-gray-900 placeholder-gray-500 focus:border-dcs-blue-500 focus:ring-dcs-blue-500 focus:bg-white rounded-lg transition-colors" type="password" name="password_confirmation" placeholder="Repite tu contraseña" required autocomplete="new-password" />
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Botón de Restablecer -->
                <div class="pt-2">
                    <x-primary-button class="w-full justify-center text-base py-3 shadow-lg hover:shadow-xl !text-white">
                        <svg class="w-5 h-5 mr-2 stroke-current text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-white font-semibold">Restablecer Contraseña</span>
                    </x-primary-button>
                </div>
            </form>
        </div>

        <!-- Volver al login -->
        <div class="text-center mt-8">
            <a href="{{ route('login') }}" class="inline-flex items-center text-sm text-white/80 hover:text-white font-medium transition-colors duration-150 group">
                <svg class="w-4 h-4 mr-2 transition-transform duration-150 group-hover:-translate-x-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver al inicio de sesión
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
