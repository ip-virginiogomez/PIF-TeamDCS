<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
            <tr>
                <th class="py-2 px-4 text-left">ID</th>
                {{-- ¡CAMBIO AQUÍ! --}}
                <th class="py-2 px-4 text-left">Sede / Carrera (Centro Formador)</th> 
                <th class="py-2 px-4 text-left">Cupos Asignados</th>
                <th class="py-2 px-4 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($distribuciones as $distribucion)
            <tr class="border-b">
                <td class="py-2 px-4">{{ $distribucion->idCupoDistribucion }}</td>
                {{-- ¡CAMBIO AQUÍ! Muestra la info de Sede/Carrera --}}
                <td class="py-2 px-4 font-medium">
                    {{ $distribucion->sedeCarrera->sede->nombreSede ?? 'Sede Desc.' }} 
                    ({{ $distribucion->sedeCarrera->sede->centroFormador->nombreCentroFormador ?? 'CF Desc.' }}) 
                    - {{ $distribucion->sedeCarrera->carrera->nombreCarrera ?? 'Carrera Desc.' }}
                </td> 
                <td class="py-2 px-4">{{ $distribucion->cantCupos }}</td> 
                <td class="py-2 px-4 flex space-x-2">
                    {{-- Los botones están bien --}}
                    <button data-action="edit" data-id="{{ $distribucion->idCupoDistribucion }}">Editar</button>
                    <button data-action="delete" data-id="{{ $distribucion->idCupoDistribucion }}">Eliminar</button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="py-4 px-4 text-center">Aún no se han distribuido cupos para esta oferta.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>