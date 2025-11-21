<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
            <tr>
                <th class="py-2 px-4 text-left">Carrera Base</th>
                <th class="py-2 px-4 text-left">Nombre en Sede</th>
                <th class="py-2 px-4 text-left">Código</th>
                <th class="py-2 px-4 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($carrerasEspecificas as $sc)
                <tr class="border-b hover:bg-gray-50" id="sede-carrera-{{ $sc->idSedeCarrera }}">
                    <td class="py-2 px-4">
                        <span class="font-medium">{{ $sc->carrera->nombreCarrera }}</span>
                    </td>
                    <td class="py-2 px-4">
                        @if($sc->nombreSedeCarrera && $sc->nombreSedeCarrera !== $sc->carrera->nombreCarrera)
                            <span class="font-medium text-blue-700">{{ $sc->nombreSedeCarrera }}</span>
                        @else
                            <span class="text-gray-600">{{ $sc->carrera->nombreCarrera }}</span>
                            <span class="text-xs text-gray-400 ml-1">(por defecto)</span>
                        @endif
                    </td>
                    <td class="py-2 px-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            {{ $sc->codigoCarrera }}
                        </span>
                    </td>
                    <td class="py-2 px-4 flex space-x-2">
                        @can('sede-carrera.update')
                        <button 
                            data-action="edit" 
                            data-id="{{ $sc->idSedeCarrera }}" 
                            class="text-yellow-500 hover:text-yellow-700">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        @endcan
                        
                        @can('sede-carrera.delete')
                        <button 
                            data-action="delete" 
                            data-id="{{ $sc->idSedeCarrera }}" 
                            class="text-red-500 hover:text-red-700">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-8">
                        <div class="flex flex-col items-center justify-center text-gray-400">
                            <i class="fas fa-graduation-cap text-6xl mb-4"></i>
                            <p class="text-lg">No hay carreras asignadas a esta sede</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
