{{-- _tabla.blade.php - Tabla de carreras por sede --}}
<div class="overflow-x-auto">
    @if($carrerasEspecificas->count() > 0)
        <!-- Contador visual -->
        <div class="mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-5 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-lg font-semibold text-blue-900">
                        {{ $carrerasEspecificas->count() }} 
                        carrera{{ $carrerasEspecificas->count() > 1 ? 's' : '' }} asignada{{ $carrerasEspecificas->count() > 1 ? 's' : '' }}
                    </p>
                    <p class="text-sm text-blue-700">a esta sede</p>
                </div>
            </div>
        </div>

        <!-- Tabla moderna -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-indigo-600 to-blue-600">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            Carrera Base
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            Nombre en Sede
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            Código
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($carrerasEspecificas as $sc)
                        <tr class="hover:bg-gray-50 transition-all duration-200 transform hover:scale-[1.001]">
                            <!-- Carrera Base -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-2 h-8 bg-indigo-500 rounded-full mr-3"></div>
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $sc->carrera->nombreCarrera }}
                                    </div>
                                </div>
                            </td>

                            <!-- Nombre Específico -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-800">
                                    <span class="inline-flex items-center">
                                        @if($sc->nombreSedeCarrera && $sc->nombreSedeCarrera !== $sc->carrera->nombreCarrera)
                                            <span class="font-medium text-indigo-700">
                                                {{ $sc->nombreSedeCarrera }}
                                            </span>
                                            <span class="ml-2 text-xs text-gray-500 italic">
                                                (personalizado)
                                            </span>
                                        @else
                                            <span class="text-gray-600">
                                                {{ $sc->carrera->nombreCarrera }}
                                            </span>
                                            <span class="ml-2 text-xs text-gray-400">
                                                (por defecto)
                                            </span>
                                        @endif
                                    </span>
                                </div>
                            </td>

                            <!-- Código -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold 
                                            bg-gradient-to-r from-purple-100 to-pink-100 
                                            text-purple-800 border border-purple-300">
                                    {{ $sc->codigoCarrera }}
                                </span>
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center space-x-3">
                                    
                                    <!-- Malla -->
                                    <button 
                                        type="button"
                                        data-action="malla" 
                                        data-id="{{ $sc->idSedeCarrera }}"
                                        class="group inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white text-xs font-medium rounded-lg shadow hover:from-green-600 hover:to-green-700 transform hover:scale-105 transition-all duration-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h2m9-9V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10m-5 3v6m-3-3h6" />
                                        </svg>
                                        Archivos
                                    </button>
                                    
                                    <!-- Editar -->
                                    <button 
                                        type="button"
                                        data-action="edit" 
                                        data-id="{{ $sc->idSedeCarrera }}"
                                        class="group inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-medium rounded-lg shadow hover:from-blue-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Editar
                                    </button>

                                    <!-- Eliminar -->
                                    <button 
                                        type="button"
                                        data-action="delete" 
                                        data-id="{{ $sc->idSedeCarrera }}"
                                        class="group inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-medium rounded-lg shadow hover:from-red-600 hover:to-red-700 transform hover:scale-105 transition-all duration-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <!-- Sin carreras -->
        <div class="text-center py-16 bg-gradient-to-b from-gray-50 to-white rounded-2xl border-2 border-dashed border-gray-300">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-4 text-2xl font-bold text-gray-900">Aún no hay carreras</h3>
            <p class="mt-2 text-gray-600 max-w-md mx-auto">
                Esta sede está lista para recibir sus primeras carreras específicas.
            </p>
            <div class="mt-8">
                <span class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl shadow-lg hover:bg-indigo-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Haz clic en "Añadir Carrera" para comenzar
                </span>
            </div>
        </div>
    @endif
</div>