<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-black leading-tight">
                {{ __('Gestión de Alumnos') }}
            </h2>
            @can('alumnos.create')
            <button 
                data-modal-target="alumnoModal" 
                data-modal-toggle="alumnoModal" 
                class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                Nuevo Alumno
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
                        @include('alumnos._tabla', [
                            'alumnos' => $alumnos,
                            'sortBy' => $sortBy,
                            'sortDirection' => $sortDirection
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-crud-modal 
        modalId="alumnoModal" 
        formId="alumnoForm" 
        primaryKey="runAlumno"
        title="Gestión de Alumno">

        {{-- Asignar a Sede/Carrera --}}
        <div class="mb-4">
            <label for="idSedeCarrera" class="block text-sm font-medium text-gray-700">Asignar a Sede/Carrera *</label>
            <select name="idSedeCarrera" id="idSedeCarrera" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="">Seleccione una opción...</option>
                @foreach($sedesCarreras as $sedeCarrera)
                    <option value="{{ $sedeCarrera->idSedeCarrera }}">
                        {{ $sedeCarrera->sede->centroFormador->nombreCentroFormador ?? 'CF Desc.' }} 
                        ({{ $sedeCarrera->sede->nombreSede ?? 'Sede Desc.' }}) 
                        - {{ $sedeCarrera->nombreSedeCarrera ?: ($sedeCarrera->carrera->nombreCarrera ?? 'Carrera Desc.') }}
                    </option>
                @endforeach
            </select>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-idSedeCarrera"></div>
        </div>
        
        {{-- RUN --}}
        <div class="mb-4">
            <label for="runAlumno" class="block text-sm font-medium text-gray-700">RUN *</label>
            <input type="text" id="runAlumno" name="runAlumno" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Ej: 12345678-9" required>
            <div id="run-help-text" class="text-xs text-amber-600 mt-1 hidden">
                El RUN no puede modificarse al editar un alumno existente
            </div>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-runAlumno"></div>
        </div>
        
        {{-- Nombres --}}
        <div class="mb-4">
            <label for="nombres" class="block text-sm font-medium text-gray-700">Nombres *</label>
            <input type="text" id="nombres" name="nombres" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-nombres"></div>
        </div>

        {{-- Apellido Paterno --}}
        <div class="mb-4">
            <label for="apellidoPaterno" class="block text-sm font-medium text-gray-700">Apellido Paterno *</label>
            <input type="text" id="apellidoPaterno" name="apellidoPaterno" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-apellidoPaterno"></div>
        </div>

        {{-- Apellido Materno --}}
        <div class="mb-4">
            <label for="apellidoMaterno" class="block text-sm font-medium text-gray-700">Apellido Materno</label>
            <input type="text" id="apellidoMaterno" name="apellidoMaterno" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <div class="text-red-500 text-sm mt-1 hidden" id="error-apellidoMaterno"></div>
        </div>

        {{-- Fecha de Nacimiento --}}
        <div class="mb-4">
            <label for="fechaNacto" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento *</label>
            <input type="date" id="fechaNacto" name="fechaNacto" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-fechaNacto"></div>
        </div>

        {{-- Correo --}}
        <div class="mb-4">
            <label for="correo" class="block text-sm font-medium text-gray-700">Correo Electrónico *</label>
            <input type="email" id="correo" name="correo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-correo"></div>
        </div>

        {{-- Foto --}}
        <div class="mb-4">
            <label for="foto" class="block text-sm font-medium text-gray-700">Foto</label>
            <div class="mt-2 mb-2">
                <img id="foto-preview" src="{{ asset('storage/placeholder.png') }}" alt="Vista previa" class="w-24 h-24 rounded-md object-cover border border-gray-300">    
            </div>
            <input type="file" id="foto" name="foto" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <div class="text-red-500 text-sm mt-1 hidden" id="error-foto"></div>
        </div>

        {{-- Acuerdo --}}
        <div class="mb-4">
            <label for="acuerdo" class="block text-sm font-medium text-gray-700">Documento de Acuerdo</label>
            <div id="acuerdo-actual" class="mb-2 text-sm text-blue-600 hidden">
                <span>📄 Archivo actual: </span>
                <a id="acuerdo-link" href="#" target="_blank" class="underline">Ver documento</a>
            </div>
            <input type="file" id="acuerdo" name="acuerdo" accept=".pdf,.doc,.docx" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <div class="text-xs text-gray-500 mt-1">Formatos permitidos: PDF, DOC, DOCX (máx. 5MB)</div>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-acuerdo"></div>
        </div>

    </x-crud-modal>
    
    @vite(['resources/js/app.js'])
</x-app-layout>