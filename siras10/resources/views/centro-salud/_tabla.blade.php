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
                        $url = route('centro-salud.index', ['sort_by' => $columna, 'sort_direction' => $direction]);
                        return "<a href=\"{$url}\" class='sort-link text-left font-bold'>{$texto} {$symbol}</a>";
                    };
                @endphp
                <th class="py-2 px-4 text-left"> {!! $link('idCentroSalud', 'ID') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('nombreCentro', 'Nombre del Centro') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('director', 'Director') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('direccion', 'Dirección') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('ciudad.nombreCiudad', 'Ciudad') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('tipo_centro_salud.acronimo', 'Tipo') !!}</th>
                <th class="py-2 px-4 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($centrosSalud as $centro)
                <tr class="border-b" id="centro-{{ $centro->idCentroSalud }}">
                    <td class="py-2 px-4">
                        <div class="flex items-center space-x-3">
                            <div>
                                <span class="font-medium">{{ $centro->idCentroSalud }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="py-2 px-4">
                        <div class="flex items-center space-x-3">
                            <div>
                                <span class="font-medium">{{ $centro->nombreCentro }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="py-2 px-4">
                        <div>
                            <span class="font-medium text-gray-900">{{ $centro->director ?? 'No asignado' }}</span>
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
                            {{ $centro->tipoCentroSalud->acronimo ?? 'N/A' }}
                        </span>
                        <div class="text-xs text-gray-500">{{ $centro->tipoCentroSalud->nombreTipo ?? 'N/A' }}</div>
                    </td>
                    <td class="py-2 px-4 flex space-x-2">
                        <button 
                            data-action="edit" 
                            data-id="{{ $centro->idCentroSalud }}" 
                            class="text-yellow-500 hover:text-yellow-700">
                            Editar
                        </button>
                        <button 
                            data-action="delete" 
                            data-id="{{ $centro->idCentroSalud }}" 
                            class="text-red-500 hover:text-red-700">
                            Eliminar
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-4 px-4 text-center">No hay centros de salud registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if(method_exists($centrosSalud, 'links'))
        <div class="mt-4">
            {{ $centrosSalud->appends(request()->query())->links() }}
        </div>
    @endif
</div>