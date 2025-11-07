<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Gestión de Carreras por Sede
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Sección de Selección --}}
            <div id="selection-container" 
                 data-centros="{{ json_encode($centrosFormadores) }}" 
                 data-carreras="{{ json_encode($carrerasBase) }}"
                 class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                    {{-- Los selectores se crean dinámicamente por JavaScript --}}
                    <div>
                        {{-- Centro Formador selector se creará aquí --}}
                    </div>
                    <div>
                        {{-- Sede selector se creará aquí --}}
                    </div>
                </div>
            </div>

            {{-- Contenedor de Gestión (inicialmente oculto) --}}
            <div id="gestion-container" class="hidden">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    {{-- Encabezado dinámico y botón --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Carreras en: <span id="sede-name-placeholder" class="font-bold text-blue-600"></span>
                        </h3>
                        <button type="button" data-modal-target="crudModal" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Añadir Carrera
                        </button>
                    </div>
                    
                    {{-- ESTE ES EL CONTENEDOR QUE NECESITAS --}}
                    <div id="tabla-container">
                        {{-- El contenido se cargará aquí dinámicamente --}}
                        <div class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Seleccione una sede</h3>
                            <p class="mt-1 text-sm text-gray-500">Para ver las carreras específicas disponibles</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal (siempre presente en el DOM, pero oculto) --}}
    <div id="crudModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 id="modalTitle" class="text-2xl font-bold text-gray-900"></h3>
                <button data-action="close-modal" class="cursor-pointer z-50 text-gray-400 hover:text-gray-600 transition ease-in-out duration-150">
                    <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                        <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                    </svg>
                </button>
            </div>
            <form id="crudForm" class="mt-4 space-y-4">
                @csrf
                <input type="hidden" name="idSede">

                {{-- Selector de Carrera Base --}}
                <div>
                    <label for="idCarrera" class="block text-sm font-medium text-gray-700">Carrera Base</label>
                    <select name="idCarrera" id="idCarrera" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Seleccione una carrera --</option>
                        {{-- Las opciones se llenarán dinámicamente por JavaScript --}}
                    </select>
                    <span id="error-idCarrera" class="text-red-500 text-sm hidden"></span>
                </div>

                {{-- Nombre Específico --}}
                <div>
                    <label for="nombreSedeCarrera" class="block text-sm font-medium text-gray-700">
                        Nombre Específico 
                        <span class="text-gray-500 text-xs">(opcional, si es diferente al nombre base)</span>
                    </label>
                    <input type="text" name="nombreSedeCarrera" id="nombreSedeCarrera" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: Enfermería - Modalidad Virtual">
                    <span id="error-nombreSedeCarrera" class="text-red-500 text-sm hidden"></span>
                </div>

                {{-- Código de Carrera --}}
                <div>
                    <label for="codigoCarrera" class="block text-sm font-medium text-gray-700">Código</label>
                    <input type="text" name="codigoCarrera" id="codigoCarrera" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: ENF-001">
                    <span id="error-codigoCarrera" class="text-red-500 text-sm hidden"></span>
                </div>

                {{-- Botones --}}
                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <button type="button" data-action="close-modal" class="px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <span id="btnTexto">Guardar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/sede-carrera.js'])
    @endpush
</x-app-layout>