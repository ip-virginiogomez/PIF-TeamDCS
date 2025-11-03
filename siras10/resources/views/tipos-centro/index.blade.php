<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tipos de Centro Formador') }}
            </h2>
            @can('tipos-centro-formador.create')
                <button data-modal-target="tipoCentroModal" data-modal-toggle="tipoCentroModal" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                    Nuevo Tipo de Centro Formador
                </button>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    <div id="tabla-container">
                        @include('tipos-centro._tabla',[
                            'tiposCentro' => $tiposCentro,
                            'sortBy' => $sortBy,
                            'sortDirection' => $sortDirection
                        ])
                    </div>
                </div>
            </div>              
        </div>
    </div>

    <x-crud-modal
        modalId="tipoCentroModal"
        formId="tipoCentroForm"
        title="Nuevo Tipo de Centro Formador"
        primaryKey="idTipoCentroFormador">

        <div class="mb-4">
            <label for="nombreTipo" class="block text-sm font-medium text-gray-700">Nombre del Tipo de Centro *</label>
            <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" id="nombreTipo" name="nombreTipo" required>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-nombreTipoCentro"></div>
        </div>
        <div class="mb-4" id="fechaCreacion-container"></div>
    </x-crud-modal>

    @vite(['resources/js/app.js'])
</x-app-layout>
