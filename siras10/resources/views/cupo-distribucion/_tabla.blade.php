<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
            <tr>
                <th class="py-2 px-4 text-left">ID</th>
                {{-- ¡CAMBIO AQUÍ! --}}
                <th scope="col" class="py-2 px-4 text-left">
                    Centro Formador (Sede)
                </th>
                <th scope="col" class="py-2 px-4 text-left">
                    Carrera
                </th>
                <th class="py-2 px-4 text-left">Cupos Asignados</th>
                <th class="py-2 px-4 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($distribuciones as $distribucion)
            <tr class="border-b">
                <td class="py-2 px-4">{{ $distribucion->idCupoDistribucion }}</td>

                {{-- ======================================================= --}}
                {{-- ¡AQUÍ ESTÁ EL CAMBIO! --}}
                {{-- ======================================================= --}}

                {{-- COLUMNA 1: Centro Formador (Sede) --}}
                <td class="py-2 px-4">
                    <div class="font-medium text-gray-900">
                        {{-- Muestra: "Universidad de Concepción" --}}
                        {{ $distribucion->sedeCarrera->sede->centroFormador->nombreCentroFormador ?? 'CF Desc.' }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{-- Muestra: "(Los Ángeles)" --}}
                        ({{ $distribucion->sedeCarrera->sede->nombreSede ?? 'Sede Desc.' }})
                    </div>
                </td>

                {{-- COLUMNA 2: Carrera --}}
                <td class="py-2 px-4">
                    <div class="text-sm text-gray-900">
                        {{-- Muestra el nombre específico si existe, si no, el base --}}
                        {{ $distribucion->sedeCarrera->nombreSedeCarrera ?: ($distribucion->sedeCarrera->carrera->nombreCarrera ?? 'Carrera Desc.') }}
                    </div>
                </td>
                
                {{-- ======================================================= --}}
                {{-- FIN DEL CAMBIO --}}
                {{-- ======================================================= --}}

                <td class="py-2 px-4">{{ $distribucion->cantCupos }}</td> 
                <td class="py-2 px-4 flex space-x-2">
                    {{-- Tus botones están perfectos --}}
                    <button type="button" data-action="edit" data-id="{{ $distribucion->idCupoDistribucion }}" 
                            class="inline-flex items-center px-3 py-1 text-xs rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                        Editar
                    </button>
                    <button type="button" data-action="delete" data-id="{{ $distribucion->idCupoDistribucion }}" 
                            class="inline-flex items-center px-3 py-1 text-xs rounded-md text-red-700 bg-red-100 hover:bg-red-200">
                        Eliminar
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                {{-- Aumenta el colspan a 5 para que ocupe toda la fila --}}
                <td colspan="5" class="py-4 px-4 text-center">Aún no se han distribuido cupos para esta oferta.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>