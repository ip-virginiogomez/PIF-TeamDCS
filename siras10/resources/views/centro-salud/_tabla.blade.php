<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
            <tr>
                @php
                    $getSortLink = function($column, $text) use ($sortBy, $sortDirection) {
                        $direction = ($sortBy === $column && $sortDirection == 'asc') ? 'desc' : 'asc';
                        $params = array_merge(request()->query(), ['sort_by' => $column, 'sort_direction' => $direction, 'page' => 1]);
                        $url = route('centro-salud.index', $params);
                        $icon = '<svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>';
                        if ($sortBy === $column) {
                             $icon = $sortDirection === 'asc' 
                                ? '<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>'
                                : '<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
                        }
                        return '<a href="'.$url.'" class="sort-link flex items-center gap-1 w-full h-full hover:bg-gray-100 p-1 rounded transition-colors duration-200">'.$text.' '.$icon.'</a>';
                    };
                @endphp
                <th class="py-2 px-4 text-left whitespace-nowrap"> {!! $getSortLink('nombreCentro', 'Nombre del Centro') !!}</th>
                <th class="py-2 px-4 text-left whitespace-nowrap"> {!! $getSortLink('director', 'Director') !!}</th>
                <th class="py-2 px-4 text-left whitespace-nowrap"> {!! $getSortLink('direccion', 'Dirección') !!}</th>
                <th class="py-2 px-4 text-left whitespace-nowrap"> {!! $getSortLink('ciudad.nombreCiudad', 'Ciudad') !!}</th>
                <th class="py-2 px-4 text-left whitespace-nowrap"> {!! $getSortLink('tipo_centro_salud.acronimo', 'Tipo') !!}</th>
                <th class="py-2 px-4 text-center whitespace-nowrap">Personal Asignado</th>
                <th class="py-2 px-4 text-left whitespace-nowrap">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($centrosSalud as $centro)
                <tr class="border-b" id="centro-{{ $centro->idCentroSalud }}">
                    <td class="py-2 px-4">
                        <span>{{ $centro->nombreCentro }}</span>
                    </td>
                    <td class="py-2 px-4">
                        <div>
                            <span class="text-gray-900">{{ $centro->director ?? 'No asignado' }}</span>
                            @if($centro->correoDirector)
                                <div class="text-sm text-gray-500">{{ $centro->correoDirector }}</div>
                            @endif
                        </div>
                    </td>
                    <td class="py-2 px-4 text-sm">
                        {{ $centro->direccion }}
                    </td>
                    <td class="py-2 px-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $centro->ciudad->nombreCiudad ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="py-2 px-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $centro->tipoCentroSalud->nombreTipo ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="py-2 px-4 text-center">
                        @if($centro->personal->isNotEmpty())
                            @php
                                $usuarios = $centro->personal->map(function($p) {
                                    return $p->usuario;
                                })->filter();
                            @endphp
                            @if($usuarios->isNotEmpty())
                                <button type="button" 
                                        data-action="view-personal"
                                        data-personal='@json($usuarios)'
                                        class="text-sky-600 hover:text-sky-900 font-medium flex items-center justify-center w-full transition-colors">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </button>
                            @else
                                <span class="text-gray-400 text-xs italic">Sin datos de usuario</span>
                            @endif
                        @else
                            <span class="text-gray-400 text-xs italic">Sin asignar</span>
                        @endif
                    </td>
                    <td class="py-2 px-4">
                        <div class="flex space-x-2">
                            @can('centro-salud.update')
                            <button data-action="edit" data-id="{{ $centro->idCentroSalud }}" title="Editar" class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 hover:bg-amber-600 text-white rounded-md transition-colors duration-150">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            @endcan
                            @can('centro-salud.delete')
                            <button data-action="delete" data-id="{{ $centro->idCentroSalud }}" title="Eliminar" class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-150">
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
                        <i class="fas fa-hospital text-4xl text-gray-300 mb-2"></i>
                        <span>No hay centros de salud registrados.</span>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($centrosSalud, 'links'))
    <div class="mt-4">
        {{ $centrosSalud->appends(request()->query())->links() }}
    </div>
@endif