<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
            <tr>
                @php
                    $link = function ($columna, $texto) use ($sortBy, $sortDirection, $oferta) {
                        $direction = ($sortBy === $columna && $sortDirection == 'asc') ? 'desc' : 'asc';
                        $symbol = '';
                        if ($sortBy == $columna) {
                            $symbol = $sortDirection == 'asc' ? '↑' : '↓';
                        }
                        $url = route('cupo-distribuciones.index', [
                            'oferta_id' => $oferta->idCupoOferta, 
                            'sort_by' => $columna, 
                            'sort_direction' => $direction
                        ]);
                        return "<a href=\"{$url}\" class='sort-link text-left font-bold'>{$texto} {$symbol}</a>";
                    };
                @endphp

                <th class="py-2 px-4 text-left"> {!! $link('idCupoDistribucion', 'ID') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('sedeCarrera.sede.centroFormador.nombreCentroFormador', 'Centro Formador (Sede)') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('sedeCarrera.carrera.nombreCarrera', 'Carrera') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('cantCupos', 'Cupos Asignados') !!}</th>
                <th class="py-2 px-4 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($distribuciones as $distribucion)
            <tr class="border-b" id="dist-{{ $distribucion->idCupoDistribucion }}">
                <td class="py-2 px-4">
                    {{-- Estilo Sede: Texto simple --}}
                    <span class="font-medium">{{ $distribucion->idCupoDistribucion }}</span>
                </td>

                {{-- COLUMNA 1: Centro Formador (Sede) --}}
                <td class="py-2 px-4">
                    {{-- Estilo Sede: "Pill" azul para la relación principal --}}
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $distribucion->sedeCarrera->sede->centroFormador->nombreCentroFormador ?? 'CF Desc.' }}
                    </span>
                    <div class="text-sm text-gray-500">
                        ({{ $distribucion->sedeCarrera->sede->nombreSede ?? 'Sede Desc.' }})
                    </div>
                </td>

                {{-- COLUMNA 2: Carrera --}}
                <td class="py-2 px-4">
                    {{-- Estilo Sede: Texto simple --}}
                    <div class="text-sm text-gray-900 font-medium">
                        {{ $distribucion->sedeCarrera->nombreSedeCarrera ?: ($distribucion->sedeCarrera->carrera->nombreCarrera ?? 'Carrera Desc.') }}
                    </div>
                </td>
                
                <td class="py-2 px-4">
                    {{-- Estilo Sede: "Pill" verde para números/contacto --}}
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ $distribucion->cantCupos }}
                    </span>
                </td> 
                
                {{-- CAMBIO: Botones con estilo Sede (ícono + texto) --}}
                <td class="py-2 px-4 flex space-x-2">
                    <button type="button" data-action="edit" data-id="{{ $distribucion->idCupoDistribucion }}" 
                            class="text-yellow-500 hover:text-yellow-700">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button type="button" data-action="delete" data-id="{{ $distribucion->idCupoDistribucion }}" 
                            class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
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

    {{-- CAMBIO: Añadida la paginación (estilo Sede) --}}
    @if(method_exists($distribuciones, 'links'))
        <div class="mt-4 px-4 py-2"> {{-- Añadí padding para que se vea igual --}}
            {{ $distribuciones->appends(request()->query())->links() }}
        </div>
    @endif
</div>