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
            <a href="{{ route('grupos.index') }}" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-sm transition">
                <svg class="w-4 h-4 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            {{-- TARJETA PRINCIPAL --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg border border-gray-200">
                
                {{-- ENCABEZADO --}}
                <div class="bg-sky-700 px-6 py-4 border-b border-[#0369a1]">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-white">Detalle del Dossier</h3>
                            <p class="text-sky-200 text-sm">{{ $grupo->nombreGrupo }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            {{-- Estado Badge --}}
                            @if($grupo->estadoDossier === 'Validado')
                                <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide shadow-sm border border-green-600">
                                    Validado
                                </span>
                            @elseif($grupo->estadoDossier === 'Rechazado')
                                <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide shadow-sm border border-red-600">
                                    Rechazado
                                </span>
                            @else
                                <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide shadow-sm border border-yellow-600">
                                    Pendiente
                                </span>
                            @endif

                            {{-- Botones de Acción --}}
                            @hasanyrole('Admin|Encargado Campo Clínico')
                                @if($grupo->estadoDossier === 'Pendiente')
                                    {{-- Validar --}}
                                    <form id="form-validar" action="{{ route('grupos.validarDossier', $grupo->idGrupo) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm transition shadow-md flex items-center border border-green-800">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Validar
                                        </button>
                                    </form>
                                    {{-- Rechazar --}}
                                    <button type="button" id="btn-open-rechazo" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm transition shadow-md flex items-center border border-red-800">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Rechazar
                                    </button>
                                @elseif($grupo->estadoDossier === 'Validado')
                                    <form id="form-revertir-validado" action="{{ route('grupos.revertirDossier', $grupo->idGrupo) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded text-sm transition shadow-md flex items-center border border-orange-700">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                            Revertir Validación
                                        </button>
                                    </form>
                                @elseif($grupo->estadoDossier === 'Rechazado')
                                    <form id="form-revertir-rechazado" action="{{ route('grupos.revertirDossier', $grupo->idGrupo) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded text-sm transition shadow-md flex items-center border border-orange-700">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                            Revertir Rechazo
                                        </button>
                                    </form>
                                @endif
                            @endhasanyrole
                        </div>
                    </div>
                </div>

                {{-- CUERPO DE DATOS --}}
                <div class="p-8">
                    
                    {{-- BARRA DE CUPOS --}}
                    <div class="bg-sky-50 border w-fit border-sky-100 rounded-lg p-5 mb-8 flex items-center justify-between shadow-sm">
                        <div class="flex items-center">
                            <div>
                                <h4 class="text-sky-900 font-bold text-lg">Cupos Distribuidos</h4>
                                <p class="text-sky-600 text-sm">Total de estudiantes asignados a este grupo</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="block text-4xl ml-4 font-extrabold text-sky-700">
                                {{ $dist->cantCupos ?? 0 }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        
                        {{-- SECCIÓN 1: ACADÉMICA --}}
                        <div class="space-y-5">
                            <div class="flex items-center space-x-2 border-b pb-2 mb-2">
                                <h4 class="text-gray-500 text-xs font-bold uppercase tracking-widest">Institución Académica</h4>
                            </div>
                            
                            <div>
                                <span class="block text-xs font-semibold text-gray-400 uppercase">Centro Formador</span>
                                <div class="font-medium text-gray-800">
                                    {{ $centroFormador->nombreCentroFormador ?? 'No especificado' }}
                                </div>
                            </div>
                            <div>
                                <span class="block text-xs font-semibold text-gray-400 uppercase">Carrera / Sede</span>
                                <div class="font-medium text-gray-800">
                                    {{ $sedeCarrera->nombreSedeCarrera ?? 'No especificado' }}
                                </div>
                                <div class="text-sm text-gray-500">{{ $sede->nombreSede ?? '' }}</div>
                            </div>
                            
                            {{-- TARJETA DOCENTE --}}
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 hover:border-sky-300 transition duration-200">
                                <span class="block text-xs font-semibold text-gray-400 uppercase mb-3">Docente a Cargo</span>
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
                                    
                                    @if($docente)
                                        <div class="flex space-x-2">
                                            <button id="btn-open-docente" class="text-sky-600 hover:text-white hover:bg-sky-600 p-2 rounded-md transition shadow-sm bg-white border border-gray-200" title="Ver Ficha">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
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
                                <h4 class="text-gray-500 text-xs font-bold uppercase tracking-widest">Campo Clínico</h4>
                            </div>
                            
                            <div>
                                <span class="block text-xs font-semibold text-gray-400 uppercase">Centro de Salud</span>
                                <div class="font-medium text-gray-800">
                                    {{ $centroSalud->nombreCentro ?? 'No especificado' }}
                                </div>
                            </div>

                            <div>
                                <span class="block text-xs font-semibold text-gray-400 uppercase">Unidad Clínica</span>
                                <div class="font-medium text-gray-800">
                                    {{ $unidad->nombreUnidad ?? 'No especificado' }}
                                </div>
                            </div>
                            <div>
                                <span class="block text-xs font-semibold text-gray-400 uppercase">Tipo de Práctica</span>
                                <div class="font-medium text-gray-800">
                                    {{ $tipoPractica->nombrePractica ?? 'General' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ======================================================== --}}
                    {{-- SECCIÓN NUEVA: ASIGNATURA                                --}}
                    {{-- ======================================================== --}}
                    <div class="mt-8 border-t border-gray-200 pt-8">
                        <div class="w-fit bg-white border border-gray-200 rounded-lg p-6 shadow-sm flex flex-col sm:flex-row items-center justify-between relative overflow-hidden">
                            {{-- Borde decorativo izquierdo --}}
                            <div class="absolute top-0 left-0 w-1 h-full bg-sky-700"></div>
                            
                            <div class="flex items-center z-10 w-full sm:w-auto mb-4 sm:mb-0">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Asignatura: {{ $grupo->asignatura->nombreAsignatura ?? 'No definida' }}</h3>
                                    <p class="text-gray-500 text-sm">Programa de la asignatura</p>
                                </div>
                            </div>

                            <div class="mt-4 sm:mt-0 z-10 flex space-x-2">
                                @php
                                    $docsAsignatura = [];
                                    
                                    // Programa
                                    if ($grupo->asignatura && $grupo->asignatura->programas->isNotEmpty()) {
                                        $ultimo = $grupo->asignatura->programas->last();
                                        if ($ultimo && $ultimo->documento) { 
                                            $docsAsignatura[] = [
                                                'nombre' => 'Programa de Asignatura',
                                                'url' => asset('storage/' . $ultimo->documento),
                                                'type' => 'programa'
                                            ];
                                        }
                                    }

                                    // Pauta
                                    if ($grupo->asignatura && $grupo->asignatura->pauta_evaluacion) {
                                        $docsAsignatura[] = [
                                            'nombre' => 'Pauta de Evaluación',
                                            'url' => asset('storage/' . $grupo->asignatura->pauta_evaluacion),
                                            'type' => 'pauta'
                                        ];
                                    }
                                @endphp

                                {{-- Botón Documentos Asignatura --}}
                                @if(count($docsAsignatura) > 0)
                                    <button type="button"
                                            data-docs='@json($docsAsignatura)'
                                            data-nombre="{{ $grupo->asignatura->nombreAsignatura }}"
                                            class="btn-open-asignatura-docs ml-6 text-sky-600 hover:text-white hover:bg-sky-600 p-2 rounded-md transition shadow-sm bg-white border border-gray-200 flex items-center gap-2 px-3" 
                                            title="Ver Documentos">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="font-medium">Ver Documentos</span>
                                    </button>
                                @else
                                    <span class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-400 text-sm font-bold rounded-md border border-gray-200 cursor-not-allowed select-none" title="Sin Documentos">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                        Sin Documentos
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN 3: NÓMINA DE ESTUDIANTES --}}
                    <div class="mt-10 border-t border-gray-200 pt-8">
                        <div class="flex justify-between items-center mb-6">
                            <div class="flex items-center">
                                <div class="p-2 bg-sky-100 text-sky-700 rounded-lg mr-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-gray-800 font-bold text-lg">Nómina de Estudiantes</h4>
                                    <p class="text-gray-500 text-sm">Listado oficial de alumnos inscritos</p>
                                </div>
                            </div>
                            @php
                                $inscritos = $grupo->alumnos->count();
                                $totalCupos = $grupo->cupoDistribucion->cantCupos ?? 0;
                                $estaLleno = $inscritos >= $totalCupos;
                            @endphp
                            @if($estaLleno)
                                <span class="inline-flex items-center px-4 py-2 bg-red-50 text-red-600 text-sm font-bold rounded-md border border-red-200 cursor-not-allowed shadow-sm select-none" title="No quedan cupos disponibles">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Grupo Completo ({{ $inscritos }}/{{ $totalCupos }})
                                </span>
                            @else                 
                                <button id="btn-open-add-alumno" type="button" class="bg-green-600 hover:bg-green-700 text-white text-sm font-bold py-2 px-4 rounded shadow-sm transition-colors duration-200 flex items-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg> Agregar Alumno
                                </button>
                            @endif
                        </div>

                        {{-- TABLA DE ALUMNOS --}}
                        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap w-16">Foto</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap w-32">RUN</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Nombre Completo</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap w-48">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($grupo->alumnos as $index => $alumno)
                                        @php
                                            $documentosAlumno = [];
                                            if($alumno->acuerdo) {
                                                $documentosAlumno[] = ['nombre' => 'Acuerdo de Confidencialidad', 'file' => $alumno->acuerdo];
                                            }
                                            foreach($alumno->vacunas as $vacuna) {
                                                if($vacuna->documento && $vacuna->estadoVacuna && $vacuna->estadoVacuna->nombreEstado === 'Activo') {
                                                    $documentosAlumno[] = [
                                                        'nombre' => $vacuna->tipoVacuna->nombreVacuna ?? 'Vacuna',
                                                        'file' => $vacuna->documento
                                                    ];
                                                }
                                            }
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border border-gray-200">
                                                    @if($alumno->foto)
                                                        <img src="{{ asset('storage/' . $alumno->foto) }}" class="h-full w-full object-cover">
                                                    @else
                                                    <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                                        <span class="text-xs">{{ substr($alumno->nombres, 0, 1) }}{{ substr($alumno->apellidoPaterno, 0, 1) }}{{ substr($alumno->apellidoMaterno, 0, 1) }}</span>
                                                    </div>                                                    
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $alumno->runAlumno }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $alumno->nombres ?? '' }} {{ $alumno->apellidoPaterno ?? '' }} {{ $alumno->apellidoMaterno ?? '' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <div class="flex items-center justify-center gap-2">
        
                                                    {{-- BOTÓN 1: VER FICHA --}}
                                                    <button type="button"
                                                            data-action="view-alumno-ficha"
                                                            data-alumno='@json($alumno)'
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-sky-600 hover:bg-sky-700 text-white rounded-md transition-colors duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500"
                                                            title="Ver Ficha">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </button>

                                                    {{-- BOTÓN 2: VER DOCUMENTOS --}}
                                                    <button type="button" 
                                                            data-action="view-alumno-docs"
                                                            data-nombre="{{ $alumno->nombres }} {{ $alumno->apellidoPaterno }}"
                                                            data-docs='@json($documentosAlumno)' 
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition-colors duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" 
                                                            title="Ver Documentos">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </button>

                                                    {{-- BOTÓN 3: ELIMINAR --}}
                                                    <button type="button" 
                                                            data-action="delete-alumno" 
                                                            data-run="{{ $alumno->runAlumno }}" 
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" 
                                                            title="Quitar del grupo">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>

                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-10 text-center bg-gray-50">
                                                <div class="flex flex-col items-center justify-center text-gray-400">
                                                    <svg class="w-12 h-12 mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                                                    <span class="text-base font-medium">Aún no hay alumnos en este grupo.</span>
                                                    <p class="text-xs mt-1">Utiliza el botón "Agregar Alumno" para inscribir estudiantes.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4 flex justify-end text-sm text-gray-500">
                            <p>Cupos ocupados: <span class="font-bold text-gray-900">{{ $grupo->alumnos->count() }}</span> / <span class="font-bold text-gray-900">{{ $grupo->cupoDistribucion->cantCupos ?? 0 }}</span></p>
                        </div>
                    </div>

                </div> 
                
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    {{-- Botones de pie de página --}}
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="grupo-id-actual" value="{{ $grupo->idGrupo }}">

    {{-- ========================================== --}}
    {{-- MODALES --}}
    {{-- ========================================== --}}

    {{-- 1. AGREGAR ALUMNO --}}
    <div id="modalAddAlumno" class="relative z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div id="modal-add-backdrop" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity z-40 cursor-pointer backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-50 w-screen overflow-y-auto pointer-events-none">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 pointer-events-auto">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-100">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900">Agregar Estudiante al Grupo</h3>
                            <button id="btn-close-add" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="mb-4">
                            <label for="search-alumno" class="block text-sm font-medium text-gray-700 mb-1">Buscar por Nombre o RUN</label>
                            <div class="relative">
                                <input type="text" id="search-alumno" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm pl-10" placeholder="Ej: 12345678-9 o Juan Perez" autocomplete="off">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                </div>
                            </div>
                        </div>
                        <div id="search-results" class="mt-2 max-h-60 overflow-y-auto border border-gray-200 rounded-md hidden bg-white shadow-sm"></div>
                        <div id="no-results" class="hidden text-center py-4 text-gray-500 text-sm">No se encontraron alumnos disponibles.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($docente)
        {{-- 2. FICHA DEL DOCENTE --}}
        <div id="modalDocente" class="relative z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div id="modal-backdrop" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity z-40 cursor-pointer backdrop-blur-sm"></div>
            <div class="fixed inset-0 z-50 w-screen overflow-y-auto pointer-events-none">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 pointer-events-auto">
                    <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200">
                        <div class="bg-gradient-to-r from-sky-700 to-blue-800 h-24 w-full absolute top-0 left-0 z-0"></div>
                        <button id="btn-close-x" class="absolute top-4 right-4 z-20 text-white hover:text-gray-200 focus:outline-none transition-transform hover:scale-110">
                            <svg class="w-6 h-6 drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        <div class="relative z-10 px-6 pt-12 pb-6">
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
                                <p class="text-sky-700 text-sm font-medium bg-sky-50 inline-block px-3 py-0.5 rounded-full mt-1 border border-sky-100">{{ $docente->profesion ?? 'Docente Clínico' }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-5 border border-gray-100 text-sm space-y-3 shadow-inner">
                                <div class="flex justify-between border-b border-gray-200 pb-2"><span class="text-gray-500 font-medium">RUN</span><span class="text-gray-900 font-semibold">{{ $docente->runDocente }}</span></div>
                                <div class="flex justify-between items-center border-b border-gray-200 pb-2"><span class="text-gray-500 font-medium">Correo</span><a class="text-sky-600 font-semibold truncate ml-4 block text-right">{{ $docente->correo ?? 'No registrado' }}</a></div>
                                <div class="flex justify-between items-center border-b border-gray-200 pb-2"><span class="text-gray-500 font-medium">Fecha de Nacimiento</span><a class="text-sky-600 font-semibold truncate ml-4 block text-right">{{ $docente->fechaNacto ? \Carbon\Carbon::parse($docente->fechaNacto)->format('d/m/Y') : 'No registrado' }}</a></div>
                            </div>
                            <div class="mt-6"><button id="btn-close-bottom" class="w-full inline-flex justify-center rounded-md bg-white border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none transition-colors">Cerrar Ficha</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. DOCUMENTOS DEL DOCENTE (DISEÑO UNIFICADO) --}}
        <div id="modalDocs" class="relative z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div id="modal-docs-backdrop" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity z-40 cursor-pointer backdrop-blur-sm"></div>
            <div class="fixed inset-0 z-50 w-screen overflow-y-auto pointer-events-none">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 pointer-events-auto">
                    <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl border border-gray-200 h-[85vh] flex flex-col">
                        {{-- Encabezado (Igual al del alumno) --}}
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                                <svg class="w-6 h-6 text-sky-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Documentos de {{ $docente->nombresDocente }} {{ $docente->apellidoPaterno }}
                            </h3>
                            <button id="btn-close-docs" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-sm transition">
                                <svg class="w-4 h-4 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            </button>
                        </div>
                        <div class="flex-1 overflow-hidden flex">
                            <div id="lista-docs-panel" class="w-full md:w-1/3 border-r border-gray-200 bg-white flex flex-col h-full">
                                <div class="flex-1 overflow-y-auto p-4">
                                    @include('docentes._documentos_lista', ['docente' => $docente, 'readonly' => true])
                                </div>
                                <div class="p-4 border-t border-gray-200 bg-gray-50 shrink-0">
                                    <button id="btn-back-docs" class="w-full inline-flex justify-center items-center rounded-md bg-white border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-100 focus:outline-none transition-colors">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                        Volver
                                    </button>
                                </div>
                            </div>
                            <div id="preview-panel" class="hidden md:flex w-full md:w-2/3 bg-gray-100 flex-col items-center justify-center p-4">
                                <div id="empty-state" class="text-center text-gray-400">
                                    <svg class="w-16 h-16 mb-4 opacity-30 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
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

    {{-- 4. DOCUMENTOS DEL ALUMNO --}}
    <div id="modalDocsAlumno" class="relative z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div id="modal-docs-alumno-backdrop" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity z-40 cursor-pointer backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-50 w-screen overflow-y-auto pointer-events-none">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 pointer-events-auto">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl border border-gray-200 h-[85vh] flex flex-col">
                    {{-- Encabezado --}}
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <svg class="w-6 h-6 text-sky-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Documentos de<span id="alumno-docs-nombre" class="ml-2"></span>
                        </h3>
                        <button id="btn-close-docs-alumno" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-sm transition">
                            <svg class="w-4 h-4 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </button>
                    </div>
                    <div class="flex-1 overflow-hidden flex">
                        <div class="w-full md:w-1/3 border-r border-gray-200 bg-white flex flex-col">
                            <div id="lista-docs-alumno" class="flex-1 overflow-y-auto p-4 space-y-2">
                                {{-- LISTA DINÁMICA --}}
                            </div>
                            <div class="p-4 border-t border-gray-200 bg-gray-50 shrink-0">
                                <button id="btn-back-docs-alumno" class="w-full inline-flex justify-center items-center rounded-md bg-white border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-100 focus:outline-none transition-colors">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                    Volver
                                </button>
                            </div>
                        </div>
                        <div class="hidden md:flex w-full md:w-2/3 bg-gray-100 flex-col items-center justify-center p-4">
                            <div id="empty-state-alumno" class="text-center text-gray-400">
                                <svg class="w-16 h-16 mb-4 opacity-30 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <p>Selecciona un documento para visualizar</p>
                            </div>
                            <iframe id="doc-iframe-alumno" src="" class="hidden w-full h-full rounded-lg border shadow-sm bg-white"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- MODAL 5: DOCUMENTOS ASIGNATURA             --}}
    {{-- ========================================== --}}
    <div id="modalAsignatura" class="relative z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div id="modal-asignatura-backdrop" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity z-40 cursor-pointer backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-50 w-screen overflow-y-auto pointer-events-none">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 pointer-events-auto">
                
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl border border-gray-200 h-[85vh] flex flex-col">
                    
                    {{-- Header --}}
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <svg class="w-6 h-6 text-sky-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Documentos de <span id="asignatura-modal-nombre" class="ml-2"></span>
                        </h3>
                        <button id="btn-close-asignatura" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-sm transition">
                            <svg class="w-4 h-4 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </button>
                    </div>

                    <div class="flex-1 overflow-hidden flex">
                        {{-- Sidebar Lista --}}
                        <div class="w-full md:w-1/3 border-r border-gray-200 bg-white flex flex-col">
                            <div id="lista-docs-asignatura" class="flex-1 overflow-y-auto p-4 space-y-2">
                                {{-- LISTA DINÁMICA --}}
                            </div>
                            <div class="p-4 border-t border-gray-200 bg-gray-50 shrink-0">
                                <button id="btn-back-asignatura" class="w-full inline-flex justify-center items-center rounded-md bg-white border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-100 focus:outline-none transition-colors">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                    Volver
                                </button>
                            </div>
                        </div>

                        {{-- Preview --}}
                        <div class="hidden md:flex w-full md:w-2/3 bg-gray-100 flex-col items-center justify-center p-4">
                            <div id="empty-state-asignatura" class="text-center text-gray-400">
                                <svg class="w-16 h-16 mb-4 opacity-30 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <p>Selecciona un documento para visualizar</p>
                            </div>
                            <iframe id="iframe-asignatura" src="" class="hidden w-full h-full rounded-lg border shadow-sm bg-white"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 6. FICHA DEL ALUMNO (DINÁMICA) --}}
    <div id="modalFichaAlumno" class="relative z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div id="modal-ficha-alumno-backdrop" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity z-40 cursor-pointer backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-50 w-screen overflow-y-auto pointer-events-none">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 pointer-events-auto">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200">
                    <div class="bg-gradient-to-r from-sky-700 to-blue-800 h-24 w-full absolute top-0 left-0 z-0"></div>
                    <button id="btn-close-ficha-alumno" class="absolute top-4 right-4 z-20 text-white hover:text-gray-200 focus:outline-none transition-transform hover:scale-110">
                        <svg class="w-6 h-6 drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    <div class="relative z-10 px-6 pt-12 pb-6">
                        <div class="flex justify-center mb-4">
                            <div class="h-24 w-24 rounded-full border-4 border-white shadow-md bg-white overflow-hidden flex items-center justify-center relative z-10">
                                <img id="ficha-alumno-foto" src="" class="h-full w-full object-cover">
                            </div>
                        </div>
                        <div class="text-center mb-6">
                            <h3 id="ficha-alumno-nombre" class="text-xl font-bold text-gray-900"></h3>
                            <p class="text-sky-700 text-sm font-medium bg-sky-50 inline-block px-3 py-0.5 rounded-full mt-1 border border-sky-100">Estudiante</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-5 border border-gray-100 text-sm space-y-3 shadow-inner">
                            <div class="flex justify-between border-b border-gray-200 pb-2"><span class="text-gray-500 font-medium">RUN</span><span id="ficha-alumno-run" class="text-gray-900 font-semibold"></span></div>
                            <div class="flex justify-between items-center border-b border-gray-200 pb-2"><span class="text-gray-500 font-medium">Correo</span><a id="ficha-alumno-correo" class="text-sky-600 font-semibold truncate ml-4 block text-right"></a></div>
                            <div class="flex justify-between items-center border-b border-gray-200 pb-2"><span class="text-gray-500 font-medium">Fecha de Nacimiento</span><a id="ficha-alumno-nacimiento" class="text-sky-600 font-semibold truncate ml-4 block text-right"></a></div>
                        </div>
                        <div class="mt-6"><button id="btn-close-ficha-alumno-bottom" class="w-full inline-flex justify-center rounded-md bg-white border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none transition-colors">Cerrar Ficha</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 7. MODAL RECHAZO DOSSIER --}}
    <div id="modalRechazo" class="relative z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div id="modal-rechazo-backdrop" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity z-40 cursor-pointer backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-50 w-screen overflow-y-auto pointer-events-none">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 pointer-events-auto">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <form id="form-rechazar" action="{{ route('grupos.rechazarDossier', $grupo->idGrupo) }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-100">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold leading-6 text-red-600 flex items-center">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    Rechazar Dossier
                                </h3>
                                <button type="button" id="btn-close-rechazo" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <p class="text-sm text-gray-500 mb-4">Por favor, indica el motivo por el cual se rechaza este dossier. Esta información será enviada al Coordinador de Campo Clínico.</p>
                            <div>
                                <label for="motivo" class="block text-sm font-medium text-gray-700 mb-1">Motivo del Rechazo</label>
                                <textarea id="motivo" name="motivo" rows="4" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" placeholder="Ej: Faltan documentos de vacunas..." required></textarea>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Rechazar Dossier</button>
                            <button type="button" id="btn-cancel-rechazo" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validar
            const formValidar = document.getElementById('form-validar');
            if (formValidar) {
                formValidar.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Validar Dossier?',
                        text: "Se notificará al Coordinador de Campo Clínico.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#16a34a', // green-600
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, validar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            formValidar.submit();
                        }
                    });
                });
            }

            // Revertir (Generic handler for both revert forms if I use a class, or specific IDs)
            const formRevertirValidado = document.getElementById('form-revertir-validado');
            if (formRevertirValidado) {
                formRevertirValidado.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Revertir Validación?',
                        text: "El estado volverá a Pendiente.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#f97316', // orange-500
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, revertir',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            formRevertirValidado.submit();
                        }
                    });
                });
            }

            const formRevertirRechazado = document.getElementById('form-revertir-rechazado');
            if (formRevertirRechazado) {
                formRevertirRechazado.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Revertir Rechazo?',
                        text: "El estado volverá a Pendiente.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#f97316', // orange-500
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, revertir',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            formRevertirRechazado.submit();
                        }
                    });
                });
            }

            // Rechazar
            const formRechazar = document.getElementById('form-rechazar');
            if (formRechazar) {
                formRechazar.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Rechazar Dossier?',
                        text: "Se enviará el motivo al Coordinador.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626', // red-600
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Sí, rechazar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            formRechazar.submit();
                        }
                    });
                });
            }
        });
    </script>

    @vite(['resources/js/app.js'])
</x-app-layout>