<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
            <tr>
                @php
                    $getSortLink = function($column, $text) use ($sortBy, $sortDirection) {
                        $direction = ($sortBy === $column && $sortDirection == 'asc') ? 'desc' : 'asc';
                        $params = array_merge(request()->query(), ['sort_by' => $column, 'sort_direction' => $direction, 'page' => 1]);
                        $url = route('cupo-distribuciones.index', $params);
                        $icon = '<svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>';
                        if ($sortBy === $column) {
                             $icon = $sortDirection === 'asc' 
                                ? '<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>'
                                : '<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
                        }
                        return '<a href="'.$url.'" class="sort-link flex items-center gap-1 w-full h-full hover:bg-gray-100 p-1 rounded transition-colors duration-200">'.$text.' '.$icon.'</a>';
                    };
                @endphp

                <th class="py-2 px-4 text-left"> {!! $getSortLink('idCupoDistribucion', 'ID') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $getSortLink('sedeCarrera.sede.centroFormador.nombreCentroFormador', 'Centro Formador (Sede)') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $getSortLink('sedeCarrera.carrera.nombreCarrera', 'Carrera') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $getSortLink('cantCupos', 'Cupos Asignados') !!}</th>
                <th class="py-2 px-4 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($distribuciones as $distribucion)
            <tr class="border-b" id="dist-{{ $distribucion->idCupoDistribucion }}">
                <td class="py-2 px-4">
                    {{-- Estilo Sede: Texto simple --}}
                    <span>{{ $distribucion->idCupoDistribucion }}</span>
                </td>

                {{-- COLUMNA 1: Centro Formador (Sede) --}}
                <td class="py-2 px-4">
                    {{-- Estilo Sede: "Pill" azul para la relación principal --}}
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $distribucion->sedeCarrera->sede->centroFormador->nombreCentroFormador ?? 'CF Desc.' }}
                    </span>
                    <div class="text-gray-500">
                        ({{ $distribucion->sedeCarrera->sede->nombreSede ?? 'Sede Desc.' }})
                    </div>
                </td>

                {{-- COLUMNA 2: Carrera --}}
                <td class="py-2 px-4">
                    {{-- Estilo Sede: Texto simple --}}
                    <div>
                        {{ $distribucion->sedeCarrera->nombreSedeCarrera ?: ($distribucion->sedeCarrera->carrera->nombreCarrera ?? 'Carrera Desc.') }}
                    </div>
                </td>
                
                <td class="py-2 px-4">
                    {{-- Estilo Sede: "Pill" verde para números/contacto --}}
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ $distribucion->cantCupos }}
                    </span>
                </td> 
                
                <td class="py-2 px-4">
                    <div class="flex space-x-2">
                        <button type="button" data-action="edit" data-id="{{ $distribucion->idCupoDistribucion }}" title="Editar" class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 hover:bg-amber-600 text-white rounded-md transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button type="button" data-action="delete" data-id="{{ $distribucion->idCupoDistribucion }}" title="Eliminar" class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-4 px-4 text-center text-gray-500">
                    {{-- CAMBIO: Mensaje de vacío estilo Sede --}}
                    <div class="flex flex-col items-center">
                        <i class="fas fa-exclamation-circle text-4xl text-gray-300 mb-2"></i>
                        <span>Aún no se han distribuido cupos.</span>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if(method_exists($distribuciones, 'links'))
        <div class="mt-4 px-4 py-2"> {{-- Añadí padding para que se vea igual --}}
            {{ $distribuciones->appends(request()->query())->links() }}
        </div>
    @endif
</div>