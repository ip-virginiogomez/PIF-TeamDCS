<div class="overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                {{-- Nombre del Grupo (Siempre es útil saber qué grupo es) --}}
                <th scope="col" class="px-4 py-3">Grupo</th>
                
                {{-- DATOS SOLICITADOS --}}
                <th scope="col" class="px-4 py-3">Sede / Carrera</th>
                <th scope="col" class="px-4 py-3">Unidad Clínica</th>
                <th scope="col" class="px-4 py-3 text-center">Cupos</th>
                
                <th scope="col" class="px-4 py-3 text-center">Entrada</th>
                <th scope="col" class="px-4 py-3 text-center">Salida</th>
                
                <th scope="col" class="px-4 py-3 text-center">Horario</th>
                
                <th scope="col" class="px-4 py-3 text-right">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($grupos as $grupo)
                @php
                    $dist = $grupo->cupoDistribucion;
                @endphp
                <tr class="bg-white border-b hover:bg-gray-50 transition">
                    
                    <td class="px-4 py-4 font-bold text-gray-900">
                        {{ $grupo->nombreGrupo }}
                    </td>

                    {{-- Sede Carrera --}}
                    <td class="px-4 py-4">
                        @if($dist && $dist->sedeCarrera)
                            <div class="text-gray-900 font-medium">{{ $dist->sedeCarrera->nombreSedeCarrera }}</div>
                            <div class="text-xs text-gray-500">{{ $dist->sedeCarrera->sede->nombreSede ?? '' }}</div>
                        @else
                            <span class="text-gray-400 italic">Sin Asignar</span>
                        @endif
                    </td>

                    {{-- Unidad Clínica --}}
                    <td class="px-4 py-4">
                        {{ $dist->unidadClinica->nombreUnidad ?? 'No def.' }}
                    </td>

                    {{-- Cupos --}}
                    <td class="px-4 py-4 text-center">
                        @if($dist)
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded border border-blue-400">
                                {{ $dist->cantidadCupos ?? 0 }}
                            </span>
                        @else
                            -
                        @endif
                    </td>

                    {{-- Fechas (Formateadas) --}}
                    <td class="px-4 py-4 text-center whitespace-nowrap">
                        @if($dist && $dist->fechaInicio)
                            {{ \Carbon\Carbon::parse($dist->fechaInicio)->format('d/m/Y') }}
                        @else - @endif
                    </td>
                    <td class="px-4 py-4 text-center whitespace-nowrap">
                        @if($dist && $dist->fechaTermino)
                            {{ \Carbon\Carbon::parse($dist->fechaTermino)->format('d/m/Y') }}
                        @else - @endif
                    </td>

                    {{-- Horas (Combinadas Entrada/Salida) --}}
                    <td class="px-4 py-4 text-center whitespace-nowrap text-xs">
                        @if($dist && $dist->horaInicio && $dist->horaTermino)
                            <div><i class="fas fa-clock text-gray-400 mr-1"></i>{{ \Carbon\Carbon::parse($dist->horaInicio)->format('H:i') }}</div>
                            <div><i class="fas fa-sign-out-alt text-gray-400 mr-1"></i>{{ \Carbon\Carbon::parse($dist->horaTermino)->format('H:i') }}</div>
                        @else - @endif
                    </td>

                    {{-- Acciones --}}
                    <td class="px-4 py-4 text-right whitespace-nowrap">
                        <button data-action="edit" data-id="{{ $grupo->idGrupo }}" class="text-blue-600 hover:text-blue-900 mx-1 p-1" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button data-action="delete" data-id="{{ $grupo->idGrupo }}" class="text-red-600 hover:text-red-900 mx-1 p-1" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500 bg-gray-50">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-users-slash text-4xl mb-2 text-gray-300"></i>
                            <p>No se encontraron grupos.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="px-4 py-3 border-t bg-gray-50">
        {{ $grupos->links() }}
    </div>
</div>