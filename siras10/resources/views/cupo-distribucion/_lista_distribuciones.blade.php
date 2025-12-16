<div class="overflow-x-auto bg-white shadow-md rounded-lg">
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3">Centro Formador / Carrera / Sede</th>
                <th scope="col" class="px-6 py-3">Centro de Salud</th>
                <th scope="col" class="px-6 py-3">Unidad Clínica</th>
                <th scope="col" class="px-6 py-3">Tipo de Práctica / Asignatura</th>
                <th scope="col" class="px-6 py-3 text-center">Cupos</th>
                <th scope="col" class="px-6 py-3 text-center">Fechas</th>
                <th scope="col" class="px-6 py-3 text-center">Horario</th>
                <th scope="col" class="px-6 py-3 text-center">Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($distribuciones as $dist)
                @php $oferta = $dist->cupoOferta; @endphp
                
                <tr class="bg-white border-b hover:bg-gray-50 transition">
                    
                    {{-- Sede y Carrera --}}
                    <td class="px-6 py-4">
                        <div class="text-xs font-bold text-indigo-600 mb-1">
                            {{ $dist->cupoDemanda->sedeCarrera->sede->centroFormador->nombreCentroFormador ?? 'N/A' }}
                        </div>
                        <div class="font-medium text-gray-900">{{ $dist->cupoDemanda->sedeCarrera->nombreSedeCarrera ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-500">{{ $dist->cupoDemanda->sedeCarrera->sede->nombreSede ?? '' }}</div>
                    </td>
                    {{-- Centro de Salud --}}
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">
                            {{ $oferta->unidadClinica->centroSalud->nombreCentro ?? 'N/A' }}
                        </div>
                    </td>
                    {{-- Unidad Clínica --}}
                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $oferta->unidadClinica->nombreUnidad ?? 'N/A' }}
                    </td>
                    {{-- Tipo de Práctica --}}
                    <td class="px-6 py-4">
                        <div class="mb-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ $oferta->tipoPractica->nombrePractica ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="text-xs text-gray-500">
                            <span class="font-semibold">Asignatura:</span> {{ $dist->cupoDemanda->asignatura ?? 'N/A' }}
                        </div>
                    </td>
                    {{-- Cupos --}}
                    <td class="px-6 py-4 text-center">
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded border border-blue-400">
                            {{ $dist->cantCupos }}
                        </span>
                    </td>
                    
                    {{-- Fechas --}}
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        @if($oferta)
                            <div class="text-gray-900">{{ \Carbon\Carbon::parse($oferta->fechaEntrada)->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-400">hasta</div>
                            <div class="text-gray-900">{{ \Carbon\Carbon::parse($oferta->fechaSalida)->format('d/m/Y') }}</div>
                        @else - @endif
                    </td>
                    {{-- Horario --}}
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        @if($oferta && $oferta->horarios->count() > 0)
                            <button 
                                type="button"
                                onclick='verHorario(@json($oferta->horarios))'
                                title="Ver Horario"
                                class="inline-flex items-center justify-center w-8 h-8 bg-teal-500 hover:bg-teal-600 text-white rounded-md transition-colors duration-150">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </button>
                        @else
                            <span class="text-gray-400 text-xs">Sin horario</span>
                        @endif
                    </td>
                    {{-- Botón Acción --}}
                    
                    <td class="px-6 py-4 text-center">
                        @can('cupo-distribuciones.delete')
                        <button data-action="delete-distribucion" data-id="{{ $dist->idCupoDistribucion }}" title="Eliminar" class="inline-flex items-center justify-center w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="px-6 py-4 text-center text-gray-500">No se han realizado distribuciones aún.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $distribuciones->appends(request()->query())->links() }} 
    </div>
</div>
