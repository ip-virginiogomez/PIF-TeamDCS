<?php
// filepath: resources/views/convenios/_tabla.blade.php
?>
<div class="overflow-x-auto">
    @if($convenios->isEmpty())
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay convenios</h3>
            <p class="mt-1 text-sm text-gray-500">Comience creando un nuevo convenio.</p>
            <div class="mt-6">
                <button onclick="abrirModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    Crear Primer Convenio
                </button>
            </div>
        </div>
    @else
        <table class="min-w-full bg-white">
            <thead class="bg-gray-200">
                <tr>
                    @php
                        $link = function ($columna, $texto) use ($sortBy, $sortDirection) {
                            $direction = ($sortBy === $columna && $sortDirection == 'asc') ? 'desc' : 'asc';
                            $symbol = '';
                            if ($sortBy == $columna) {
                                $symbol = $sortDirection == 'asc' ? '↑' : '↓';
                            }
                            $url = route('convenios.index', ['sort_by' => $columna, 'sort_direction' => $direction]);
                            return "<a href=\"{$url}\" class='sort-link text-left font-bold'>{$texto} {$symbol}</a>";
                        };
                    @endphp
                    
                    <th class="py-2 px-4 text-left whitespace-nowrap">{!! $link('idConvenio', 'ID') !!}</th>
                    <th class="py-2 px-4 text-left whitespace-nowrap">{!! $link('centro_formador.nombreCentroFormador', 'Centro Formador') !!}</th>
                    <th class="py-2 px-4 text-left whitespace-nowrap">{!! $link('fechaSubida', 'Fecha Subida') !!}</th>
                    <th class="py-2 px-4 text-left whitespace-nowrap">{!! $link('anioValidez', 'Año Validez') !!}</th>
                    <th class="py-2 px-4 text-left whitespace-nowrap">Documento</th>
                    <th class="py-2 px-4 text-left whitespace-nowrap">Estado</th>
                    <th class="py-2 px-4 text-left whitespace-nowrap">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($convenios as $convenio)
                    <tr class="border-b" id="convenio-{{ $convenio->idConvenio }}">
                        <td class="py-2 px-4 whitespace-nowrap">
                            <span>{{ $convenio->idConvenio }}</span>
                        </td>
                        <td class="py-2 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $convenio->centroFormador->nombreCentroFormador ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="py-2 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ \Carbon\Carbon::parse($convenio->fechaSubida)->format('d/m/Y') }}
                            </span>
                        </td>
                        <td class="py-2 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $convenio->anioValidez }}
                            </span>
                        </td>
                        <td class="py-2 px-4">
                            @if($convenio->documento)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Disponible
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Sin documento
                                </span>
                            @endif
                        </td>
                        <td class="py-2 px-4">
                            @php
                                $anioActual = date('Y');
                                $vigente = $convenio->anioValidez >= $anioActual;
                            @endphp
                            @if($vigente)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Vigente
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Expirado
                                </span>
                            @endif
                        </td>
                        <td class="py-2 px-4">
                            <div class="flex space-x-2">
                                @if($convenio->documento)
                                    <button onclick="verDocumento({{ $convenio->idConvenio }})" title="Ver Documento" class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors duration-150">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                @endif
                                <button onclick="editarConvenio({{ $convenio->idConvenio }})" title="Editar" class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 hover:bg-amber-600 text-white rounded-md transition-colors duration-150">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button onclick="eliminarConvenio({{ $convenio->idConvenio }})" title="Eliminar" class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-150">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="mt-4">
            {{ $convenios->appends(request()->query())->links() }}
        </div>
    @endif
</div>