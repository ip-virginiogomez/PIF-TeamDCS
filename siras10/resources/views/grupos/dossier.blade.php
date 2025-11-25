<x-app-layout>
    {{-- 1. DEFINICIÓN DE VARIABLES --}}
    @php
        $dist = $grupo->cupoDistribucion;
        $oferta = $dist->cupoOferta ?? null;
        $sedeCarrera = $dist->sedeCarrera ?? null;
        $sede = $sedeCarrera->sede ?? null;
        $centroFormador = $sede->centroFormador ?? null;
        $unidad = $oferta->unidadClinica ?? null;
        $centroSalud = $unidad->centroSalud ?? null;
        $tipoPractica = $oferta->tipoPractica ?? null;
        
        $docente = $grupo->docenteCarrera->docente ?? null; 
    @endphp

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dossier del Grupo') }}: {{ $grupo->nombreGrupo }}
            </h2>
            <a href="{{ route('grupos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-sm transition">
                <i class="fas fa-arrow-left mr-2"></i> Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- TARJETA PRINCIPAL --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg border border-gray-200">
                
                {{-- ENCABEZADO --}}
                <div class="bg-sky-700 px-6 py-4 border-b border-[#0369a1]">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-white">Detalle del Dossier</h3>
                            <p class="text-sky-200 text-sm">{{ $grupo->nombreGrupo }}</p>
                        </div>
                        <div class="bg-white text-[#0369a1] px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm">
                            {{ $grupo->asignatura->nombreAsignatura ?? 'Sin Asignatura' }}
                        </div>
                    </div>
                </div>

                {{-- CUERPO DE DATOS --}}
                <div class="p-8">
                    
                    {{-- BARRA DE CUPOS --}}
                    <div class="bg-sky-50 border border-sky-100 rounded-lg p-5 mb-8 flex items-center justify-between shadow-sm">
                        <div class="flex items-center">
                            <div>
                                <h4 class="text-sky-900 font-bold text-lg">Cupos Distribuidos</h4>
                                <p class="text-sky-600 text-sm">Total de estudiantes asignados a este grupo</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="block text-4xl font-extrabold text-sky-700">
                                {{ $dist->cantCupos ?? 0 }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        
                        {{-- SECCIÓN 1: ACADÉMICA --}}
                        <div class="space-y-5">
                            <div class="flex items-center space-x-2 border-b pb-2 mb-2">
                                <i class="fas fa-university text-gray-400"></i>
                                <h4 class="text-gray-500 text-xs font-bold uppercase tracking-widest">Institución Académica</h4>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase">Centro Formador</label>
                                <div class="font-medium text-gray-800">
                                    {{ $centroFormador->nombreCentroFormador ?? 'No especificado' }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase">Carrera / Sede</label>
                                <div class="font-medium text-gray-800">
                                    {{ $sedeCarrera->nombreSedeCarrera ?? 'No especificado' }}
                                </div>
                                <div class="text-sm text-gray-500">{{ $sede->nombreSede ?? '' }}</div>
                            </div>
                            
                            {{-- TARJETA DOCENTE --}}
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 hover:border-sky-300 transition duration-200">
                                <label class="block text-xs font-semibold text-gray-400 uppercase mb-3">Docente a Cargo</label>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-sky-100 flex items-center justify-center text-sky-600 mr-3 overflow-hidden border border-sky-200">
                                            @if($docente && $docente->foto)
                                                <img src="{{ asset('storage/' . $docente->foto) }}" class="h-full w-full object-cover">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ $docente->nombresDocente }}+{{ $docente->apellidoPaterno }}&background=bae6fd&color=0369a1&size=128" class="h-full w-full object-cover">
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 text-sm leading-tight">
                                                {{ $docente->nombresDocente ?? 'Sin' }} 
                                                {{ $docente->apellidoPaterno ?? 'Asignar' }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $docente->tituloProfesional ?? 'Docente Clínico' }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- BOTÓN ABRIR FICHA --}}
                                    @if($docente)
                                        <div class="flex space-x-2">
                                            {{-- Botón Ficha --}}
                                            <button id="btn-open-docente" class="text-sky-600 hover:text-white hover:bg-sky-600 p-2 rounded-md transition shadow-sm bg-white border border-gray-200" title="Ver Ficha">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            {{-- Botón Docs Directo --}}
                                            <button id="btn-open-docs" class="text-red-600 hover:text-white hover:bg-red-600 p-2 rounded-md transition shadow-sm bg-white border border-gray-200" title="Ver Documentos">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- SECCIÓN 2: CLÍNICA --}}
                        <div class="space-y-5">
                            <div class="flex items-center space-x-2 border-b pb-2 mb-2">
                                <i class="fas fa-clinic-medical text-gray-400"></i>
                                <h4 class="text-gray-500 text-xs font-bold uppercase tracking-widest">Campo Clínico</h4>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase">Centro de Salud</label>
                                <div class="font-medium text-gray-800">
                                    {{ $centroSalud->nombreCentro ?? 'No especificado' }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase">Unidad Clínica</label>
                                <div class="font-medium text-gray-800">
                                    {{ $unidad->nombreUnidad ?? 'No especificado' }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase">Tipo de Práctica</label>
                                <div class="font-medium text-gray-800">
                                    {{ $tipoPractica->nombrePractica ?? 'General' }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button class="text-gray-600 hover:text-gray-900 font-medium text-sm transition flex items-center">
                        <i class="fas fa-print mr-2"></i> Imprimir Ficha
                    </button>
                    <button class="bg-sky-700 hover:bg-sky-800 text-white font-bold py-2 px-4 rounded shadow-sm text-sm transition flex items-center">
                        <i class="fas fa-file-pdf mr-2"></i> Descargar PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if($docente)
        {{-- ========================================== --}}
        {{-- MODAL 1: FICHA DEL DOCENTE --}}
        {{-- ========================================== --}}
        <div id="modalDocente" class="relative z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div id="modal-backdrop" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity z-40 cursor-pointer backdrop-blur-sm"></div>

            <div class="fixed inset-0 z-50 w-screen overflow-y-auto pointer-events-none">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 pointer-events-auto">
                    <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200">
                        
                        {{-- Header Modal --}}
                        <div class="bg-gradient-to-r from-sky-700 to-blue-800 h-24 w-full absolute top-0 left-0 z-0"></div>
                        <button id="btn-close-x" class="absolute top-4 right-4 z-20 text-white hover:text-gray-200 focus:outline-none transition-transform hover:scale-110">
                            <i class="fas fa-times text-xl shadow-sm"></i>
                        </button>

                        <div class="relative z-10 px-6 pt-12 pb-6">
                            
                            {{-- Foto --}}
                            <div class="flex justify-center mb-4">
                                <div class="h-24 w-24 rounded-full border-4 border-white shadow-md bg-white overflow-hidden flex items-center justify-center relative z-10">
                                    @if($docente->foto)
                                        <img src="{{ asset('storage/' . $docente->foto) }}" class="h-full w-full object-cover">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ $docente->nombresDocente }}+{{ $docente->apellidoPaterno }}&background=bae6fd&color=0369a1&size=128" class="h-full w-full object-cover">
                                    @endif
                                </div>
                            </div>

                            <div class="text-center mb-6">
                                <h3 class="text-xl font-bold text-gray-900">{{ $docente->nombresDocente }} {{ $docente->apellidoPaterno }}</h3>
                                <p class="text-sky-700 text-sm font-medium bg-sky-50 inline-block px-3 py-0.5 rounded-full mt-1 border border-sky-100">
                                    {{ $docente->profesion ?? 'Docente Clínico' }}
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-5 border border-gray-100 text-sm space-y-3 shadow-inner">
                                <div class="flex justify-between border-b border-gray-200 pb-2">
                                    <span class="text-gray-500 font-medium">RUN</span>
                                    <span class="text-gray-900 font-semibold">{{ $docente->runDocente }}</span>
                                </div>
                                <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                                    <span class="text-gray-500 font-medium">Correo</span>
                                    <a class="text-sky-600 font-semibold truncate ml-4 block text-right">{{ $docente->correo ?? 'No registrado' }}</a>
                                </div>
                                <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                                    <span class="text-gray-500 font-medium">Fecha de Nacimiento</span>
                                    <a class="text-sky-600 font-semibold truncate ml-4 block text-right">{{ $docente->fechaNacto ? \Carbon\Carbon::parse($docente->fechaNacto)->format('d/m/Y') : 'No registrado' }}</a>
                                </div>
                            </div>
                            <div class="mt-6">
                                <button id="btn-close-bottom" type="button" class="w-full inline-flex justify-center rounded-md bg-white border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none transition-colors">
                                    Cerrar Ficha
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- ========================================== --}}
        {{-- MODAL 2: DOCUMENTOS DEL DOCENTE --}}
        {{-- ========================================== --}}
        <div id="modalDocs" class="relative z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div id="modal-docs-backdrop" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity z-40 cursor-pointer backdrop-blur-sm"></div>

            <div class="fixed inset-0 z-50 w-screen overflow-y-auto pointer-events-none">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 pointer-events-auto">
                    
                    <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl border border-gray-200 h-[85vh] flex flex-col">
                        
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                                <i class="fas fa-folder-open text-yellow-500 mr-2"></i> 
                                Documentos de {{ $docente->nombresDocente }}
                            </h3>
                            <button id="btn-close-docs" class="text-gray-400 hover:text-gray-600 transition">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>

                        <div class="flex-1 overflow-hidden flex">
                            <div id="lista-docs-panel" class="w-full md:w-1/3 border-r border-gray-200 bg-white flex flex-col">
                                @include('docentes._documentos_lista', ['docente' => $docente, 'readonly' => true])
                            </div>
                            <div class="p-4 border-t border-gray-200 bg-gray-50">
                                <button id="btn-back-docs" type="button" class="p-4 border-t border-gray-200 bg-gray-50 shrink-0 text-gray-600 hover:text-gray-900 font-medium text-sm transition flex items-center">
                                    <i class="fas fa-arrow-left mr-2 text-gray-400"></i> Volver
                                </button>
                            </div>
                            {{-- PREVIEW --}}
                            <div id="preview-panel" class="hidden md:flex w-full md:w-2/3 bg-gray-100 flex-col items-center justify-center p-4">
                                <div id="empty-state" class="text-center text-gray-400">
                                    <i class="fas fa-eye text-5xl mb-4 opacity-30"></i>
                                    <p>Selecciona un documento para visualizar</p>
                                </div>
                                <iframe id="doc-iframe" src="" class="hidden w-full h-full rounded-lg border shadow-sm bg-white"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @vite(['resources/js/app.js', 'resources/js/GrupoDossier.js'])
</x-app-layout>