<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Carreras por Sede') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- SELECCIÓN DE CENTRO Y SEDE --}}
            <div id="selection-container"
                data-centros="{{ json_encode($centrosFormadores) }}"
                data-carreras="{{ json_encode($carrerasBase) }}"
                class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Seleccionar Sede</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div></div>
                        <div></div>
                    </div>
                </div>
            </div>

            {{-- GESTIÓN DE CARRERAS --}}
            <div id="gestion-container" class="hidden">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">
                                Carreras en: <span id="sede-name-placeholder" class="text-blue-600"></span>
                            </h3>
                            @can('sede-carrera.create')
                            <button type="button"
                                    data-modal-target="crudModal"
                                    class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                                Registrar Carrera
                            </button>
                            @endcan
                        </div>

                        <div id="tabla-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    {{-- MODAL CRUD ESTANDARIZADO --}}
    <x-crud-modal 
        modalId="crudModal" 
        formId="crudForm" 
        title="Añadir Carrera"
    >
        <input type="hidden" name="idSede">

        <div class="mb-4">
            <label for="idCarrera" class="block text-sm font-medium text-gray-700">Perfil *</label>
            <select name="idCarrera" id="idCarrera" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">-- Seleccione un perfil --</option>
            </select>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-idCarrera"></div>
        </div>

        <div class="mb-4">
            <label for="nombreSedeCarrera" class="block text-sm font-medium text-gray-700">Nombre en Sede</label>
            <input type="text" name="nombreSedeCarrera" id="nombreSedeCarrera"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <p class="mt-1 text-xs text-gray-500">Si se deja vacío, se usará el nombre base de la carrera</p>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-nombreSedeCarrera"></div>
        </div>

        <div class="mb-4">
            <label for="codigoCarrera" class="block text-sm font-medium text-gray-700">Código *</label>
            <input type="text" name="codigoCarrera" id="codigoCarrera" required
                placeholder="Ej: INF-2025"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <div class="text-red-500 text-sm mt-1 hidden" id="error-codigoCarrera"></div>
        </div>
    </x-crud-modal>

    @vite(['resources/js/app.js'])
</x-app-layout>