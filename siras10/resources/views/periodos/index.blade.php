<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Períodos') }}
            </h2>
            @can('periodos.create')
                {{-- CAMBIO: Llamar a la función global específica --}}
                <button onclick="limpiarFormularioPeriodo()" data-modal-target="periodoModal" data-modal-toggle="periodoModal" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                    Nuevo Período
                </button>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="tabla-container">
                        @include('periodos._tabla', ['periodos' => $periodos])
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para Crear/Editar Períodos --}}
    <x-crud-modal 
        modalId="periodoModal" 
        formId="periodoForm" 
        title="Gestión de Período"
        primaryKey="idPeriodo"
        closeFunction="cerrarModalPeriodo()"> {{-- 1. Le decimos al modal cómo cerrarse --}}
        
        <div class="mb-4">
            <label for="Año" class="block text-sm font-medium text-gray-700">Año *</label>
            <input type="number" id="Año" name="Año" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-Año"></div>
        </div>
        
        <div class="mb-4">
            <label for="fechaInicio" class="block text-sm font-medium text-gray-700">Fecha de Inicio *</label>
            <input type="date" id="fechaInicio" name="fechaInicio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-fechaInicio"></div>
        </div>

        <div class="mb-4">
            <label for="fechaFin" class="block text-sm font-medium text-gray-700">Fecha de Fin *</label>
            <input type="date" id="fechaFin" name="fechaFin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-fechaFin"></div>
        </div>

        {{-- 2. AÑADIMOS EL FOOTER CON LOS BOTONES --}}
        <x-slot name="footer">
            <button type="button" onclick="cerrarModalPeriodo()" class="bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded">
                Cancelar
            </button>
            <button type="submit" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                Guardar
            </button>
        </x-slot>
    </x-crud-modal>

    @vite(['resources/js/periodos.js'])
</x-app-layout>
