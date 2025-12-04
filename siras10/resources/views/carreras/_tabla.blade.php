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
                        <span>{{ $carrera->idCarrera }}</span>
                    </td>
                    <td class="py-2 px-4">
                        <span>{{ $carrera->nombreCarrera }}</span>
                    </td>
                    <td class="py-2 px-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            {{ \Carbon\Carbon::parse($carrera->fechaCreacion)->format('d/m/Y') }}
                        </span>
                    </td>
                    <td class="py-2 px-4">
                        <div class="flex space-x-2">
                            @can('carreras.update')
                            <button data-action="edit" data-id="{{ $carrera->idCarrera }}" title="Editar" class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 hover:bg-amber-600 text-white rounded-md transition-colors duration-150">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            @endcan
                            @can('carreras.delete')
                            <button data-action="delete" data-id="{{ $carrera->idCarrera }}" title="Eliminar" class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-150">
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