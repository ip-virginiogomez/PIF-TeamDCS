<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Contraseña - SIRAS</title>

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

        <!-- Tarjeta de Recuperación -->
        <div class="w-full max-w-md p-8 space-y-6 bg-white/95 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/20">
            
            <!-- Encabezado de la tarjeta -->
            <div class="text-center">
                <div class="mx-auto w-16 h-16 bg-dcs-blue-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-dcs-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">
                    ¿Olvidaste tu contraseña?
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    No hay problema. Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
                </p>
            </div>

            <!-- Mensaje de estado -->
            <x-auth-session-status class="mb-4 p-4 bg-green-50 border-2 border-green-600 text-green-800 rounded-lg text-sm font-semibold" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <!-- Correo Electrónico -->
                <div>
                    <x-input-label for="email" value="Correo Electrónico" class="text-gray-700 font-medium" />
                    <div class="relative mt-2">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <x-text-input id="email" class="block w-full pl-10 pr-3 py-3 bg-gray-100 border-gray-300 text-gray-900 placeholder-gray-500 focus:border-dcs-blue-500 focus:ring-dcs-blue-500 focus:bg-white rounded-lg transition-colors" type="email" name="email" :value="old('email')" placeholder="tu.correo@ejemplo.com" required autofocus />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Botones -->
                <div class="pt-2">
                    <x-primary-button class="w-full justify-center text-base py-3 shadow-lg hover:shadow-xl !text-white">
                        <svg class="w-5 h-5 mr-2 stroke-current text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="text-white font-semibold">Enviar enlace de recuperación</span>
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
