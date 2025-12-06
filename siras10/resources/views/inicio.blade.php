<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIRAS</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts y Estilos -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">

    <!-- ===== INICIO: BARRA DE NAVEGACIÓN ===== -->
<header class="absolute top-0 left-0 w-full z-30">
    <nav class="bg-dcs-blue-900 backdrop-blur-sm shadow-lg py-3 sm:py-4">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center gap-4">
                <!-- Logos a la izquierda -->
                <div class="flex items-center gap-x-3 sm:gap-x-6 lg:gap-x-8 flex-1">
                    <a href="https://admision.virginiogomez.cl/" target="_blank" rel="noopener noreferrer" title="Instituto Profesional Virginio Gómez">
                        <img class="h-10 sm:h-12 lg:h-14 object-contain transition-transform duration-200 hover:scale-105" 
                            src="{{ asset('images/ipvg.png') }}" 
                            alt="Logo Instituto Profesional Virginio Gómez">
                    </a>
                    <a href="https://dcslosangeles.cl/" target="_blank" rel="noopener noreferrer" title="Dirección Comunal de Salud Los Ángeles">
                        <img class="h-12 sm:h-14 lg:h-16 object-contain transition-transform duration-200 hover:scale-105" 
                            src="{{ asset('images/dcs.jpg') }}" 
                            alt="Logo Dirección Comunal de Salud Los Ángeles">
                    </a>
                </div>

                <!-- Botón de Iniciar Sesión a la derecha -->
                <div class="flex-shrink-0">
                    <a href="{{ route('login') }}" 
                    class="inline-block rounded-md bg-yellow-500 px-4 sm:px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-semibold !text-white shadow-lg hover:bg-yellow-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-500 transition-all duration-150 whitespace-nowrap">
                        <span class="text-white">Iniciar Sesión</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>
    <!-- ===== FIN: BARRA DE NAVEGACIÓN ===== -->

    <!-- ===== SECCIÓN HERO ===== -->
    <div class="relative min-h-screen flex items-center justify-center overflow-hidden pt-20">
        <!-- Fondo con gradiente azul -->
        <div class="absolute inset-0 bg-gradient-to-br from-dcs-blue-600 via-dcs-blue-700 to-dcs-blue-900"></div>
        
        <!-- Patrón de fondo sutil -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PHBhdGggZD0iTTM2IDM0djItaDJWMzZoLTJ6bTAgNGgtMnYyaDJ2LTJ6bS0yLTJoMnYtMmgtMnYyem0wIDBoLTJ2Mmgydi0yem0wIDRoLTJ2Mmgydi0yem0wLThoLTJ2Mmgydi0yem0wIDBoMnYtMmgtMnYyem0wLTRoMnYtMmgtMnYyem0tMiAwaDJ2LTJoLTJ2MnoiLz48L2c+PC9nPjwvc3ZnPg==')] opacity-20"></div>

        <main class="relative z-10 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                
                <!-- Columna de Texto -->
                <div class="text-center lg:text-left">
                    <h1 class="text-4xl font-bold text-white tracking-tight sm:text-5xl lg:text-6xl">
                        Bienvenido a SIRAS</span>
                    </h1>
                    
                    <p class="mt-6 text-xl leading-8 text-gray-100 max-w-lg mx-auto lg:mx-0">
                        Sistema Informático de Rotaciones Académicas de Salud
                    </p>
                    
                    <p class="mt-4 text-base leading-7 text-gray-200 max-w-lg mx-auto lg:mx-0">
                        Una plataforma diseñada para facilitar la gestión de cupos de prácticas profesionales y rotaciones entre instituciones formadoras y centros de salud.
                    </p>
                    
                    <!-- Información adicional -->
                    <div class="mt-10 border-l-4 border-yellow-400 pl-6 bg-white/10 backdrop-blur-sm p-6 rounded-r-lg">
                        <h3 class="text-lg font-semibold text-white mb-2">Nuestro Objetivo</h3>
                        <p class="text-gray-100 leading-relaxed">
                            Optimizar la asignación de cupos de práctica, centralizar la gestión de documentos y fortalecer la comunicación entre universidades, institutos y centros de salud de la comuna de Los Ángeles.
                        </p>
                    </div>
                </div>

                <!-- Columna de Imagen -->
                <div class="flex justify-center lg:justify-end">
                    <div class="relative">
                        <img class="relative rounded-xl shadow-2xl w-full max-w-md object-cover ring-1 ring-white/20" 
                            src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?q=80&w=2070&auto=format&fit=crop" 
                            alt="Profesionales de la salud colaborando">
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- ===== SECCIÓN DE CARACTERÍSTICAS (FONDO BLANCO) ===== -->
    <div class="bg-white py-24 sm:py-32">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base font-semibold leading-7 text-dcs-blue-600">Funcionalidades Principales</h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Una solución integral para la gestión de prácticas</p>
                <p class="mt-6 text-lg leading-8 text-gray-600 max-w-3xl mx-auto">
                    SIRAS facilita cada etapa del proceso de rotación de práctica, desde la publicación de la oferta inicial de cupo, hasta la asignación por centro formador y terminando en la asignación por alumnos para la práctica profesional.
                </p>
            </div>
            
            <div class="mt-16 max-w-2xl mx-auto sm:mt-20 lg:mt-24 lg:max-w-none">
                <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
                    
                    <!-- Característica 1 -->
                    <div class="flex flex-col bg-gradient-to-br from-dcs-blue-50 to-dcs-blue-100 p-8 rounded-xl hover:shadow-xl transition-all duration-200 border border-dcs-blue-200">
                        <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-dcs-blue-600 shadow-md">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            Gestión Centralizada de Cupos
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-700">
                            <p class="flex-auto">Los centros de salud publican cupos disponibles, la Dirección Comunal de Salud los distribuye estratégicamente, y los centros formadores asignan a sus estudiantes de manera eficiente.</p>
                        </dd>
                    </div>

                    <!-- Característica 2 -->
                    <div class="flex flex-col bg-gradient-to-br from-yellow-50 to-yellow-100 p-8 rounded-xl hover:shadow-xl transition-all duration-200 border border-yellow-200">
                        <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-yellow-500 shadow-md">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            Documentación Digital
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-700">
                            <p class="flex-auto">Almacena y gestiona documentos esenciales como certificados, seguros, convenios y acuerdos, manteniéndolos organizados y accesibles en cualquier momento.</p>
                        </dd>
                    </div>

                    <!-- Característica 3 -->
                    <div class="flex flex-col bg-gradient-to-br from-dcs-blue-50 to-dcs-blue-100 p-8 rounded-xl hover:shadow-xl transition-all duration-200 border border-dcs-blue-200">
                        <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-dcs-blue-600 shadow-md">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                </svg>
                            </div>
                            Seguimiento Continuo
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-700">
                            <p class="flex-auto">Facilita la coordinación entre docentes supervisores, tutores clínicos y estudiantes, permitiendo un monitoreo constante del progreso académico y práctico.</p>
                        </dd>
                    </div>

                </dl>
            </div>
        </div>
    </div>

    <!-- ===== SECCIÓN DE BENEFICIOS (FONDO AZUL CLARO) ===== -->
    <div class="bg-gradient-to-b from-dcs-blue-50 to-white py-24 sm:py-32">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:text-center">
                <h2 class="text-base font-semibold leading-7 text-dcs-blue-600">¿Por qué SIRAS?</h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    Beneficios para todas las instituciones
                </p>
            </div>
            
            <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-10 lg:max-w-none lg:grid-cols-2 lg:gap-y-16">
                    
                    <div class="relative pl-16">
                        <dt class="text-base font-semibold leading-7 text-gray-900">
                            <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-yellow-500">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            Ahorro de Tiempo
                        </dt>
                        <dd class="mt-2 text-base leading-7 text-gray-600">Reduce significativamente el tiempo dedicado a tareas administrativas manuales.</dd>
                    </div>

                    <div class="relative pl-16">
                        <dt class="text-base font-semibold leading-7 text-gray-900">
                            <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-dcs-blue-600">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                </svg>
                            </div>
                            Seguridad de Datos
                        </dt>
                        <dd class="mt-2 text-base leading-7 text-gray-600">Protección robusta de información sensible de estudiantes y docentes.</dd>
                    </div>

                    <div class="relative pl-16">
                        <dt class="text-base font-semibold leading-7 text-gray-900">
                            <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-yellow-500">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                </svg>
                            </div>
                            Reportes en Tiempo Real
                        </dt>
                        <dd class="mt-2 text-base leading-7 text-gray-600">Acceso instantáneo a estadísticas y métricas de ocupación de cupos.</dd>
                    </div>

                    <div class="relative pl-16">
                        <dt class="text-base font-semibold leading-7 text-gray-900">
                            <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-dcs-blue-600">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                            </div>
                            Colaboración Eficiente
                        </dt>
                        <dd class="mt-2 text-base leading-7 text-gray-600">Mejora la comunicación entre todas las partes involucradas en el proceso.</dd>
                    </div>

                </dl>
            </div>
        </div>
    </div>

    <!-- ===== FOOTER PROFESIONAL (AZUL OSCURO) ===== -->
    <footer class="bg-dcs-blue-900 border-t border-dcs-blue-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Columna 1: Acerca de -->
                <div>
                    <h3 class="text-white font-semibold text-lg mb-4">Acerca de SIRAS</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">
                        Sistema Informático de Rotaciones Académicas de Salud desarrollado para optimizar la gestión de prácticas profesionales en la comuna de Los Ángeles.
                    </p>
                </div>

                <!-- Columna 2: Enlaces Institucionales -->
                <div>
                    <h3 class="text-white font-semibold text-lg mb-4">Instituciones Participantes</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="https://dcslosangeles.cl/" target="_blank" class="text-gray-300 hover:text-yellow-400 text-sm transition-colors duration-150">
                                Dirección Comunal de Salud Los Ángeles
                            </a>
                        </li>
                        <li>
                            <a href="https://admision.virginiogomez.cl/" target="_blank" class="text-gray-300 hover:text-yellow-400 text-sm transition-colors duration-150">
                                Instituto Profesional Virginio Gómez
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Columna 3: Contacto -->
                <div>
                    <h3 class="text-white font-semibold text-lg mb-4">Soporte</h3>
                    <p class="text-gray-300 text-sm mb-2">
                        Para consultas o asistencia técnica, contacte al área de TI de la DCS.
                    </p>
                </div>
            </div>

            <!-- Separador -->
            <div class="border-t border-dcs-blue-800 mt-8 pt-8">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <!-- Copyright -->
                    <p class="text-gray-400 text-sm text-center sm:text-left">
                        © {{ date('Y') }} SIRAS. Todos los derechos reservados.
                    </p>
                    
                    <!-- Créditos de desarrollo -->
                    <p class="text-gray-400 text-sm text-center sm:text-right">
                        Desarrollado por estudiantes de 
                        <a href="https://admision.virginiogomez.cl/" target="_blank" class="text-yellow-400 hover:text-yellow-300 font-medium transition-colors duration-150">
                            Ingeniería en Informática IPVG
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>