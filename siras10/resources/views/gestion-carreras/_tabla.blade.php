<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
            <tr>
                <th class="py-2 px-4 text-left">Carrera Base</th>
                <th class="py-2 px-4 text-left">Nombre en Sede</th>
                <th class="py-2 px-4 text-left">Código</th>
                <th class="py-2 px-4 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($carrerasEspecificas as $sc)
            <tr class="border-b" id="sede-carrera-{{ $sc->idSedeCarrera }}">
                <td class="py-2 px-4">
                    <span>{{ $sc->carrera->nombreCarrera }}</span>
                </td>
                <td class="py-2 px-4">
                    @if($sc->nombreSedeCarrera && $sc->nombreSedeCarrera !== $sc->carrera->nombreCarrera)
                        <span class="text-blue-700">{{ $sc->nombreSedeCarrera }}</span>
                    @else
                        <span class="text-gray-600">{{ $sc->carrera->nombreCarrera }}</span>
                        <span class="text-xs text-gray-400 ml-1">(por defecto)</span>
                    @endif
                </td>
                <td class="py-2 px-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        {{ $sc->codigoCarrera }}
                    </span>
                </td>
                <td class="py-2 px-4">
                    <div class="flex space-x-2">
                        {{-- OPCIÓN 1: Botones con fondo y SVG integrado --}}
                        @can('sede-carrera.read')
                        <a
                            href="{{ route('sede-carrera.archivos', $sc->idSedeCarrera) }}"
                            title="Ver Archivos"
                            class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors duration-150"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"
                                />
                            </svg>
                        </a>

                        @endcan
                        
                        @can('sede-carrera.update')
                        <button
                            data-action="edit"
                            data-id="{{ $sc->idSedeCarrera }}"
                            title="Editar"
                            class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 hover:bg-amber-600 text-white rounded-md transition-colors duration-150"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                />
                            </svg>
                        </button>

                        @endcan
                        
                        @can('sede-carrera.delete')
                        <button
                            data-action="delete"
                            data-id="{{ $sc->idSedeCarrera }}"
                            title="Eliminar"
                            class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-150"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                />
                            </svg>
                        </button>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="py-8">
                    <div class="flex flex-col items-center justify-center text-gray-400">
                        <i class="fas fa-graduation-cap text-6xl mb-4"></i>
                        <p class="text-lg">No hay carreras asignadas a esta sede</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
