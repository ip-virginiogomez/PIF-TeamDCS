<div class="overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3">Sede / Carrera</th>
                <th scope="col" class="px-6 py-3">Centro de Salud</th>
                <th scope="col" class="px-6 py-3">Unidad Clínica</th>
                <th scope="col" class="px-6 py-3 text-center">Cupos</th>
                <th scope="col" class="px-6 py-3 text-center">Fechas</th>
                <th scope="col" class="px-6 py-3 text-center">Horario</th>
                <th scope="col" class="px-6 py-3 text-center">Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($distribuciones as $dist)
                @php $oferta = $dist->cupoOferta; @endphp
                
                <tr class="bg-white border-b hover:bg-gray-50 transition row-distribucion" data-id="{{ $dist->idCupoDistribucion }}">
                    
                    {{-- Sede y Carrera --}}
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $dist->sedeCarrera->nombreSedeCarrera ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-500">{{ $dist->sedeCarrera->sede->nombreSede ?? '' }}</div>
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
                        @if($oferta)
                            <div class="flex flex-col items-center justify-center space-y-1">
                                <span class="inline-flex items-center text-green-700 bg-green-50 px-2 py-0.5 rounded text-xs">
                                    <i class="fas fa-sign-in-alt mr-1"></i> {{ \Carbon\Carbon::parse($oferta->horaEntrada)->format('H:i') }}
                                </span>
                                <span class="inline-flex items-center text-red-700 bg-red-50 px-2 py-0.5 rounded text-xs">
                                    <i class="fas fa-sign-out-alt mr-1"></i> {{ \Carbon\Carbon::parse($oferta->horaSalida)->format('H:i') }}
                                </span>
                            </div>
                        @else - @endif
                    </td>
                    {{-- Botón Acción --}}
                    <td class="px-6 py-4 text-center">
                        <button 
                            type="button"
                            data-action="select-distribucion" 
                            data-id="{{ $dist->idCupoDistribucion }}"
                            data-summary="{{ $oferta->unidadClinica->nombreUnidad ?? 'Unidad' }} - {{ $dist->sedeCarrera->nombreSedeCarrera ?? 'Carrera' }}"
                            class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-3 py-2 focus:outline-none shadow-sm transition-colors duration-200">
                            Asignar Grupos
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No se encontraron distribuciones disponibles.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $distribuciones->links() }} 
    </div>
</div>