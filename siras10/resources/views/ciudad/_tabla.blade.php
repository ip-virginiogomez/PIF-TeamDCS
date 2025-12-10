<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
            <tr>
                @php
                    $getSortIcon = function($column) use ($sortBy, $sortDirection) {
                        if ($sortBy !== $column) {
                            return '<svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>';
                        }
                        return $sortDirection === 'asc' 
                            ? '<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>'
                            : '<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
                    };
                @endphp
                <th class="py-2 px-4 text-left cursor-pointer hover:bg-gray-100" onclick="toggleSort('nombreCiudad')">
                    <div class="flex items-center gap-1">
                        Nombre de la Ciudad {!! $getSortIcon('nombreCiudad') !!}
                    </div>
                </th>
                <th class="py-2 px-4 text-left cursor-pointer hover:bg-gray-100" onclick="toggleSort('fechacreacion')">
                    <div class="flex items-center gap-1">
                        Fecha Creación {!! $getSortIcon('fechacreacion') !!}
                    </div>
                </th>
                <th class="py-2 px-4 text-left">Centros Asociados</th>
                <th class="py-2 px-4 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ciudades as $ciudad)
            <tr class="border-b" id="ciudad-{{ $ciudad->idCiudad }}">
                <td class="py-2 px-4">
                    <span class="font-medium text-gray-900">{{ $ciudad->nombreCiudad }}</span>
                </td>
                <td class="py-2 px-4 text-sm text-gray-600">
                    {{ $ciudad->fechacreacion ? \Carbon\Carbon::parse($ciudad->fechacreacion)->format('d/m/Y') : 'N/A' }}
                </td>
                <td class="py-2 px-4">
                    @php
                        $count = $ciudad->centroSaluds()->count();
                    @endphp
                    @if($count > 0)
                        <div class="flex items-center gap-2">
                            <button onclick="verCentrosAsociados('ciudad-{{ $ciudad->idCiudad }}', '{{ $ciudad->nombreCiudad }}')" class="inline-flex items-center px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded transition-colors duration-150">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Ver
                            </button>
                        </div>
                        <div id="centros-ciudad-{{ $ciudad->idCiudad }}" class="hidden">
                            @foreach($ciudad->centroSaluds as $centro)
                                <div data-centro>{{ $centro->nombreCentro }}</div>
                            @endforeach
                        </div>
                    @else
                        <span class="text-gray-400 text-sm">Sin centros</span>
                    @endif
                </td>
                <td class="py-2 px-4">
                    <div class="flex space-x-2">
                        @can('ciudades.update')
                        <button data-action="edit" data-id="{{ $ciudad->idCiudad }}" title="Editar" class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 hover:bg-amber-600 text-white rounded-md transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        @endcan
                        @can('ciudades.delete')
                        <button data-action="delete" data-id="{{ $ciudad->idCiudad }}" title="Eliminar" class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-150">
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
                    <div class="flex flex-col items-center">
                        <svg class="w-16 h-16 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>No hay ciudades registradas.</span>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Paginación --}}
@if ($ciudades->hasPages())
<div class="mt-4">
    {{ $ciudades->links() }}
</div>
@endif

<script>
    function toggleSort(column) {
        const urlParams = new URLSearchParams(window.location.search);
        const currentSort = urlParams.get('sort_by');
        const currentDirection = urlParams.get('sort_direction') || 'asc';
        
        let newDirection = 'asc';
        if (currentSort === column && currentDirection === 'asc') {
            newDirection = 'desc';
        }
        
        urlParams.set('sort_by', column);
        urlParams.set('sort_direction', newDirection);
        
        window.location.search = urlParams.toString();
    }
</script>
