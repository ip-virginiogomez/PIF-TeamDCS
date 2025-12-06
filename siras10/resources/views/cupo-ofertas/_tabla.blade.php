<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
            <tr>
                <th class="py-2 px-4 text-left">Período</th>
                <th class="py-2 px-4 text-left">
                    <div>Unidad Clínica</div>
                    <div class="text-xs font-normal text-gray-600">(Centro Salud)</div>
                </th>
                <th class="py-2 px-4 text-left">Tipo Práctica</th>
                <th class="py-2 px-4 text-left">Carrera</th>
                <th class="py-2 px-4 text-left">Cupos Ofertados</th>
                <th class="py-2 px-4 text-left">Cupos Asignados</th>
                <th class="py-2 px-4 text-left">Fechas</th>
                <th class="py-2 px-4 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cupoOfertas as $oferta)
            <tr class="border-b" id="oferta-{{ $oferta->idCupoOferta }}">
                <td class="py-2 px-4">
                    <span>{{ $oferta->periodo->Año ?? 'N/A' }}</span>
                </td>
                <td class="py-2 px-4">
                    <div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $oferta->unidadClinica->nombreUnidad ?? 'N/A' }}
                        </span>
                        @if($oferta->unidadClinica && $oferta->unidadClinica->centroSalud && $oferta->unidadClinica->centroSalud->nombreCentro)
                        <div class="text-xs text-gray-500 mt-1 ml-1">
                            {{ $oferta->unidadClinica->centroSalud->nombreCentro }}
                        </div>
                        @endif
                    </div>
                </td>
                <td class="py-2 px-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        {{ $oferta->tipoPractica->nombrePractica ?? 'N/A' }}
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
                <td class="py-2 px-4">
                    @php
                        $cuposAsignados = $oferta->cupo_distribuciones_sum_cant_cupos ?? 0;
                        $porcentaje = $oferta->cantCupos > 0 ? ($cuposAsignados / $oferta->cantCupos) * 100 : 0;
                        $colorClase = $cuposAsignados == 0 ? 'bg-gray-100 text-gray-800' : 
                                     ($porcentaje >= 100 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800');
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClase }}">
                        {{ $cuposAsignados }} / {{ $oferta->cantCupos }}
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
                <td class="py-2 px-4">
                    <div class="flex space-x-2">
                        <button data-action="edit" data-id="{{ $oferta->idCupoOferta }}" title="Editar" class="inline-flex items-center justify-center w-8 h-8 bg-amber-500 hover:bg-amber-600 text-white rounded-md transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button data-action="delete" data-id="{{ $oferta->idCupoOferta }}" title="Eliminar" class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                        <a href="{{ route('cupo-distribuciones.index', ['oferta_id' => $oferta->idCupoOferta]) }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-share-alt"></i> Distribuir
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="py-4 px-4 text-center text-gray-500">
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
