<div class="overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                @php
                    $getSortLink = function($column, $text) use ($sortBy, $sortDirection) {
                        $direction = ($sortBy === $column && $sortDirection == 'asc') ? 'desc' : 'asc';
                        $params = array_merge(request()->query(), ['sort_by' => $column, 'sort_direction' => $direction, 'page' => 1]);
                        $url = route('grupos.index', $params);
                        $icon = '<svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>';
                        if ($sortBy === $column) {
                            $icon = $sortDirection === 'asc' 
                                ? '<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>'
                                : '<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
                        }
                        return '<a href="'.$url.'" class="sort-link flex items-center gap-1 w-full h-full hover:bg-gray-100 p-1 rounded transition-colors duration-200">'.$text.' '.$icon.'</a>';
                    };
                @endphp
                <th scope="col" class="px-6 py-3">
                    {!! $getSortLink('centro_formador', 'Centro Formador') !!}
                </th>
                <th scope="col" class="px-6 py-3">
                    {!! $getSortLink('sede_carrera', 'Sede / Carrera') !!}
                </th>
                <th scope="col" class="px-6 py-3">
                    {!! $getSortLink('centro_salud', 'Centro de Salud') !!}
                </th>
                <th scope="col" class="px-6 py-3">
                    {!! $getSortLink('unidad_clinica', 'Unidad Clínica') !!}
                </th>
                <th scope="col" class="px-6 py-3">
                    {!! $getSortLink('tipo_practica', 'Tipo de Práctica') !!}
                </th>
                <th scope="col" class="px-6 py-3 text-center">Cupos</th>
                <th scope="col" class="px-6 py-3 text-center">Fechas</th>
                <th scope="col" class="px-6 py-3 text-center">Horario</th>
                <th scope="col" class="px-6 py-3 text-center">Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($distribuciones as $dist)
                @php $oferta = $dist->cupoOferta; @endphp
                
                <tr class="bg-white border-b hover:bg-gray-50 transition row-distribucion" data-id="{{ $dist->idCupoDistribucion }}">

                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">
                            {{ $dist->cupoDemanda->sedeCarrera->sede->centroFormador->nombreCentroFormador ?? 'N/A' }}
                        </div>
                    </td>
                    
                    {{-- Sede y Carrera --}}
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $dist->cupoDemanda->sedeCarrera->nombreSedeCarrera ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-500">{{ $dist->cupoDemanda->sedeCarrera->sede->nombreSede ?? '' }}</div>
                    </td>
                    {{-- Centro de Salud --}}
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">
                            {{ $oferta->unidadClinica->centroSalud->nombreCentro ?? 'N/A' }}
                        </div>
                    </td>
                    {{-- Unidad Clínica --}}
                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $oferta->unidadClinica->nombreUnidad ?? 'N/A' }}
                    </td>
                    {{-- Tipo de Práctica --}}
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            {{ $oferta->tipoPractica->nombrePractica ?? 'N/A' }}
                        </span>
                    </td>
                    {{-- Cupos --}}
                    <td class="px-6 py-4 text-center">
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded border border-blue-400">
                            {{ $dist->cantCupos }}
                        </span>
                    </td>
                    
                    {{-- Fechas --}}
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        @if($oferta)
                            <div class="text-gray-900">{{ \Carbon\Carbon::parse($oferta->fechaEntrada)->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-400">hasta</div>
                            <div class="text-gray-900">{{ \Carbon\Carbon::parse($oferta->fechaSalida)->format('d/m/Y') }}</div>
                        @else - @endif
                    </td>
                    {{-- Horario --}}
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        @if($oferta && $oferta->horarios->count() > 0)
                            <button 
                                type="button"
                                onclick='verHorario(@json($oferta->horarios))'
                                title="Ver Horario"
                                class="inline-flex items-center justify-center w-8 h-8 bg-teal-500 hover:bg-teal-600 text-white rounded-md transition-colors duration-150">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </button>
                        @else
                            <span class="text-gray-400 text-xs">Sin horario</span>
                        @endif
                    </td>
                    {{-- Botón Acción --}}
                    <td class="px-6 py-4 text-center">
                        <button 
                            type="button"
                            data-action="select-distribucion" 
                            data-id="{{ $dist->idCupoDistribucion }}"
                            data-summary="{{ $oferta->unidadClinica->nombreUnidad ?? 'Unidad' }} - {{ $dist->sedeCarrera->nombreSedeCarrera ?? 'Carrera' }}"
                            class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-2 focus:outline-none shadow-sm transition-colors duration-200">
                            Asignar Grupos
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="px-6 py-4 text-center text-gray-500">No se encontraron distribuciones disponibles.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $distribuciones->links() }} 
    </div>
</div>