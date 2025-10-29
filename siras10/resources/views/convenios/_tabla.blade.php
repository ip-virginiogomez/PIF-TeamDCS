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
                    
                    <th class="py-2 px-4 text-left">{!! $link('idConvenio', 'ID') !!}</th>
                    <th class="py-2 px-4 text-left">{!! $link('centro_formador.nombreCentroFormador', 'Centro Formador') !!}</th>
                    <th class="py-2 px-4 text-left">{!! $link('fechaSubida', 'Fecha Subida') !!}</th>
                    <th class="py-2 px-4 text-left">{!! $link('anioValidez', 'Año Validez') !!}</th>
                    <th class="py-2 px-4 text-left">Documento</th>
                    <th class="py-2 px-4 text-left">Estado</th>
                    <th class="py-2 px-4 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($convenios as $convenio)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="py-2 px-4">#{{ $convenio->idConvenio }}</td>
                        <td class="py-2 px-4">
                            {{ $convenio->centroFormador->nombreCentroFormador ?? 'N/A' }}
                        </td>
                        <td class="py-2 px-4">{{ \Carbon\Carbon::parse($convenio->fechaSubida)->format('d/m/Y') }}</td>
                        <td class="py-2 px-4">{{ $convenio->anioValidez }}</td>
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
                                    <button onclick="verDocumento({{ $convenio->idConvenio }})" 
                                            class="text-blue-600 hover:text-blue-900 text-sm font-medium"
                                            title="Ver documento">
                                        Ver
                                    </button>
                                @endif
                                
                                <button onclick="editarConvenio({{ $convenio->idConvenio }})" 
                                        class="text-yellow-600 hover:text-yellow-900 text-sm font-medium"
                                        title="Editar convenio">
                                    Editar
                                </button>
                                
                                <button onclick="eliminarConvenio({{ $convenio->idConvenio }})" 
                                        class="text-red-600 hover:text-red-900 text-sm font-medium"
                                        title="Eliminar convenio">
                                    Eliminar
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