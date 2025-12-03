<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
            <tr>
                <th class="py-2 px-4 text-left whitespace-nowrap">Año</th>
                <th class="py-2 px-4 text-left whitespace-nowrap">Fecha de Subida</th>
                <th class="py-2 px-4 text-center whitespace-nowrap">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($programas as $programa)
            <tr class="border-b" data-asignatura-id="{{ $programa->idAsignatura }}">
                <td class="py-2 px-4 whitespace-nowrap">
                    {{ \Carbon\Carbon::parse($programa->fechaSubida)->format('Y') }}
                </td>
                <td class="py-2 px-4 whitespace-nowrap">
                    {{ \Carbon\Carbon::parse($programa->fechaSubida)->format('d/m/Y') }}
                </td>
                <td class="py-2 px-4 whitespace-nowrap">
                    <div class="flex justify-center space-x-2">
                        <button
                            type="button"
                            data-action="preview-programa-modal"
                            data-url="{{ asset('storage/' . $programa->documento) }}"
                            data-title="Programa de Asignatura"
                            data-asignatura="{{ $programa->asignatura->nombreAsignatura ?? 'Asignatura' }}"
                            data-fecha="{{ \Carbon\Carbon::parse($programa->fechaSubida)->format('d/m/Y') }}"
                            title="Ver documento"
                            class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                        
                        <a href="{{ route('sede-carrera.programas.download', ['programa' => $programa->idPrograma]) }}"
                           title="Descargar documento"
                           class="inline-flex items-center justify-center w-8 h-8 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </a>
                        
                        <button
                            type="button"
                            data-action="delete-programa"
                            data-url="{{ route('sede-carrera.programas.destroy', ['programa' => $programa->idPrograma]) }}"
                            data-programa-id="{{ $programa->idPrograma }}"
                            data-asignatura="{{ $programa->asignatura->nombreAsignatura ?? 'esta asignatura' }}"
                            data-reload-url="{{ route('sede-carrera.asignaturas.programas.list', ['asignatura' => $programa->idAsignatura]) }}"
                            title="Eliminar programa"
                            class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="py-4 px-4 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-file-pdf text-4xl text-gray-300 mb-2"></i>
                        <span>No hay programas registrados para esta asignatura.</span>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($programas->hasPages())
<div class="mt-4">
    {{ $programas->links() }}
</div>
@endif
