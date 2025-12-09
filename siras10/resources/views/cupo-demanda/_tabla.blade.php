@php
    $sortBy = $sortBy ?? null;
    $sortDirection = $sortDirection ?? 'asc';

    $getSortLink = function($column, $text, $center = false) use ($sortBy, $sortDirection) {
        $direction = ($sortBy === $column && $sortDirection == 'asc') ? 'desc' : 'asc';
        $url = request()->fullUrlWithQuery(['sort_by' => $column, 'sort_direction' => $direction, 'page' => 1]);
        
        $icon = '<svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>';
        
        if ($sortBy === $column) {
                $icon = $sortDirection === 'asc' 
                ? '<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>'
                : '<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
        }
        
        $justify = $center ? 'justify-center' : '';
        return '<a href="'.$url.'" class="sort-link flex items-center '.$justify.' gap-1 w-full h-full hover:bg-gray-100 p-1 rounded transition-colors duration-200">'.$text.' '.$icon.'</a>';
    };
@endphp

<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
            <tr>
                <th class="py-2 px-4 text-left whitespace-nowrap">
                    {!! $getSortLink('sede', 'Sede') !!}
                </th>
                <th class="py-2 px-4 text-left whitespace-nowrap">
                    {!! $getSortLink('carrera', 'Carrera') !!}
                </th>
                <th class="py-2 px-4 text-left whitespace-nowrap">
                    {!! $getSortLink('asignatura', 'Asignatura') !!}
                </th>
                <th class="py-2 px-4 text-left whitespace-nowrap">
                    {!! $getSortLink('tipo_practica', 'Tipo de Práctica') !!}
                </th>
                <th class="py-2 px-4 text-center whitespace-nowrap">
                    {!! $getSortLink('cupos', 'Cupos Solicitados', true) !!}
                </th>
                <th class="py-2 px-4 text-center whitespace-nowrap">Cupos Asignados</th>
                <th class="py-2 px-4 text-center whitespace-nowrap">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($demandas as $demanda)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 px-4 whitespace-nowrap">
                        {{ $demanda->sedeCarrera->sede->nombreSede }}
                    </td>
                    <td class="py-2 px-4 whitespace-nowrap">
                        {{ $demanda->sedeCarrera->nombreSedeCarrera }}
                    </td>
                    <td class="py-2 px-4 whitespace-nowrap">
                        {{ $demanda->asignatura }}
                    </td>
                    <td class="py-2 px-4 whitespace-nowrap">
                        {{ $demanda->nombreTipoPractica ?? '-' }}
                    </td>
                    <td class="py-2 px-4 whitespace-nowrap text-center">
                        {{ $demanda->cuposSolicitados }}
                    </td>
                    <td class="py-2 px-4 text-center whitespace-nowrap">
                        @php
                            $cuposAsignados = $demanda->cupo_distribuciones_sum_cant_cupos ?? 0;
                            $porcentaje = $demanda->cuposSolicitados > 0 ? ($cuposAsignados / $demanda->cuposSolicitados) * 100 : 0;
                            $colorClase = $cuposAsignados == 0 ? 'bg-gray-100 text-gray-800' : 
                                        ($porcentaje >= 100 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800');
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClase }}">
                            {{ $cuposAsignados }} / {{ $demanda->cuposSolicitados }}
                        </span>
                    </td>
                    <td class="py-2 px-4 text-center">
                        <div class="flex justify-center space-x-2">
                            {{-- Botón Editar --}}
                            @can('cupo-demandas.update')
                            <button data-action="edit" data-id="{{ $demanda->idDemandaCupo }}" title="Editar" class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 hover:bg-amber-600 text-white rounded-md transition-colors duration-150">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            @endcan
                            
                            {{-- Botón Eliminar --}}
                            @can('cupo-demandas.delete')
                            <button data-action="delete" data-id="{{ $demanda->idDemandaCupo }}" title="Eliminar" class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-150">
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
                            <span>No hay demandas registradas para este periodo.</span>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4 px-4">
        {{ $demandas->appends(request()->query())->links() }}
    </div>
</div>
