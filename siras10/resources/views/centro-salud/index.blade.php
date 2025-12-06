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

                    {{-- BUSCADOR --}}
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6">
                        <form id="search-form" action="{{ route('centro-salud.index') }}" method="GET">
                            <div class="relative w-full lg:w-1/3">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                    </svg>
                                </div>
                                <input type="text" 
                                    id="search-input" 
                                    name="search" 
                                    value="{{ request('search') }}" 
                                    class="block w-full p-2.5 pl-10 pr-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" 
                                    placeholder="Buscar por Nombre, Ciudad o Tipo..." 
                                    autocomplete="off">
                                
                                <button type="button" id="btn-clear-search" class="hidden absolute inset-y-0 right-0 items-center pr-3 text-gray-400 hover:text-gray-600 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>

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

    {{-- Modal Ver Personal --}}
    <div id="personalModal" class="relative z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div id="personalModalBackdrop" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity z-40 cursor-pointer backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-50 w-screen overflow-y-auto pointer-events-none">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 pointer-events-auto">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-200">
                    <div class="bg-gradient-to-r from-sky-700 to-blue-800 px-8 py-5 border-b border-blue-900 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            Personal Asignado
                        </h3>
                        <button id="closePersonalModalX" class="text-white hover:text-gray-200 focus:outline-none transition-transform hover:scale-110">
                            <svg class="w-6 h-6 drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <div class="p-8 bg-gray-50 max-h-[70vh] overflow-y-auto" id="personalListContainer">
                        {{-- Content populated by JS --}}
                    </div>

                    <div class="bg-white px-8 py-4 sm:px-8 sm:flex sm:flex-row-reverse border-t border-gray-200">
                        <button type="button" id="closePersonalModalBtn" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/js/app.js'])
</x-app-layout>