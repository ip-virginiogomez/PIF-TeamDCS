<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Unidades Clínicas') }}
            </h2>
            @can('unidad-clinicas.create')
                <button data-modal-target="unidadClinicaModal" data-modal-toggle="unidadClinicaModal" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                    Nueva Unidad Clínica
                </button>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="tabla-container">
                        @include('unidad-clinicas._tabla', ['unidadesClinicas' => $unidadesClinicas])
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para Crear/Editar Unidades Clínicas --}}
    <x-crud-modal 
        modalId="unidadClinicaModal" 
        formId="unidadClinicaForm" 
        title="Gestión de Unidad Clínica"
        primaryKey="idUnidadClinica"
        closeFunction="cerrarModalUnidadClinica()">
        
        <div class="mb-4">
            <label for="nombreUnidad" class="block text-sm font-medium text-gray-700">Nombre de la Unidad *</label>
            <input type="text" id="nombreUnidad" name="nombreUnidad" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-nombreUnidad"></div>
        </div>
        
        <div class="mb-4">
            <label for="idCentroSalud" class="block text-sm font-medium text-gray-700">Centro de Salud *</label>
            <select id="idCentroSalud" name="idCentroSalud" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                <option value="">Seleccione un centro</option>
                @foreach($centrosSalud as $centro)
                    <option value="{{ $centro->idCentroSalud }}">{{ $centro->nombreCentro }}</option>
                @endforeach
            </select>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-idCentroSalud"></div>
        </div>

        <x-slot name="footer">
            <button type="button" onclick="cerrarModalUnidadClinica()" class="bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded">
                Cancelar
            </button>
            <button type="submit" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                Guardar
            </button>
        </x-slot>
    </x-crud-modal>

    @vite(['resources/js/app.js'])
</x-app-layout>
