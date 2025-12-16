<div class="space-y-3">
    @forelse($vacunas as $vacuna)
        <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
            
            {{-- IZQUIERDA: Icono e Información --}}
            <div class="flex items-center space-x-3 overflow-hidden flex-1">
                
                {{-- Icono según tipo de archivo (Visual) --}}
                <div class="bg-indigo-50 p-2.5 rounded-full text-indigo-600 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
                
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        {{-- Nombre de la Vacuna --}}
                        <p class="text-sm font-bold text-gray-800 truncate">
                            {{ $vacuna->tipoVacuna->nombreVacuna ?? 'Vacuna ID: '.$vacuna->idTipoVacuna }}
                        </p>

                        {{-- LÓGICA DE COLORES DEL BADGE --}}
                        @php
                            $estado = strtolower($vacuna->estadoVacuna->nombreEstado ?? '');
                            $claseBadge = 'bg-gray-100 text-gray-800'; // Default

                            if (Str::contains($estado, 'activo') || Str::contains($estado, 'vigente') || Str::contains($estado, 'aprobado')) {
                                $claseBadge = 'bg-green-100 text-green-800 border border-green-200';
                            } elseif (Str::contains($estado, 'expirado') || Str::contains($estado, 'vencido') || Str::contains($estado, 'rechazado')) {
                                $claseBadge = 'bg-red-100 text-red-800 border border-red-200';
                            } elseif (Str::contains($estado, 'pendiente')) {
                                $claseBadge = 'bg-yellow-100 text-yellow-800 border border-yellow-200';
                            }
                        @endphp

                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium uppercase tracking-wide {{ $claseBadge }}">
                            {{ $vacuna->estadoVacuna->nombreEstado ?? 'Sin Estado' }}
                        </span>
                    </div>
                    
                    <div class="flex items-center space-x-3 text-xs text-gray-500">
                        {{-- Fecha --}}
                        <span class="flex items-center">
                            <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            {{ $vacuna->fechaSubida ? \Carbon\Carbon::parse($vacuna->fechaSubida)->format('d/m/Y') : '-' }}
                        </span>
                    </div>
                </div>
            </div>
            
            {{-- DERECHA: Botones --}}
            <div class="ml-4 flex-shrink-0 flex items-center space-x-1">
                {{-- Botón Ver --}}
                <button type="button" 
                        data-action="preview-vacuna" 
                        data-url="{{ asset('storage/' . $vacuna->documento) }}" 
                        class="group flex items-center justify-center w-8 h-8 rounded-full text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-all focus:outline-none"
                        title="Ver documento">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>

                {{-- Botón Cambiar Estado --}}
                <button type="button" 
                        data-action="change-status-vacuna" 
                        data-id="{{ $vacuna->getKey() }}"
                        data-current-status="{{ $vacuna->idEstadoVacuna }}"
                        class="group flex items-center justify-center w-8 h-8 rounded-full text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition-all focus:outline-none"
                        title="Cambiar Estado">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>

                {{-- Botón Eliminar --}}
                <button type="button" 
                        data-action="delete-vacuna" 
                        data-id="{{ $vacuna->getKey() }}" 
                        class="group flex items-center justify-center w-8 h-8 rounded-full text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all focus:outline-none"
                        title="Eliminar registro">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>

        </div>
    @empty
        {{-- Estado Vacío (Empty State) --}}
        <div class="flex flex-col items-center justify-center py-8 text-gray-400 border-2 border-dashed border-gray-200 rounded-lg bg-gray-50">
            <div class="p-3 bg-white rounded-full shadow-sm mb-3">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
            </div>
            <p class="text-sm font-medium text-gray-500">No hay vacunas registradas.</p>
            <p class="text-xs text-gray-400 mt-1">Usa el formulario superior para agregar una.</p>
        </div>
    @endforelse
</div>