<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
            <tr>
                @php
                    $getSortLink = function($column, $text) use ($sortBy, $sortDirection) {
                        $direction = ($sortBy === $column && $sortDirection == 'asc') ? 'desc' : 'asc';
                        $params = array_merge(request()->query(), ['sort_by' => $column, 'sort_direction' => $direction, 'page' => 1]);
                        $url = route('centros-formadores.index', $params);
                        $icon = '<svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>';
                        if ($sortBy === $column) {
                             $icon = $sortDirection === 'asc' 
                                ? '<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>'
                                : '<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
                        }
                        return '<a href="'.$url.'" class="sort-link flex items-center gap-1 w-full h-full hover:bg-gray-100 p-1 rounded transition-colors duration-200">'.$text.' '.$icon.'</a>';
                    };
                @endphp
                <th class="py-2 px-4 text-left"> {!! $getSortLink('nombreCentroFormador', 'Centro Formador') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $getSortLink('fechaCreacion', 'Fecha Creación') !!}</th>
                <th class="py-2 px-4 text-center">Coordinador</th>
                <th class="py-2 px-4 text-center">Convenios</th>
                <th class="py-2 px-4 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($centrosFormadores as $centroFormador)
            <tr class="border-b" id="centro-formador-{{ $centroFormador->idCentroFormador }}">
                <td class="py-2 px-4">
                    <span>{{ $centroFormador->nombreCentroFormador }}</span>
                </td>
                <td class="py-2 px-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        {{ $centroFormador->fechaCreacion ? \Carbon\Carbon::parse($centroFormador->fechaCreacion)->format('d/m/Y') : 'N/A' }}
                    </span>
                </td>
                <td class="py-2 px-4 text-center">
                    @php
                        $coordinador = $centroFormador->coordinadorCampoClinicos->first()?->usuario;
                    @endphp
                    @if($coordinador)
                        <button type="button" 
                            data-action="view-coordinator" 
                            data-coordinator='@json($coordinador)'
                            class="text-blue-600 hover:text-blue-900 transition-colors duration-150"
                            title="Ver datos del coordinador">
                            <svg class="w-6 h-6 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </button>
                    @else
                        <span class="text-gray-400 text-xs italic">Sin asignar</span>
                    @endif
                </td>
                <td class="py-2 px-4 text-center">
                    @php
                        $convenios = $centroFormador->convenios;
                        $conveniosData = [];
                        foreach($convenios as $c) {
                            $conveniosData[] = [
                                'id' => $c->idConvenio,
                                'documento' => $c->documento,
                                'fechaInicio' => $c->fechaInicio,
                                'fechaFin' => $c->fechaFin,
                                'vigente' => $c->fechaFin && \Carbon\Carbon::parse($c->fechaFin)->isFuture()
                            ];
                        }
                    @endphp
                    @if($convenios->count() > 0)
                        <button type="button" 
                            data-action="view-convenios" 
                            data-centro-id="{{ $centroFormador->idCentroFormador }}"
                            data-centro-nombre="{{ $centroFormador->nombreCentroFormador }}"
                            data-convenios='@json($conveniosData)'
                            class="text-blue-600 hover:text-blue-900 transition-colors duration-150 text-sm font-medium"
                            title="Ver documentos">
                            Ver documentos
                        </button>
                    @else
                        <span class="text-gray-400 text-xs italic">Sin convenios</span>
                    @endif
                </td>
                <td class="py-2 px-4">
                    <div class="flex space-x-2">
                        @can('centros-formadores.update')
                        <button data-action="edit" data-id="{{ $centroFormador->idCentroFormador }}" title="Editar" class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 hover:bg-amber-600 text-white rounded-md transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        @endcan
                        @can('centros-formadores.delete')
                        <button data-action="delete" data-id="{{ $centroFormador->idCentroFormador }}" title="Eliminar" class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-4 px-4 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-university text-4xl text-gray-300 mb-2"></i>
                        <span>No hay centros formadores registrados.</span>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($centrosFormadores, 'links'))
    <div class="mt-4">
        {{ $centrosFormadores->appends(request()->query())->links() }}
    </div>
@endif