<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">Centro Formador</th>
                <th scope="col" class="px-6 py-3">Sede</th>
                <th scope="col" class="px-6 py-3">Carrera</th>
                <th scope="col" class="px-6 py-3">Cupos Solicitados</th>
                <th scope="col" class="px-6 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($demandas as $demanda)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $demanda->sedeCarrera->sede->centroFormador->nombreCentroFormador }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $demanda->sedeCarrera->sede->nombreSede }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $demanda->sedeCarrera->carrera->nombreCarrera }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $demanda->cuposSolicitados }}
                    </td>
                    <td class="px-6 py-4 flex gap-2">
                        <button data-action="edit" data-id="{{ $demanda->idDemandaCupo }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Editar</button>
                        
                        <button data-action="delete" data-id="{{ $demanda->idDemandaCupo }}" class="font-medium text-red-600 dark:text-red-500 hover:underline">Eliminar</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center">No hay demandas registradas para este periodo.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
