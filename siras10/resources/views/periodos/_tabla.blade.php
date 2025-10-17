<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
            <tr>
                <th class="py-2 px-4 text-left">ID</th>
                <th class="py-2 px-4 text-left">Año</th>
                <th class="py-2 px-4 text-left">Fecha de Inicio</th>
                <th class="py-2 px-4 text-left">Fecha de Fin</th>
                <th class="py-2 px-4 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($periodos as $periodo)
            <tr class="border-b" id="periodo-{{ $periodo->idPeriodo }}">
                <td class="py-2 px-4">{{ $periodo->idPeriodo }}</td>
                <td class="py-2 px-4 font-medium">{{ $periodo->Año }}</td>
                <td class="py-2 px-4">{{ \Carbon\Carbon::parse($periodo->fechaInicio)->format('d/m/Y') }}</td>
                <td class="py-2 px-4">{{ \Carbon\Carbon::parse($periodo->fechaFin)->format('d/m/Y') }}</td>
                <td class="py-2 px-4 flex space-x-2">
                    @can('periodos.update')
                        {{-- CAMBIO: Llamar a la función global específica --}}
                        <button onclick="editarPeriodo({{ $periodo->idPeriodo }})" class="text-yellow-500 hover:text-yellow-700">Editar</button>
                    @endcan
                    @can('periodos.delete')
                        {{-- CAMBIO: Llamar a la función global específica --}}
                        <button onclick="eliminarPeriodo({{ $periodo->idPeriodo }})" class="text-red-500 hover:text-red-700">Eliminar</button>
                    @endcan
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-4 px-4 text-center">No hay períodos registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if(method_exists($periodos, 'links'))
    <div class="mt-4">
        {{ $periodos->links() }}
    </div>
@endif
