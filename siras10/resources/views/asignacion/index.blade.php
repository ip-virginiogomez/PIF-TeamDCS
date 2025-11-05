<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black leading-tight">
            {{ __('Asignar Usuarios a Centros Salud/Formador') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex" style="min-height: 70vh;">

                    <div class="w-1/3 border-r border-gray-200 bg-gray-50">
                        <div class="p-4 border-b">
                            <h3 class="font-semibold text-lg">Coordinadores</h3>
                            </div>
                        <ul id="lista-coordinadores" class="overflow-y-auto">
                            @forelse ($coordinadores as $coordinador)
                                <li class="p-4 border-b cursor-pointer hover:bg-indigo-50" 
                                    data-id="{{ $coordinador->runUsuario }}"
                                    data-nombre="{{ $coordinador->nombreUsuario }}">
                                    <div class="font-medium">{{ $coordinador->nombreUsuario }} {{ $coordinador->apellidoPaterno }}</div>
                                    <div class="text-sm text-gray-500">{{ $coordinador->correo }}</div>
                                </li>
                            @empty
                                <li class="p-4 text-gray-500">No se encontraron coordinadores.</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="w-2/3 p-6">
                        <div id="panel-inicial" class="text-center text-gray-500 pt-20">
                            <i class="fas fa-hand-point-left fa-3x mb-4 text-gray-400"></i>
                            <h2 class="text-lg font-medium">Seleccione un coordinador</h2>
                            <p>Seleccione un coordinador de la lista para ver y gestionar sus centros asignados.</p>
                        </div>

                        <div id="panel-carga" class="text-center text-gray-500 pt-20 hidden">
                            <i class="fas fa-spinner fa-spin fa-3x text-blue-500"></i>
                            <p class="mt-2">Cargando...</p>
                        </div>

                        <div id="panel-contenido" class="hidden">
                            <h2 class="text-xl font-semibold mb-4">
                                Asignaciones de: <span id="nombre-coordinador" class="text-blue-600"></span>
                            </h2>

                            <div class="bg-gray-50 p-4 rounded-lg border">
                                <h3 class="font-semibold mb-2">Asignar Nuevo Centro</h3>
                                <div class="flex space-x-2">
                                    <select id="select-centros-disponibles" class="block w-full rounded-md border-gray-300 shadow-sm">
                                        </select>
                                    <button id="btn-asignar" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        <i class="fas fa-plus"></i> Asignar
                                    </button>
                                </div>
                                <div id="error-asignacion" class="text-red-500 text-sm mt-2 hidden"></div>
                            </div>

                            <hr class="my-6">

                            <div>
                                <h3 class="text-lg font-semibold mb-3">Asignaciones Actuales</h3>
                                <ul id="lista-asignaciones-actuales" class="space-y-2">
                                    </ul>
                                <div id="sin-asignaciones" class="text-gray-500 hidden">
                                    Este coordinador aún no tiene centros asignados.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/js/app.js'])
</x-app-layout>