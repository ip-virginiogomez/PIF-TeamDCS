<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
            <tr>
                <th class="py-2 px-4 text-left">ID</th>
                <th class="py-2 px-4 text-left">Nombre de la Práctica</th>
                <th class="py-2 px-4 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tiposPractica as $tipo)
            <tr class="border-b" id="tipo-{{ $tipo->idTipoPractica }}">
                <td class="py-2 px-4">{{ $tipo->idTipoPractica }}</td>
                <td class="py-2 px-4 font-medium">{{ $tipo->nombrePractica }}</td>
                <td class="py-2 px-4 flex space-x-2">
                    @can('tipos-practica.update')
                        <button data-action="edit" 
                            data-id="{{ $tipo->idTipoPractica }}" 
                            class="text-yellow-500 hover:text-yellow-700">
                            Editar
                        </button>
                    @endcan
                    @can('tipos-practica.delete')
                        <button 
                            data-action="delete" 
                            data-id="{{ $tipo->idTipoPractica }}" 
                            class="text-red-500 hover:text-red-700">
                            Eliminar
                        </button>
                    @endcan
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="py-4 px-4 text-center">No hay tipos de práctica registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($tiposPractica, 'links'))
    <div class="mt-4">
        {{ $tiposPractica->links() }}
    </div>
@endif