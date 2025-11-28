<div class="overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500">
        {{-- HEADERS --}}
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
            <tr>
                <th scope="col" class="px-6 py-3 font-semibold text-gray-600">Nombre del Grupo</th>
                <th scope="col" class="px-6 py-3 font-semibold text-gray-600">Asignatura</th>
                <th scope="col" class="px-6 py-3 font-semibold text-gray-600">Docente Encargado</th>
                {{-- NUEVA COLUMNA: FECHAS --}}
                <th scope="col" class="px-6 py-3 font-semibold text-gray-600 text-center">Período</th>
                {{-- NUEVA COLUMNA: ARCHIVO --}}
                <th scope="col" class="px-6 py-3 font-semibold text-gray-600 text-center">Archivo</th>
                <th scope="col" class="px-6 py-3 font-semibold text-gray-600 text-center w-40">Acciones</th>
            </tr>
        </thead>
        
        {{-- BODY --}}
        <tbody class="divide-y divide-gray-200">
            @forelse($grupos as $grupo)
                <tr class="bg-white hover:bg-gray-50 transition-colors duration-200">
                    
                    {{-- 1. Nombre del Grupo --}}
                    <td class="px-6 py-4 align-middle">
                        <span class="text-sm font-bold text-gray-900">{{ $grupo->nombreGrupo }}</span>
                    </td>

                    {{-- 2. Asignatura --}}
                    <td class="px-6 py-4 align-middle">
                        @if($grupo->asignatura)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ $grupo->asignatura->nombreAsignatura }}
                            </span>
                        @else
                            <span class="text-gray-400 text-xs italic">No especificada</span>
                        @endif
                    </td>

                    {{-- 3. Docente Encargado --}}
                    <td class="px-6 py-4 align-middle">
                        @if($grupo->docenteCarrera && $grupo->docenteCarrera->docente)
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $grupo->docenteCarrera->docente->nombresDocente }} 
                                    {{ $grupo->docenteCarrera->docente->apellidoPaterno }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $grupo->docenteCarrera->docente->apellidoMaterno }}
                                </span>
                            </div>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Sin Asignar
                            </span>
                        @endif
                    </td>

                    {{-- 4. NUEVA COLUMNA: FECHAS (Periodo) --}}
                    <td class="px-6 py-4 align-middle text-center whitespace-nowrap">
                        @if($grupo->fechaInicio && $grupo->fechaFin)
                            <div class="flex flex-col text-xs">
                                <span class="text-gray-900 font-medium">
                                    {{ $grupo->fechaInicio->format('d/m/Y') }}
                                </span>
                                <span class="text-gray-400 text-[10px] uppercase">hasta</span>
                                <span class="text-gray-900 font-medium">
                                    {{ $grupo->fechaFin->format('d/m/Y') }}
                                </span>
                            </div>
                        @else
                            <span class="text-gray-400 text-xs italic">Sin fechas</span>
                        @endif
                    </td>

                    {{-- 5. NUEVA COLUMNA: ARCHIVO DOSSIER --}}
                    <td class="px-6 py-4 align-middle text-center">
                        @if($grupo->archivo_dossier)
                            <div class="flex justify-center items-center space-x-2">
                                
                                {{-- Botón PREVIEW (Ojo) --}}
                                <button type="button"
                                        data-action="preview-file"
                                        data-url="{{ asset('storage/' . $grupo->archivo_dossier) }}"
                                        data-title="{{ $grupo->nombreGrupo }}"
                                        data-type="{{ pathinfo($grupo->archivo_dossier, PATHINFO_EXTENSION) }}"
                                        class="text-sky-600 hover:text-sky-800 transition-colors p-1 rounded hover:bg-sky-50"
                                        title="Ver Archivo">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>

                                {{-- Botón DESCARGA (Flecha) --}}
                                <a href="{{ asset('storage/' . $grupo->archivo_dossier) }}" download 
                                class="text-gray-400 hover:text-green-600 transition-colors p-1 rounded hover:bg-green-50" 
                                title="Descargar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>
                            </div>
                        @else
                            <span class="text-gray-400 text-xs italic select-none cursor-default">Pendiente</span>
                        @endif
                    </td>

                    {{-- 6. Acciones --}}
                    <td class="px-6 py-4 align-middle text-center">
                        <div class="flex justify-center space-x-2 items-center">
                            {{-- Editar --}}
                            <button data-action="edit" data-id="{{ $grupo->idGrupo }}" title="Editar" 
                                class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 hover:bg-amber-600 text-white rounded-md transition-colors duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            
                            {{-- Eliminar --}}
                            <button data-action="delete" data-id="{{ $grupo->idGrupo }}" title="Eliminar" 
                                class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>

                            {{-- Ver Dossier --}}
                            <a href="{{ route('dossier.index', $grupo->idGrupo) }}" title="Ver Dossier" 
                               class="inline-flex items-center justify-center w-8 h-8 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition-colors duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    {{-- Ajuste colspan a 6 porque agregamos 2 columnas nuevas --}}
                    <td colspan="6" class="px-6 py-10 text-center text-gray-500 bg-white">
                        <div class="flex flex-col items-center justify-center space-y-2">
                            <div class="p-3 bg-gray-100 rounded-full">
                                <i class="fas fa-layer-group text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-base font-medium text-gray-600">No hay grupos creados</p>
                            <p class="text-sm text-gray-400">Selecciona una distribución arriba y agrega un grupo.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($grupos instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="px-6 py-3 border-t bg-gray-50">
            {{ $grupos->links() }}
        </div>
    @endif
</div>