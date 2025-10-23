<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
            <tr>
                <th class="py-2 px-4 text-left">ID</th>
                <th class="py-2 px-4 text-left">Nombre de la Unidad</th>
                <th class="py-2 px-4 text-left">Centro de Salud</th>
                <th class="py-2 px-4 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($unidadesClinicas as $unidad)
            <tr class="border-b" id="unidad-{{ $unidad->idUnidadClinica }}">
                <td class="py-2 px-4">{{ $unidad->idUnidadClinica }}</td>
                <td class="py-2 px-4 font-medium">{{ $unidad->nombreUnidad }}</td>
                <td class="py-2 px-4">{{ $unidad->centroSalud->nombreCentro ?? 'N/A' }}</td>
                <td class="py-2 px-4 flex space-x-2">
                    @can('unidad-clinicas.update')
                        <button 
                            data-action="edit" 
                            data-id="{{ $unidad->idUnidadClinica }}" 
                            class="text-yellow-500 hover:text-yellow-700">
                            Editar
                        </button>
                    @endcan
                    @can('unidad-clinicas.delete')
                        <button 
                            data-action="delete" 
                            data-id="{{ $unidad->idUnidadClinica }}" 
                            class="text-red-500 hover:text-red-700">
                            Eliminar
                        </button>
                    @endcan
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="py-4 px-4 text-center">No hay unidades clínicas registradas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($unidadesClinicas, 'links'))
    <div class="mt-4">
        {{ $unidadesClinicas->links() }}
    </div>
@endif