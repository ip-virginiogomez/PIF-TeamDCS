<div class="overflow-x-auto">
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
                        $url = route('tipos-centro-formador.index', ['sort_by' => $columna, 'sort_direction' => $direction]);
                        return "<a href=\"{$url}\" class='sort-link text-left font-bold'>{$texto} {$symbol}</a>";
                    };
                @endphp
                <th class="py-2 px-4 text-left"> {!! $link('idTipoCentroFormador', 'ID') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('nombreTipo', 'Nombre Tipo') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('fechaCreacion', 'Fecha Creación') !!}</th>
                <th class="py-2 px-4 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tiposCentro as $tipos)
            <tr class="border-b" id="tipos-{{ $tipos->idTipoCentroFormador }}">
                <td class="py-2 px-4">
                    <span>{{ $tipos->idTipoCentroFormador }}</span>
                </td>
                <td class="py-2 px-4">
                    <span>{{ $tipos->nombreTipo }}</span>
                </td>
                <td class="py-2 px-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        {{ $tipos->fechaCreacion ? \Carbon\Carbon::parse($tipos->fechaCreacion)->format('d/m/Y') : 'N/A' }}
                    </span>
                </td>
                <td class="py-2 px-4">
                    <div class="flex space-x-2">
                        <button data-action="edit" data-id="{{ $tipos->idTipoCentroFormador }}" title="Editar" class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 hover:bg-amber-600 text-white rounded-md transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button data-action="delete" data-id="{{ $tipos->idTipoCentroFormador }}" title="Eliminar" class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-4 px-4 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-building text-4xl text-gray-300 mb-2"></i>
                        <span>No hay tipos de centro formador registrados.</span>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if(method_exists($tiposCentro, 'links'))
        <div class="mt-4">
            {{ $tiposCentro->appends(request()->query())->links() }}
        </div>
    @endif
</div>