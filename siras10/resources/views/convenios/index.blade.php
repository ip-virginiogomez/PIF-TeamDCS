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
        
        <!-- Año de Validez -->
        <div class="mb-4">
            <label for="anioValidez" class="block text-sm font-medium text-gray-700">Año de Validez *</label>
            <input type="number" 
                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" 
                   id="anioValidez" 
                   name="anioValidez" 
                   min="{{ date('Y') }}" 
                   max="{{ date('Y') + 10 }}" 
                   value="{{ date('Y') + 1 }}"
                   required>
            <div class="text-xs text-gray-500 mt-1">El convenio será válido hasta el 31 de diciembre del año especificado</div>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-anioValidez"></div>
        </div>

        <!-- Documento -->
        <div class="mb-4">
            <label for="documento" class="block text-sm font-medium text-gray-700">Documento del Convenio *</label>
            <input type="file" 
                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" 
                   id="documento" 
                   name="documento" 
                   accept=".pdf,.doc,.docx">
            <div class="text-xs text-gray-500 mt-1">Formatos permitidos: PDF, DOC, DOCX (máx. 10MB)</div>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-documento"></div>
            <div id="archivo-preview"></div>
        </div>

        <div class="mb-4" id="fechaSubida-container"></div>
    </x-crud-modal>

    @vite(['resources/js/app.js'])
</x-app-layout>