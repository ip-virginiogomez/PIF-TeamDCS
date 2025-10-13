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
                        $url = route('sede.index', ['sort_by' => $columna, 'sort_direction' => $direction]);
                        return "<a href=\"{$url}\" class='sort-link text-left font-bold'>{$texto} {$symbol}</a>";
                    };
                @endphp
                <th class="py-2 px-4 text-left"> {!! $link('idSede', 'ID') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('centroFormador.nombreCentroFormador', 'Centro Formador') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('nombreSede', 'Sede') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('direccion', 'Dirección') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('fechaCreacion', 'Fecha Creación') !!}</th>
                <th class="py-2 px-4 text-left"> {!! $link('numContacto', 'Contacto') !!}</th>
                <th class="py-2 px-4 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sedes as $sede)
            <tr class="border-b" id="sede-{{ $sede->idSede }}">
                <td class="py-2 px-4">
                    <div class="flex items-center space-x-3">
                        <div>
                            <span class="font-medium">{{ $sede->idSede }}</span>
                        </div>
                    </div>
                </td>
                <td class="py-2 px-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $sede->centroFormador->nombreCentroFormador ?? 'N/A' }}
                    </span>
                </td>
                <td class="py-2 px-4">
                    <div class="flex items-center space-x-3">
                        <div>
                            <span class="font-medium">{{ $sede->nombreSede }}</span>
                        </div>
                    </div>
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
                <td class="py-2 px-4 flex space-x-2">
                    <button onclick="editarSede({{ $sede->idSede }})" class="text-yellow-500 hover:text-yellow-700">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button onclick="eliminarSede({{ $sede->idSede }})" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
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
    @if(method_exists($sedes, 'links'))
        <div class="mt-4">
            {{ $sedes->appends(request()->query())->links() }}
        </div>
    @endif
</div>