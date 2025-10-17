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
                <td class="py-2 px-4">{{ $oferta->periodo->Año ?? 'N/A' }}</td>
                <td class="py-2 px-4">{{ $oferta->unidadClinica->nombreUnidad ?? 'N/A' }}</td>
                <td class="py-2 px-4">{{ $oferta->carrera->nombreCarrera ?? 'N/A' }}</td>
                <td class="py-2 px-4 font-bold">{{ $oferta->cantCupos }}</td>
                <td class="py-2 px-4 text-sm">
                    {{ \Carbon\Carbon::parse($oferta->fechaEntrada)->format('d/m/Y') }} - 
                    {{ \Carbon\Carbon::parse($oferta->fechaSalida)->format('d/m/Y') }}
                </td>
                <td class="py-2 px-4 flex space-x-2">
                    {{-- CORRECCIÓN: Llamar a las funciones globales específicas y proteger con @can --}}
                    @can('cupo-ofertas.update')
                        <button onclick="editarCupoOferta({{ $oferta->idCupoOferta }})" class="text-yellow-500 hover:text-yellow-700">Editar</button>
                    @endcan
                    @can('cupo-ofertas.delete')
                        <button onclick="eliminarCupoOferta({{ $oferta->idCupoOferta }})" class="text-red-500 hover:text-red-700">Eliminar</button>
                    @endcan
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-4 px-4 text-center">No hay ofertas de cupo registradas.</td>
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
