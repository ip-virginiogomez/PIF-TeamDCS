<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Carreras') }}
            </h2>
            @can('carreras.create')
            <button data-modal-target="carreraModal" data-modal-toggle="carreraModal" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                Nueva Carrera
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
                        @include('carreras._tabla',[
                            'carreras' => $carreras,
                            'sortBy' => $sortBy,
                            'sortDirection' => $sortDirection
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-crud-modal 
        modalId="carreraModal" 
        formId="carreraForm" 
        primaryKey="idCarrera"
        title="Nueva Carrera">
        
        <div class="mb-4">
            <label for="nombreCarrera" class="block text-sm font-medium text-gray-700">Nombre de la Carrera *</label>
            <input type="text" 
                id="nombreCarrera" 
                name="nombreCarrera" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                required>
            <div id="error-nombreCarrera" class="text-red-500 text-sm mt-1 hidden"></div>
        </div>

    </x-crud-modal>

    @vite(['resources/js/app.js'])
</x-app-layout>