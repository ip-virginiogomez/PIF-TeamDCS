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

    {{-- Modal Ver Coordinador --}}
    <div id="coordinatorModal" class="relative z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div id="coordinatorModalBackdrop" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity z-40 cursor-pointer backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-50 w-screen overflow-y-auto pointer-events-none">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 pointer-events-auto">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-200">
                    <div class="bg-gradient-to-r from-sky-700 to-blue-800 px-8 py-5 border-b border-blue-900 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            Coordinadores Asignados
                        </h3>
                        <button id="closeCoordinatorModalX" class="text-white hover:text-gray-200 focus:outline-none transition-transform hover:scale-110">
                            <svg class="w-6 h-6 drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <div class="p-8 bg-gray-50 max-h-[70vh] overflow-y-auto" id="coordinatorListContainer">
                        {{-- Content populated by JS --}}
                    </div>

                    <div class="bg-white px-8 py-4 sm:px-8 sm:flex sm:flex-row-reverse border-t border-gray-200">
                        <button type="button" id="closeCoordinatorModalBtn" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/js/app.js'])
</x-app-layout>