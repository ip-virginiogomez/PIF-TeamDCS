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
                        $url = route('carreras.index', ['sort_by' => $columna, 'sort_direction' => $direction]);
                        return "<a href=\"{$url}\" class='sort-link text-left font-bold'>{$texto} {$symbol}</a>";
                    };
                @endphp
                <th class="py-2 px-4 text-left">{!! $link('idCarrera', 'ID') !!}</th>
                <th class="py-2 px-4 text-left">{!! $link('nombreCarrera', 'Nombre') !!}</th>
                <th class="py-2 px-4 text-left">{!! $link('fechaCreacion', 'Fecha de Creación') !!}</th>
                <th class="py-2 px-4 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($carreras as $carrera)
                <tr id="carrera-{{ $carrera->idCarrera }}" class="border-b">
                    <td class="py-2 px-4">
                        <div class="flex items-center space-x-3">
                            <div>
                                <span class="font-medium">{{ $carrera->idCarrera }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="py-2 px-4">
                        <div class="flex items-center space-x-3">
                            <div>
                                <span class="font-medium">{{ $carrera->nombreCarrera }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="py-2 px-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            {{ \Carbon\Carbon::parse($carrera->fechaCreacion)->format('d/m/Y') }}
                        </span>
                    </td>
                    <td class="py-2 px-4 flex space-x-2">
                        <button data-action="edit" data-id="{{ $carrera->idCarrera }}" class="text-yellow-500 hover:text-yellow-700">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button data-action="delete" data-id="{{ $carrera->idCarrera }}" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-4 px-4 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center p-4">
                            <i class="fas fa-graduation-cap text-4xl text-gray-300 mb-2"></i>
                            <span>No hay carreras registradas.</span>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($carreras, 'links'))
    <div class="mt-4">
        {{ $carreras->appends(request()->query())->links() }}
    </div>
@endif