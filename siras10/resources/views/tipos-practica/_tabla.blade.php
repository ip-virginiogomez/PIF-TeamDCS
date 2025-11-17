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
                <td class="py-2 px-4">
                    <div class="flex items-center space-x-3">
                        <div>
                            <span class="font-medium">{{ $tipo->idTipoPractica }}</span>
                        </div>
                    </div>
                </td>
                <td class="py-2 px-4">
                    <div class="flex items-center space-x-3">
                        <div>
                            <span class="font-medium">{{ $tipo->nombrePractica }}</span>
                        </div>
                    </div>
                </td>
                <td class="py-2 px-4 flex space-x-2">
                    <button data-action="edit" data-id="{{ $tipo->idTipoPractica }}" class="text-yellow-500 hover:text-yellow-700">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button data-action="delete" data-id="{{ $tipo->idTipoPractica }}" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="py-4 px-4 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-2"></i>
                        <span>No hay tipos de práctica registrados.</span>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($tiposPractica, 'links'))
    <div class="mt-4">
        {{ $tiposPractica->appends(request()->query())->links() }}
    </div>
@endif