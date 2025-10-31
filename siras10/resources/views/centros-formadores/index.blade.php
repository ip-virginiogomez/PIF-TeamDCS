<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Centros Formadores') }}
            </h2>
            <button data-modal-target="centroFormadorModal" data-modal-toggle="centroFormadorModal" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                Nuevo Centro Formador
            </button>
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
                        @include('centros-formadores._tabla', [
                            'centrosFormadores' => $centrosFormadores   ,
                            'sortBy' => $sortBy ?? 'idCentroFormador',
                            'sortDirection' => $sortDirection ?? 'asc'
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-crud-modal 
        modalId="centroFormadorModal" 
        formId="centroFormadorForm" 
        title="Nuevo Centro Formador"
        primaryKey="idCentroFormador">

        <div class="mb-4">
            <label for="idTipoCentroFormador" class="block text-sm font-medium text-gray-700">Tipo de Centro Formador *</label>
            <select class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="idTipoCentroFormador" name="idTipoCentroFormador" required>
                <option value="">Seleccione el tipo de centro formador</option>
                @foreach($tipoCentroFormador as $tipo)
                <option value="{{ $tipo->idTipoCentroFormador }}">{{ $tipo->nombreTipo }}</option>
                @endforeach
            </select>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-idTipoCentroFormador"></div>
        </div>
        <div class="mb-4">
            <label for="nombreCentroFormador" class="block text-sm font-medium text-gray-700">Nombre *</label>
            <input type="text" id="nombreCentroFormador" name="nombreCentroFormador" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            <div id="error-nombreCentroFormador" class="text-red-500 text-sm mt-1 hidden"></div>
        </div>
        <div class="mb-4" id="fechaCreacion-container"></div>
    </x-crud-modal>

    @vite(['resources/js/app.js'])
</x-app-layout>