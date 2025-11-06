<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Gestión de Carreras por Sede
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Sección de Selección --}}
            <div id="selection-container" data-centros="{{ json_encode($centrosFormadores) }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                    <div>
                        <label for="centro-formador-selector" class="block text-sm font-medium text-gray-700">1. Centro Formador</label>
                        <select id="centro-formador-selector" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Seleccionar --</option>
                            @foreach ($centrosFormadores as $centro)
                                <option value="{{ $centro->idCentroFormador }}">{{ $centro->nombreCentroFormador }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="sede-selector" class="block text-sm font-medium text-gray-700">2. Sede</label>
                        <select id="sede-selector" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" disabled>
                            <option value="">-- Primero seleccione un centro --</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Contenedor de Gestión (inicialmente oculto) --}}
            <div id="gestion-container" class="hidden">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    {{-- Encabezado dinámico y botón --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Listado de Carreras para: <span id="sede-name-placeholder" class="font-bold"></span>
                        </h3>
                        <button type="button" data-modal-target="crudModal" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white uppercase text-xs font-semibold rounded-md">
                            Añadir Carrera
                        </button>
                    </div>
                    
                    {{-- Contenedor para la tabla (se llenará con AJAX) --}}
                    <div id="tabla-container">
                        {{-- El contenido se cargará aquí --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal (siempre presente en el DOM, pero oculto) --}}
    <div id="crudModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 id="modalTitle" class="text-2xl font-bold"></h3>
                <button data-action="close-modal" class="cursor-pointer z-50"><svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"><path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path></svg></button>
            </div>
            <form id="crudForm" class="mt-4 space-y-4">
                @csrf
                <input type="hidden" name="idSede">

                {{-- NUEVO CAMPO: Selector de Carrera Base --}}
                <div>
                    <label for="idCarrera" class="block text-sm font-medium text-gray-700">Carrera Base</label>
                    <select name="idCarrera" id="idCarrera" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Seleccione una carrera --</option>
                        @foreach($carrerasBase as $carrera)
                            <option value="{{ $carrera->idCarrera }}">{{ $carrera->nombreCarrera }}</option>
                        @endforeach
                    </select>
                    <span id="error-idCarrera" class="text-red-500 text-sm"></span>
                </div>

                <div>
                    <label for="nombreSedeCarrera" class="block text-sm font-medium text-gray-700">Nombre Específico (Opcional, si es diferente)</label>
                    <input type="text" name="nombreSedeCarrera" id="nombreSedeCarrera" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <span id="error-nombreSedeCarrera" class="text-red-500 text-sm"></span>
                </div>
                <div>
                    <label for="codigoCarrera" class="block text-sm font-medium text-gray-700">Código</label>
                    <input type="text" name="codigoCarrera" id="codigoCarrera" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <span id="error-codigoCarrera" class="text-red-500 text-sm"></span>
                </div>
                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700"><span id="btnTexto"></span></button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/sede-carrera.js'])
    @endpush
</x-app-layout>