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
                class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                Nuevo Alumno
            </button>
            @endcan
        </div>  
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
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

    {{-- MODAL CRUD ALUMNO (Datos Personales) --}}
    <x-crud-modal 
        modalId="alumnoModal" 
        formId="alumnoForm" 
        primaryKey="runAlumno"
        title="Gestión de Alumno"
        enctype="multipart/form-data"> {{-- Importante: enctype --}}

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
            <label for="apellidoPaterno" class="block text-sm font-medium text-gray-700">Primer Apellido</label>
            <input type="text" id="apellidoPaterno" name="apellidoPaterno" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            <div class="text-red-500 text-sm mt-1 hidden" id="error-apellidoPaterno"></div>
        </div>

        {{-- Apellido Materno --}}
        <div class="mb-4">
            <label for="apellidoMaterno" class="block text-sm font-medium text-gray-700">Segundo Apellido</label>
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

    <div id="modalVacunas" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div id="modal-backdrop-vacunas" class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 backdrop-blur-sm" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-200">
                
                {{-- Header (Color Sky-600) --}}
                <div class="bg-sky-600 px-6 py-4 flex justify-between items-center border-b border-sky-700">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <span id="titulo-modal-vacunas">Gestión de Vacunas</span>
                    </h3>
                    <button type="button" data-action="close-modal-vacunas" class="text-sky-200 hover:text-white transition-colors bg-sky-700 hover:bg-sky-800 rounded-lg p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-6 bg-white">
                    
                    {{-- 1. Formulario para agregar nueva --}}
                    <div class="mb-6 bg-slate-50 p-5 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="text-sm font-bold text-slate-700 mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Agregar Nueva Vacuna
                        </h4>
                        
                        <form id="form-vacunas" enctype="multipart/form-data">
                            <input type="hidden" id="runAlumnoVacuna" name="runAlumno">
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                {{-- Tipo Vacuna --}}
                                <div>
                                    <label for="idTipoVacuna" class="block text-xs font-semibold text-gray-600 mb-1">Tipo de Vacuna</label>
                                    <select name="idTipoVacuna" id="idTipoVacuna" class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition-all" required>
                                        <option value="">Seleccione...</option>
                                        @if(isset($tiposVacuna))
                                            @foreach($tiposVacuna as $tipo)
                                                <option value="{{ $tipo->idTipoVacuna }}">{{ $tipo->nombreVacuna }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                {{-- Estado --}}
                                <div>
                                    <label for="idEstadoVacuna" class="block text-xs font-semibold text-gray-600 mb-1">Estado</label>
                                    <select name="idEstadoVacuna" id="idEstadoVacuna" class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition-all" required>
                                        <option value="">Seleccione...</option>
                                        @if(isset($estadosVacuna))
                                            @foreach($estadosVacuna as $estado)
                                                <option value="{{ $estado->idEstadoVacuna }}">{{ $estado->nombreEstado }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            
                            {{-- Input Archivo y Botón --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Documento de Respaldo</label>
                                <div class="flex items-center gap-2">
                                    <input type="file" name="archivo" id="archivo_vacuna" accept=".pdf,.jpg,.png" 
                                        class="block w-full text-xs text-slate-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-lg file:border-0
                                        file:text-xs file:font-semibold
                                        file:bg-sky-50 file:text-sky-700
                                        hover:file:bg-sky-100
                                        cursor-pointer focus:outline-none" required>
                                    
                                    <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold py-2 px-4 rounded-lg shadow transition-all duration-200 flex items-center shrink-0">
                                        <span id="btn-text-vacuna">Subir</span>
                                        <svg id="spinner-vacuna" class="hidden w-3 h-3 ml-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    </button>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1 pl-1">Formatos: PDF, JPG, PNG (Máx 2MB)</p>
                            </div>
                        </form>
                    </div>

                    {{-- 2. Lista de vacunas existentes --}}
                    <div class="border-t border-gray-100 pt-5">
                        <h4 class="text-sm font-bold text-gray-800 mb-4 flex items-center justify-between">
                            <span>Historial de Vacunas</span>
                            <span class="text-xs font-normal text-gray-400 bg-gray-50 px-2 py-1 rounded">Registro histórico</span>
                        </h4>
                        
                        {{-- Contenedor con scroll --}}
                        <div id="lista-vacunas-container" class="max-h-64 overflow-y-auto pr-1 scrollbar-thin scrollbar-thumb-gray-200 scrollbar-track-transparent">
                            {{-- Aquí se inyecta el partial _lista_vacunas.blade.php --}}
                            <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                                <svg class="w-8 h-8 mb-2 animate-spin text-sky-200" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span class="text-xs">Cargando datos...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @vite(['resources/js/app.js'])
</x-app-layout>