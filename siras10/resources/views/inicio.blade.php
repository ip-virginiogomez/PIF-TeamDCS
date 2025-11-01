<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bienvenido a SIRAS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts y Estilos -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-dcs-blue-900">

    <!-- ===== INICIO: BARRA DE NAVEGACIÓN ===== -->
    <header class="absolute top-0 left-0 w-full z-30">
        <nav class="bg-dcs-blue-900/50 backdrop-blur-sm py-3">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <!-- Logos a la izquierda -->
                    <div class="flex items-center gap-x-6 sm:gap-x-8">
                        <a href="https://admision.virginiogomez.cl/" target="_blank" rel="noopener noreferrer" title="Instituto Profesional Virginio Gómez">
                            <img class="max-h-12 object-contain transition-transform duration-200 hover:scale-105" 
                                src="{{ asset('images/ipvg.png') }}" 
                                alt="Logo Instituto Profesional Virginio Gómez">
                        </a>
                        <a href="https://dcslosangeles.cl/" target="_blank" rel="noopener noreferrer" title="Dirección Comunal de Salud Los Ángeles">
                            <img class="max-h-16 object-contain transition-transform duration-200 hover:scale-105" 
                                src="{{ asset('images/dcs.jpg') }}" 
                                alt="Logo Dirección Comunal de Salud Los Ángeles">
                        </a>
                    </div>

                    <!-- Botón de Iniciar Sesión a la derecha -->
                    <div>
                        <a href="{{ route('login') }}" 
                        class="rounded-md bg-green-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 transition-colors duration-150">
                            Iniciar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <!-- ===== FIN: BARRA DE NAVEGACIÓN ===== -->

    <div class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <!-- Fondo con gradiente sutil -->
        <div class="absolute inset-0 bg-gradient-to-br from-dcs-blue-800 to-dcs-blue-900"></div>

        <main class="relative z-10 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                
                <!-- Columna de Texto -->
                <div class="text-center lg:text-left">
                    <h1 class="text-4xl font-bold text-white tracking-tight sm:text-5xl lg:text-6xl">
                        Bienvenido a <span class="text-green-400">SIRAS</span>
                    </h1>
                    <p class="mt-6 text-lg leading-8 text-gray-300 max-w-lg mx-auto lg:mx-0">
                        La plataforma líder para la gestión de rotaciones académicas en el área de la salud.
                    </p>
                    
                    <!-- Información adicional -->
                    <div class="mt-8 border-l-4 border-green-500 pl-6">
                        <p class="text-gray-300">
                            Optimizamos la asignación de cupos, centralizamos la gestión de documentos y fortalecemos la comunicación entre universidades y centros de salud.
                        </p>
                    </div>
                </div>

                <!-- Columna de Imagen -->
                <div class="flex justify-center">
                    <img class="rounded-lg shadow-2xl w-full max-w-md object-cover" 
                        src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?q=80&w=2070&auto=format&fit=crop" 
                        alt="Profesionales de la salud colaborando">
                </div>

            </div>
        </main>
    </div>

    <!-- ===== INICIO: SECCIÓN DE CARACTERÍSTICAS ===== -->
    <div class="bg-dcs-blue-900 py-24 sm:py-32">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base font-semibold leading-7 text-green-400">Todo en un solo lugar</h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">La plataforma definitiva para la gestión de prácticas</p>
                <p class="mt-6 text-lg leading-8 text-gray-300">
                    SIRAS simplifica cada etapa del proceso de rotación académica, desde la oferta de cupos hasta el seguimiento final.
                </p>
            </div>
            <div class="mt-16 max-w-2xl mx-auto sm:mt-20 lg:mt-24 lg:max-w-none">
                <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
                    
                    <!-- Característica 1 -->
                    <div class="flex flex-col">
                        <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-white">
                            <svg class="h-5 w-5 flex-none text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12.75 15l3-3m0 0l-3-3m3 3h-7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            Gestión de Cupos Centralizada
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-300">
                            <p class="flex-auto">Los centros de salud publican sus cupos disponibles, la direccion comunal de salud los distribuye y los centros formadores asignan a sus estudiantes de manera eficiente y transparente.</p>
                        </dd>
                    </div>

                    <!-- Característica 2 -->
                    <div class="flex flex-col">
                        <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-white">
                            <svg class="h-5 w-5 flex-none text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3.75 9.75h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5M21 6.75H3a.75.75 0 00-.75.75v10.5c0 .414.336.75.75.75h18a.75.75 0 00.75-.75V7.5a.75.75 0 00-.75-.75z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            Documentación Digital
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-300">
                            <p class="flex-auto">Centraliza y gestiona todos los documentos requeridos para las prácticas, como certificados, seguros y convenios, accesibles en cualquier momento.</p>
                        </dd>
                    </div>

                    <!-- Característica 3 -->
                    <div class="flex flex-col">
                        <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-white">
                            <svg class="h-5 w-5 flex-none text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M10.5 6h9.75M10.5 12h9.75M10.5 18h9.75M3.75 6.75h.008v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12.75h.008v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 18.75h.008v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            Comunicación y Seguimiento
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-300">
                            <p class="flex-auto">Facilita la comunicación entre docentes, tutores clínicos y estudiantes, permitiendo un seguimiento continuo del progreso académico y asistencial.</p>
                        </dd>
                    </div>

                </dl>
            </div>
        </div>
    </div>
    <!-- ===== FIN: SECCIÓN DE CARACTERÍSTICAS ===== -->

</body>
</html>