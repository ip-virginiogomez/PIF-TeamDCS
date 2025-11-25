<div class="overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500">
        {{-- HEADERS --}}
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
            <tr>
                <th scope="col" class="px-6 py-3 font-semibold text-gray-600">
                    Nombre del Grupo
                </th>
                <th scope="col" class="px-6 py-3 font-semibold text-gray-600">
                    Asignatura
                </th>
                <th scope="col" class="px-6 py-3 font-semibold text-gray-600">
                    Docente Encargado
                </th>
                <th scope="col" class="px-6 py-3 font-semibold text-gray-600 text-center w-40">
                    Acciones
                </th>
            </tr>
        </thead>
        
        {{-- BODY --}}
        <tbody class="divide-y divide-gray-200">
            @forelse($grupos as $grupo)
                <tr class="bg-white hover:bg-gray-50 transition-colors duration-200">
                    
                    {{-- 1. Nombre del Grupo --}}
                    <td class="px-6 py-4 align-middle">
                        <span class="text-sm font-bold text-gray-900">
                            {{ $grupo->nombreGrupo }}
                        </span>
                    </td>

                    <td class="px-6 py-4 align-middle">
                        @if($grupo->asignatura)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ $grupo->asignatura->nombreAsignatura }}
                            </span>
                        @else
                            <span class="text-gray-400 text-xs italic">No especificada</span>
                        @endif
                    </td>

                    {{-- 2. Docente Encargado --}}
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

                    {{-- 3. Acciones (Centradas) --}}
                    <td class="px-6 py-4 align-middle text-center">
                        {{-- Usamos justify-center para centrar los botones --}}
                        <div class="flex justify-center space-x-2 items-center">
                            
                            {{-- Botón Editar --}}
                            <button data-action="edit" data-id="{{ $grupo->idGrupo }}" title="Editar" 
                                class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 hover:bg-amber-600 text-white rounded-md transition-colors duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            
                            {{-- Botón Eliminar --}}
                            <button data-action="delete" data-id="{{ $grupo->idGrupo }}" title="Eliminar" 
                                class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                            <a href="{{ route('grupos.dossier', $grupo->idGrupo) }}"
                            target="_blank" 
                            title="Ver Dossier" 
                            class="inline-flex items-center justify-center w-8 h-8 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition-colors duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{-- Icono de Documento/Reporte --}}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-6 py-10 text-center text-gray-500 bg-white">
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