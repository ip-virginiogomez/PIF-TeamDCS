<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
            <tr>
                <th class="py-2 px-4 text-left">Período</th>
                <th class="py-2 px-4 text-left">Unidad</th>
                <th class="py-2 px-4 text-left">Carrera</th>
                <th class="py-2 px-4 text-left">Cupos</th>
                <th class="py-2 px-4 text-left">Fechas</th>
                <th class="py-2 px-4 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cupoOfertas as $oferta)
            <tr class="border-b" id="oferta-{{ $oferta->idCupoOferta }}">
                <td class="py-2 px-4">
                    <div class="flex items-center space-x-3">
                        <div>
                            <span class="font-medium">{{ $oferta->periodo->Año ?? 'N/A' }}</span>
                        </div>
                    </div>
                </td>
                <td class="py-2 px-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $oferta->unidadClinica->nombreUnidad ?? 'N/A' }}
                    </span>
                </td>
                <td class="py-2 px-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $oferta->carrera->nombreCarrera ?? 'N/A' }}
                    </span>
                </td>
                <td class="py-2 px-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ $oferta->cantCupos }}
                    </span>
                </td>
                <td class="py-2 px-4 text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        {{ \Carbon\Carbon::parse($oferta->fechaEntrada)->format('d/m/Y') }}
                    </span>
                    <span class="text-gray-500"> - </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        {{ \Carbon\Carbon::parse($oferta->fechaSalida)->format('d/m/Y') }}
                    </span>
                </td>
                <td class="py-2 px-4 flex space-x-2">
                    <button data-action="edit" data-id="{{ $oferta->idCupoOferta }}" class="text-yellow-500 hover:text-yellow-700">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button data-action="delete" data-id="{{ $oferta->idCupoOferta }}" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                    <a href="{{ route('cupo-distribuciones.index', ['oferta_id' => $oferta->idCupoOferta]) }}" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-share-alt"></i> Distribuir
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-4 px-4 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-users text-4xl text-gray-300 mb-2"></i>
                        <span>No hay ofertas de cupo registradas.</span>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($cupoOfertas, 'links'))
    <div class="mt-4">
        {{ $cupoOfertas->appends(request()->query())->links() }}
    </div>
@endif
