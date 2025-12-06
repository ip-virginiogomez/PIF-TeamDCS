<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
            <tr>
                @php
                    $getSortLink = function($column, $text) use ($sortBy, $sortDirection) {
                        $direction = ($sortBy === $column && $sortDirection == 'asc') ? 'desc' : 'asc';
                        $params = array_merge(request()->query(), ['sort_by' => $column, 'sort_direction' => $direction, 'page' => 1]);
                        $url = route('sede.index', $params);
                        $icon = '<svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>';
                        if ($sortBy === $column) {
                             $icon = $sortDirection === 'asc' 
                                ? '<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>'
                                : '<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
                        }
                        return '<a href="'.$url.'" class="sort-link flex items-center gap-1 w-full h-full hover:bg-gray-100 p-1 rounded transition-colors duration-200">'.$text.' '.$icon.'</a>';
                    };
                @endphp
                <th class="py-2 px-4 text-left whitespace-nowrap"> {!! $getSortLink('idSede', 'ID') !!}</th>
                <th class="py-2 px-4 text-left whitespace-nowrap"> {!! $getSortLink('centroFormador.nombreCentroFormador', 'Centro Formador') !!}</th>
                <th class="py-2 px-4 text-left whitespace-nowrap"> {!! $getSortLink('nombreSede', 'Sede') !!}</th>
                <th class="py-2 px-4 text-left whitespace-nowrap"> {!! $getSortLink('direccion', 'Dirección') !!}</th>
                <th class="py-2 px-4 text-left whitespace-nowrap"> {!! $getSortLink('fechaCreacion', 'Fecha Creación') !!}</th>
                <th class="py-2 px-4 text-left whitespace-nowrap"> {!! $getSortLink('numContacto', 'Contacto') !!}</th>
                <th class="py-2 px-4 text-left whitespace-nowrap">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sedes as $sede)
            <tr class="border-b" id="sede-{{ $sede->idSede }}">
                <td class="py-2 px-4 whitespace-nowrap">
                    <span>{{ $sede->idSede }}</span>
                </td>
                <td class="py-2 px-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $sede->centroFormador->nombreCentroFormador ?? 'N/A' }}
                    </span>
                </td>
                <td class="py-2 px-4">
                    <span>{{ $sede->nombreSede }}</span>
                </td>
                <td class="py-2 px-4 text-sm">{{ $sede->direccion }}</td>
                <td class="py-2 px-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        {{ $sede->fechaCreacion ? \Carbon\Carbon::parse($sede->fechaCreacion)->format('d/m/Y') : 'N/A' }}
                    </span>
                </td>
                <td class="py-2 px-4">
                    @if($sede->numContacto)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $sede->numContacto }}
                        </span>
                    @else
                        <span class="text-gray-400 text-sm">Sin contacto</span>
                    @endif
                </td>
                <td class="py-2 px-4">
                    <div class="flex space-x-2">
                        @can('sede.update')
                        <button data-action="edit" data-id="{{ $sede->idSede }}" title="Editar" class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 hover:bg-amber-600 text-white rounded-md transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        @endcan
                        @can('sede.delete')
                        <button data-action="delete" data-id="{{ $sede->idSede }}" title="Eliminar" class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-150">
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
                <td colspan="7" class="py-4 px-4 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-building text-4xl text-gray-300 mb-2"></i>
                        <span>No hay sedes registradas.</span>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($sedes, 'links'))
    <div class="mt-4">
        {{ $sedes->appends(request()->query())->links() }}
    </div>
@endif