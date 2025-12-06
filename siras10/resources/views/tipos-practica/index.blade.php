<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Tipos de Práctica') }}
            </h2>
            @can('tipos-practica.create')
                <button data-modal-target="tipoPracticaModal" data-modal-toggle="tipoPracticaModal" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                    Nuevo Tipo de Práctica
                </button>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Buscador --}}
                    <div class="mb-4">
                        <form id="search-form" class="flex gap-2" onsubmit="return false;">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                    </svg>
                                </div>
                                <input type="text" 
                                       id="search-input" 
                                       name="search"
                                       class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" 
                                       placeholder="Buscar por nombre de práctica..."
                                       autocomplete="off">
                                <button type="button" id="btn-clear-search" class="absolute inset-y-0 right-0 items-center pr-3 hidden text-gray-500 hover:text-gray-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div id="tabla-container">
                        @include('tipos-practica._tabla', [
                            'tiposPractica' => $tiposPractica,
                            'sortBy' => $sortBy,
                            'sortDirection' => $sortDirection
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para Crear/Editar --}}
    <x-crud-modal 
        modalId="tipoPracticaModal" 
        formId="tipoPracticaForm" 
        title="Gestión de Tipo de Práctica"
        primaryKey="idTipoPractica"
        closeFunction="cerrarModalTipoPractica()">
        
        <div class="mb-4">
            <label for="nombrePractica" class="block text-sm font-medium text-gray-700">Nombre de la Práctica *</label>
            <input type="text" id="nombrePractica" name="nombrePractica" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-nombrePractica"></div>
        </div>

        <x-slot name="footer">
            <button type="button" onclick="cerrarModalTipoPractica()" class="bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded">
                Cancelar
            </button>
            <button type="submit" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                Guardar
            </button>
        </x-slot>
    </x-crud-modal>

    @vite(['resources/js/app.js'])
</x-app-layout>