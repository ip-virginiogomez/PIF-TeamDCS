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

                    {{-- BUSCADOR --}}
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6">
                        <form id="search-form" action="{{ route('tipos-centro-formador.index') }}" method="GET">
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
                                    placeholder="Buscar por Nombre de Tipo..." 
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
