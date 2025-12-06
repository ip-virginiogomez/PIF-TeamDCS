<?php
// filepath: resources/views/convenios/index.blade.php
?>
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-black leading-tight">
                {{ __('Gestión de Convenios') }}
            </h2>
            <button onclick="abrirModal()" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                Nuevo Convenio
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
                        @include('convenios._tabla', [
                            'convenios' => $convenios ?? collect(),
                            'sortBy' => $sortBy ?? 'idConvenio',
                            'sortDirection' => $sortDirection ?? 'desc'
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-crud-modal 
        modalId="convenioModal" 
        formId="convenioForm" 
        title="Nuevo Convenio"
        primaryKey="convenioId">
        
        <!-- Centro Formador -->
        <div class="mb-4">
            <label for="idCentroFormador" class="block text-sm font-medium text-gray-700">Centro Formador *</label>
            <select class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                    id="idCentroFormador" name="idCentroFormador" required>
                <option value="">Seleccione un centro formador</option>
                @foreach($centrosFormadores as $centro)
                    <option value="{{ $centro->idCentroFormador }}">{{ $centro->nombreCentroFormador }}</option>
                @endforeach
            </select>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-idCentroFormador"></div>
        </div>
        
        <!-- Fecha de Inicio -->
        <div class="mb-4">
            <label for="fechaInicio" class="block text-sm font-medium text-gray-700">Fecha de Inicio *</label>
            <input type="date" 
                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" 
                   id="fechaInicio" 
                   name="fechaInicio" 
                   required>
            <div class="text-xs text-gray-500 mt-1">Debe corresponder al año indicado arriba</div>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-fechaInicio"></div>
        </div>

        <!-- Fecha de Fin -->
        <div class="mb-4">
            <label for="fechaFin" class="block text-sm font-medium text-gray-700">Fecha de Fin *</label>
            <input type="date" 
                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" 
                   id="fechaFin" 
                   name="fechaFin" 
                   required>
            <div class="text-xs text-gray-500 mt-1">Debe ser posterior a la fecha de inicio</div>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-fechaFin"></div>
        </div>

        <!-- Documento -->
        <div class="mb-4">
            <label for="documento" class="block text-sm font-medium text-gray-700">Documento del Convenio *</label>
            <input type="file" 
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" 
                    id="documento" 
                    name="documento" 
                    accept=".pdf,.doc,.docx">
            <div class="text-xs text-gray-500 mt-1">Formatos permitidos: PDF, DOC, DOCX (máx. 2MB)</div>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-documento"></div>
            <div id="archivo-preview"></div>
        </div>

        <div class="mb-4" id="fechaSubida-container"></div>
    </x-crud-modal>

    {{-- Modal Preview Documento --}}
    <div id="modalPreviewConvenio" class="fixed inset-0 z-[110] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" id="backdrop-preview-convenio"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto pointer-events-none">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 pointer-events-auto">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl h-[80vh] flex flex-col">
                    <div class="bg-gray-50 px-4 py-3 flex justify-between items-center border-b">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Vista Previa del Documento</h3>
                        <button type="button" data-action="close-preview-convenio" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">Cerrar</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex-1 bg-gray-100 p-4">
                        <iframe id="iframe-preview-convenio" src="" class="w-full h-full rounded border bg-white"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/js/app.js'])
</x-app-layout>