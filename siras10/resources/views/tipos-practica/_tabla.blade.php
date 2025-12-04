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
                    <span>{{ $tipo->idTipoPractica }}</span>
                </td>
                <td class="py-2 px-4">
                    <span>{{ $tipo->nombrePractica }}</span>
                </td>
                <td class="py-2 px-4">
                    <div class="flex space-x-2">
                        @can('tipos-practica.update')
                        <button data-action="edit" data-id="{{ $tipo->idTipoPractica }}" title="Editar" class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 hover:bg-amber-600 text-white rounded-md transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        @endcan
                        @can('tipos-practica.delete')
                        <button data-action="delete" data-id="{{ $tipo->idTipoPractica }}" title="Eliminar" class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-150">
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