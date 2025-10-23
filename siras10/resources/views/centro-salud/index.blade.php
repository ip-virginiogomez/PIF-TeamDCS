<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Centros de Salud') }}
            </h2>
            <button data-modal-target="centroSaludModal" data-modal-toggle="centroSaludModal" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                Nuevo Centro de Salud
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
                        @include('centro-salud._tabla',[
                            'centrosSalud' => $centrosSalud,
                            'sortBy' => $sortBy,
                            'sortDirection' => $sortDirection
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Función simple en closeFunction -->
    <x-crud-modal 
        modalId="centroSaludModal" 
        formId="centroSaludForm" 
        title="Nuevo Centro de Salud"
        primaryKey="centroId"
        closeFunction="cerrarModal()">
        
        <div class="mb-4">
            <label for="idTipoCentroSalud" class="block text-sm font-medium text-gray-700">Tipo de Centro *</label>
            <select class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="idTipoCentroSalud" name="idTipoCentroSalud" required>
                <option value="">Seleccione un tipo</option>
                @foreach($tiposCentro as $tipo)
                <option value="{{ $tipo->idTipoCentroSalud }}">{{ $tipo->acronimo }} - {{ $tipo->nombreTipo }}</option>
                @endforeach
            </select>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-idTipoCentroSalud"></div>
        </div>
        
        <div class="mb-4">
            <label for="nombreCentro" class="block text-sm font-medium text-gray-700">Nombre del Centro *</label>
            <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" id="nombreCentro" name="nombreCentro" required>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-nombreCentro"></div>
        </div>

        <div class="mb-4">
            <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección *</label>
            <textarea class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" id="direccion" name="direccion" rows="3" required></textarea>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-direccion"></div>
        </div>

        <div class="mb-4">
            <label for="director" class="block text-sm font-medium text-gray-700">Director del Centro *</label>
            <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" id="director" name="director" placeholder="Ej: Dr. Juan Pérez" required>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-director"></div>
        </div>

        <div class="mb-4">
            <label for="correoDirector" class="block text-sm font-medium text-gray-700">Correo del Director *</label>
            <input type="email" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" id="correoDirector" name="correoDirector" placeholder="director@centro.cl" required>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-correoDirector"></div>
        </div>

        <div class="mb-4">
            <label for="idCiudad" class="block text-sm font-medium text-gray-700">Ciudad *</label>
            <select class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="idCiudad" name="idCiudad" required>
                <option value="">Seleccione una ciudad</option>
                @foreach($ciudades as $ciudad)
                <option value="{{ $ciudad->idCiudad }}">{{ $ciudad->nombreCiudad }}</option>
                @endforeach
            </select>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-idCiudad"></div>
        </div>
    </x-crud-modal>

    @vite(['resources/js/app.js'])
</x-app-layout>