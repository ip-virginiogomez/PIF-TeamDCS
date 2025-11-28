<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-black leading-tight">
                {{ __('Gestión de Sedes') }}
            </h2>
            <button data-modal-target="sedeModal" data-modal-toggle="sedeModal" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                Nueva Sede
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
                        @include('sede._tabla',[
                            'sedes' => $sedes,
                            'sortBy' => $sortBy,
                            'sortDirection' => $sortDirection
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-crud-modal 
        modalId="sedeModal" 
        formId="sedeForm" 
        title="Nueva Sede"
        primaryKey="sedeId">
        
        <div class="mb-4">
            <label for="idCentroFormador" class="block text-sm font-medium text-gray-700">Centro Formador *</label>
            <select class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="idCentroFormador" name="idCentroFormador" required>
                <option value="">Seleccione el centro formador</option>
                @foreach($centrosFormadores as $centroFormador)
                <option value="{{ $centroFormador->idCentroFormador }}">{{ $centroFormador->nombreCentroFormador }}</option>
                @endforeach
            </select>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-idCentroFormador"></div>
        </div>
        
        <div class="mb-4">
            <label for="nombreSede" class="block text-sm font-medium text-gray-700">Nombre de la Sede *</label>
            <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" id="nombreSede" name="nombreSede" required>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-nombreSede"></div>
        </div>

        <div class="mb-4">
            <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección *</label>
            <textarea class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" id="direccion" name="direccion" rows="3" required></textarea>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-direccion"></div>
        </div>

        <div class="mb-4">
            <label for="numContacto" class="block text-sm font-medium text-gray-700">Número de Contacto *</label>
            <input type="text" placeholder="+12345678901" maxlength="12" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" id="numContacto" name="numContacto">
            <div class="text-red-500 text-sm mt-1 hidden" id="error-numContacto"></div>
        </div>
        <div class="mb-4" id="fechaCreacion-container"></div>
    </x-crud-modal>

    @vite(['resources/js/app.js'])
</x-app-layout>