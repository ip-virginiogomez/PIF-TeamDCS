{{-- Este archivo (resources/views/sede-carrera/_tabla.blade.php) 
     solo debe contener la tabla y su lógica --}}

<div class="overflow-x-auto">
    @if($carrerasEspecificas->count() > 0)
        <div class="mb-4">
            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>{{ $carrerasEspecificas->count() }}</strong> carrera(s) específica(s) asignada(s) a esta sede.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Carrera Base</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Específico</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($carrerasEspecificas as $sedeCarrera)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $sedeCarrera->carrera->nombreCarrera }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $sedeCarrera->nombreSedeCarrera ?: $sedeCarrera->carrera->nombreCarrera }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $sedeCarrera->codigoCarrera }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-2">
                                    <button type="button" data-action="edit" data-id="{{ $sedeCarrera->idSedeCarrera }}" class="inline-flex items-center px-3 py-1 text-xs rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">Editar</button>
                                    <button type="button" data-action="delete" data-id="{{ $sedeCarrera->idSedeCarrera }}" class="inline-flex items-center px-3 py-1 text-xs rounded-md text-red-700 bg-red-100 hover:bg-red-200">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12">
            <h3 class="mt-4 text-lg font-medium text-gray-900">No hay carreras asignadas</h3>
            <p class="mt-2 text-sm text-gray-500">Esta sede aún no tiene carreras específicas asignadas.</p>
            <div class="mt-6">
                {{-- No necesitas el botón aquí, porque el botón "Añadir Carrera" principal ya está visible --}}
            </div>
        </div>
    @endif
</div>