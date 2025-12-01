<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asignación de Grupos a Campos Clínicos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- SECCIÓN 1: TABLA DE DISTRIBUCIONES (MAESTRO) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-700">1. Seleccione una Distribución (Cupos)</h3>
                </div>
                
                <div class="p-6">
                    {{-- 1. FORMULARIO DE FILTROS (FUERA DEL CONTENEDOR AJAX) --}}
                    {{-- Esto evita que se borre al escribir --}}
                    <div class="mb-6 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        <form id="form-filtros" method="GET" action="{{ route('grupos.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
                            
                            {{-- Barra de Búsqueda --}}
                            <div class="w-full md:flex-1">
                                <label for="input-search" class="block text-sm font-medium text-gray-700 mb-1">Buscar Distribución</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                    <input type="text" 
                                            name="search" 
                                            id="input-search" 
                                            value="{{ request('search') }}"
                                            class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                            placeholder="Centro, Sede, Unidad..."
                                            autocomplete="off">
                                </div>
                            </div>

                            {{-- Filtro por Periodo --}}
                            <div class="w-full md:w-48">
                                <label for="select-periodo" class="block text-sm font-medium text-gray-700 mb-1">Periodo (Año)</label>
                                <select name="periodo" id="select-periodo" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Todos los años</option>
                                    @if(isset($periodosDisponibles))
                                        @foreach($periodosDisponibles as $year)
                                            <option value="{{ $year }}" {{ request('periodo') == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            {{-- Botón Limpiar --}}
                            <div class="flex space-x-2" id="btn-container">
                                <button type="button" 
                                        id="btn-limpiar"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors" 
                                        title="Limpiar filtros">
                                    Limpiar
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- 2. CONTENEDOR DE LA TABLA (AJAX TARGET) --}}
                    {{-- Solo esto se refrescará --}}
                    <div id="tabla-distribuciones-container" class="relative min-h-[200px]">
                        @include('grupos._tabla_distribuciones')
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 2: TABLA DE GRUPOS (DETALLE) --}}
            <div id="seccion-grupos" class="hidden bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-blue-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-blue-800">
                        2. Grupos Asignados
                        <span id="titulo-distribucion-seleccionada" class="text-sm font-normal text-blue-600 ml-2"></span>
                    </h3>
                    
                    <button id="btn-nuevo-grupo" data-distribucion-id="" class="bg-blue-600 hover:bg-blue-800 text-white text-sm font-bold py-2 px-4 rounded transition">
                        <i class="fas fa-plus mr-2"></i> Agregar Grupo
                    </button>
                </div>
                
                <div class="p-6">
                    <div id="tabla-grupos-container">
                        <p class="text-gray-500 text-center py-4">Cargando grupos...</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL DE GRUPOS (CREAR/EDITAR) --}}
    <x-crud-modal modalId="grupoModal" formId="grupoForm" primaryKey="idGrupo" title="Grupo" enctype="multipart/form-data">
        <input type="hidden" id="idCupoDistribucion" name="idCupoDistribucion">

        <div class="mb-4">
            <label for="nombreGrupo" class="block text-sm font-medium text-gray-700">Nombre del Grupo *</label>
            <input type="text" id="nombreGrupo" name="nombreGrupo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required maxlength="45">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="fechaInicio" class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                <input type="date" id="fechaInicio" name="fechaInicio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="fechaFin" class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                <input type="date" id="fechaFin" name="fechaFin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
        </div>

        <div class="mb-4">
            <label for="idAsignatura" class="block text-sm font-medium text-gray-700">Asignatura *</label>
            <select id="idAsignatura" name="idAsignatura" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                <option value="">Seleccione una asignatura...</option>
                @if(isset($listaAsignaturas))
                    @foreach($listaAsignaturas as $asig)
                        <option value="{{ $asig->idAsignatura }}">{{ $asig->nombreAsignatura }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="mb-4">
            <label for="idDocenteCarrera" class="block text-sm font-medium text-gray-700">Docente Encargado *</label>
            <select id="idDocenteCarrera" name="idDocenteCarrera" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                <option value="">Seleccione un docente...</option>
                @if(isset($listaDocentesCarrera))
                    @foreach($listaDocentesCarrera as $dc)
                        <option value="{{ $dc->idDocenteCarrera }}">
                            {{ $dc->docente->nombresDocente ?? 'Sin Nombre' }} 
                            {{ $dc->docente->apellidoPaterno ?? '' }} 
                            ({{ $dc->sedeCarrera->nombreSedeCarrera ?? 'N/A' }})
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="mb-4 border-t pt-4 border-gray-100">
            <label for="archivo_dossier" class="block text-sm font-medium text-gray-700">Archivo Dossier (Opcional)</label>
            <input type="file" id="archivo_dossier" name="archivo_dossier" accept=".pdf,.doc,.docx,.jpg,.png" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            <p class="mt-1 text-xs text-gray-500">Formatos: PDF, Word o Imagen. Dejar vacío para mantener el actual.</p>
        </div>
    </x-crud-modal>

    {{-- MODAL PREVIEW DOSSIER --}}
    <div id="modalPreviewDossier" class="relative z-[150] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div id="backdropPreview" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity z-40 cursor-pointer backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-50 w-screen overflow-y-auto pointer-events-none">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 pointer-events-auto">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl border border-gray-200 h-[85vh] flex flex-col">
                    <div class="bg-gray-800 px-6 py-4 border-b border-gray-700 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            Vista Previa: <span id="preview-title" class="ml-2 font-normal text-gray-300"></span>
                        </h3>
                        <button id="btn-close-preview" class="text-gray-400 hover:text-white transition">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div class="flex-1 bg-gray-100 p-0 relative w-full h-full">
                        <iframe id="iframe-preview" src="" class="w-full h-full border-none"></iframe>
                        <div id="preview-error" class="hidden absolute inset-0 flex-col items-center justify-center bg-gray-100 z-0">
                            <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            <p class="text-gray-500 font-medium">Este formato de archivo no se puede previsualizar.</p>
                            <a id="btn-fallback-download" href="#" class="mt-4 text-indigo-600 hover:underline">Descargar archivo</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/js/app.js']) 
</x-app-layout>