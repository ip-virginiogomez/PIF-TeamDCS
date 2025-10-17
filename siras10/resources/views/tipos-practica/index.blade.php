<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Tipos de Práctica') }}
            </h2>
            @can('tipos-practica.create')
                <button onclick="limpiarFormularioTipoPractica()" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                    Nuevo Tipo de Práctica
                </button>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="tabla-container">
                        @include('tipos-practica._tabla', ['tiposPractica' => $tiposPractica])
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

    @vite(['resources/js/tipo-practica.js'])
</x-app-layout>