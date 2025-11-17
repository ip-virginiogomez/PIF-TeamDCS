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
                <td class="py-2 px-4">
                    <div class="flex items-center space-x-3">
                        <div>
                            <span class="font-medium">{{ $periodo->idPeriodo }}</span>
                        </div>
                    </div>
                </td>
                <td class="py-2 px-4">
                    <div class="flex items-center space-x-3">
                        <div>
                            <span class="font-medium">{{ $periodo->Año }}</span>
                        </div>
                    </div>
                </td>
                <td class="py-2 px-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        {{ \Carbon\Carbon::parse($periodo->fechaInicio)->format('d/m/Y') }}
                    </span>
                </td>
                <td class="py-2 px-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        {{ \Carbon\Carbon::parse($periodo->fechaFin)->format('d/m/Y') }}
                    </span>
                </td>
                <td class="py-2 px-4 flex space-x-2">
                    <button data-action="edit" data-id="{{ $periodo->idPeriodo }}" class="text-yellow-500 hover:text-yellow-700">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button data-action="delete" data-id="{{ $periodo->idPeriodo }}" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-4 px-4 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-calendar text-4xl text-gray-300 mb-2"></i>
                        <span>No hay períodos registrados.</span>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($periodos, 'links'))
    <div class="mt-4">
        {{ $periodos->appends(request()->query())->links() }}
    </div>
@endif
