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

                    {{-- BUSCADOR Y FILTROS --}}
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6">
                        <form id="search-form" action="{{ route('alumnos.index') }}" method="GET">
                            
                            <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                                
                                {{-- 1. BUSCADOR --}}
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
                                        placeholder="Buscar por RUN, Nombre o Apellido..." 
                                        autocomplete="off">
                                    
                                    <button type="button" id="btn-clear-search" class="hidden absolute inset-y-0 right-0 items-center pr-3 text-gray-400 hover:text-gray-600 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                    
                                {{-- 2. FILTROS --}}
                                <div class="flex flex-col sm:flex-row gap-3 w-full lg:flex-1">
                                    @hasrole('Admin|Encargado Campo Clínico')
                                    {{-- Filtro Centro Formador --}}
                                    <div class="w-full sm:flex-1">
                                        <select name="centro_id" id="filter-centro" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 truncate">
                                            <option value="">Todos los Centros</option>
                                            @foreach($centrosFormadores as $centro)
                                                <option value="{{ $centro->idCentroFormador }}" {{ request('centro_id') == $centro->idCentroFormador ? 'selected' : '' }}>
                                                    {{ $centro->nombreCentroFormador }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endhasrole
                    
                                    {{-- Filtro Sede/Carrera --}}
                                    <div class="w-full flex-1">
                                        <select name="sede_carrera_id" id="filter-sede-carrera" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 truncate">
                                            <option value="">Todas las Carreras</option>
                                            @foreach($sedesCarreras as $sc)
                                                <option value="{{ $sc->idSedeCarrera }}" {{ request('sede_carrera_id') == $sc->idSedeCarrera ? 'selected' : '' }}>
                                                    {{ $sc->nombreSedeCarrera }} ({{ $sc->sede->nombreSede ?? '' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                    
                                </div>
                    
                            </div>
                        </form>
                    </div>

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
            <label for="acuerdo" class="block text-sm font-medium text-gray-700">Acuerdo de Confidencialidad</label>
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

    <div id="modalDocumentosAlumno" class="relative z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        {{-- Backdrop --}}
        <div id="backdrop-docs-alumno" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity z-40 cursor-pointer backdrop-blur-sm"></div>

        <div class="fixed inset-0 z-50 w-screen overflow-y-auto pointer-events-none">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 pointer-events-auto">
                
                {{-- Contenedor Principal (Mismo estilo rounded-xl, h-[85vh]) --}}
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl border border-gray-200 h-[85vh] flex flex-col">
                    
                    {{-- ENCABEZADO (Estilo Gris con Icono Sky) --}}
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 id="titulo-modal-docs" class="text-lg font-bold text-gray-800 flex items-center">
                            <svg class="w-6 h-6 text-sky-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Documentos del Alumno
                        </h3>
                        <button id="btn-close-docs" data-action="close-modal-docs" class="bg-gray-400 hover:bg-gray-600 text-white font-light py-2 px-4 rounded text-sm transition focus:outline-none">
                            <svg class="w-4 h-4 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Cerrar
                        </button>
                    </div>

                    {{-- CUERPO (Layout Dividido: Lista | Iframe) --}}
                    <div id="contenido-docs-alumno" class="flex-1 overflow-hidden flex">
                        
                        {{-- COLUMNA IZQUIERDA: Lista de Documentos --}}
                        <div class="w-full md:w-1/3 border-r border-gray-200 bg-white flex flex-col">
                            <div id="lista-docs-panel" class="flex-1 overflow-y-auto p-4 space-y-2">
                                @if(isset($alumno) && isset($documentosAlumno))
                                    @foreach($documentosAlumno as $doc)
                                        @if($doc['file'])
                                            <div class="flex items-center justify-between p-3 rounded-lg border border-gray-200 transition mb-2" data-preview-url="{{ asset('storage/'.$doc['file']) }}">
                                                <div class="flex items-center overflow-hidden">
                                                    <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                                    <span class="text-sm font-medium text-gray-700 truncate" title="{{ $doc['nombre'] }}">{{ $doc['nombre'] }}</span>
                                                </div>
                                                <button type="button" data-action="preview-doc" data-url="{{ asset('storage/'.$doc['file']) }}" class="hidden"></button>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            
                            {{-- Botón Volver (Footer de la lista) --}}
                            <div class="p-4 border-t border-gray-200 bg-gray-50 shrink-0">
                                <button id="btn-back-docs" class="w-full inline-flex justify-center items-center rounded-md bg-white border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-100 focus:outline-none transition-colors">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                    Volver a la Ficha
                                </button>
                            </div>
                        </div>

                        {{-- COLUMNA DERECHA: Visor (Iframe) --}}
                        <div class="hidden md:flex w-full md:w-2/3 bg-gray-100 flex-col items-center justify-center p-4">
                            {{-- Estado Vacío --}}
                            <div id="empty-state" class="text-center text-gray-400">
                                <svg class="w-16 h-16 mb-4 opacity-30 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <p>Selecciona un documento para visualizar</p>
                            </div>
                            
                            {{-- Iframe --}}
                            <iframe id="doc-iframe" src="" class="hidden w-full h-full rounded-lg border shadow-sm bg-white"></iframe>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL PREVIEW VACUNA (Nuevo) --}}
    <div id="modalPreviewVacuna" class="fixed inset-0 z-[110] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" id="backdrop-preview-vacuna"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto pointer-events-none">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 pointer-events-auto">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl h-[80vh] flex flex-col">
                    <div class="bg-gray-50 px-4 py-3 flex justify-between items-center border-b">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Vista Previa del Documento</h3>
                        <button type="button" data-action="close-preview-vacuna" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">Cerrar</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex-1 bg-gray-100 p-4">
                        <iframe id="iframe-preview-vacuna" src="" class="w-full h-full rounded border bg-white"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    @vite(['resources/js/app.js'])
    <script>
        window.estadosVacuna = @json($estadosVacuna ?? []);
    </script>
</x-app-layout>